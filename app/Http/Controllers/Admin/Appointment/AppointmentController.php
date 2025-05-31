<?php

namespace App\Http\Controllers\Admin\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use App\Models\DoctorVisit;
use App\Models\DoctorSchedule;
use DataTables;
use Carbon\Carbon;
use DB;

class AppointmentController extends Controller
{

    public function index()
    {
         $pageTitle = 'Appointment List';
        $statuses = ['All', 'Pending Confirmation', 'Confirmed', 'Treated', 'Cancelled', 'Requested'];
        return view('admin.appointments.index', compact('statuses','pageTitle'));
    }

    public function getData($status)
    {
        $query = Appointment::with(['patient', 'doctor','visit','payment']);

        if ($status !== 'All') {
            $query->where('status', $status)->latest()->get();
        }

        return \DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('patient', fn($row) => $row->patient->name ?? 'N/A')
            ->addColumn('doctor', fn($row) => $row->doctor->name ?? 'N/A')
            ->addColumn('visit_type', fn($row) => $row->visit->visit_description ?? 'N/A')
            ->addColumn('bill_status', function ($row) {
                if ($row->payment && $row->payment->status) {
                        return '<span class="btn btn-sm btn-success text-white">' . ucfirst($row->payment->status) . '</span>';
                    }
                    return '<span class="btn btn-sm btn-danger text-white">Unpaid</span>';
                })

            ->editColumn('appointment_date', function ($row) {
                $date = \Carbon\Carbon::parse($row->appointment_date)->format('d-m-Y');
                $slot = $row->time_slot;
                return $date .'<br>'. ($slot ? ' (' . $slot . ')' : '');
            })

            ->addColumn('actions', function ($row) {
                return '<a href="' . route('admin.appointment.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                <button data-id="'.$row->id.'" class="deleteBtn btn btn-sm btn-danger">Delete</button>
                ';
            })
            ->rawColumns(['actions','bill_status','appointment_date'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Add Appointment';
        $doctors = User::where('role','doctor')->get();
        $patients = User::where('role','patient')->get();
        return view('admin.appointments.create', compact('doctors','patients','pageTitle'));
    }

    public function getVisitTypes($doctor_id)
    {
        $visitTypes = DoctorVisit::where('doctor_id', $doctor_id)->get();

        return response()->json([
            'visit_types' => $visitTypes
        ]);
    }

    public function getVisitCharge($doctorId, $visitTypeId)
    {
        $pivot = DB::table('doctor_visits')
            ->where('doctor_id', $doctorId)
            ->where('id', $visitTypeId)
            ->first();

        return response()->json([
            'visit_charges' => $pivot ? $pivot->visit_charges : null
        ]);
    }

   
    public function getAvailableSlots(Request $request)
    {
        $doctorId = $request->doctor_id;
        $date = $request->date;

        if (!$doctorId || !$date) {
            return response()->json(['slots' => []]);
        }

        $dayName = Carbon::parse($date)->format('l');

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('weekday', $dayName)
            ->first();

        if (!$schedule) {
            return response()->json(['slots' => []]);
        }

        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
        $interval = 60; // slot interval in minutes

        $slots = [];

        while ($start->addMinutes(0)->lt($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes($interval);

            if ($slotEnd->gt($end)) break;

            $slots[] = $slotStart->format('h:i A') . ' - ' . $slotEnd->format('h:i A');
            $start->addMinutes($interval);
        }

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $payNow = $request->has('pay_now');

        $rules = [
            'patient_id'       => 'required|exists:users,id',
            'doctor_id'        => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'visit_type'       => 'required|string',
            'time_slot'        => 'nullable|string',
            'visit_charges'    => 'required|numeric',
            'discount'         => 'nullable|numeric',
            'total_amount'     => 'required|numeric',
            'status'           => 'nullable|string',
            'notes'            => 'nullable|string',
        ];
        $data = $request->validate($rules);
        $data['pay_now'] = $payNow;

        $appointment = Appointment::create($data);

        if ($payNow) {
            Payment::create([
                'appointment_id' => $appointment->id,
                'amount'         => $data['total_amount'],
                'deposit_type'   => $request->input('payment_mode'),
                'card_number'    => $request->input('card_number'),
                'expiry_date'    => $request->input('expiry_date'),
                'is_paid'        => true,
                'status'         => 'paid',
                'paid_at'        => now(),
            ]);
        }

        return redirect()->route('admin.appointment.index')->with('success', 'Appointment booked successfully!');
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Appointment';
        $appointment = Appointment::findOrFail($id);
        $doctors = User::where('role','doctor')->get();
        $patients = User::where('role','patient')->get();
        $visitTypes = DoctorVisit::where('doctor_id', $appointment->doctor_id)->get();
        return view('admin.appointments.edit',compact('doctors','patients','appointment','visitTypes','pageTitle'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'patient_id'       => 'required|exists:users,id',
            'doctor_id'        => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'visit_type'       => 'required|string',
            'time_slot'        => 'nullable|string',
            'visit_charges'    => 'required|numeric',
            'discount'         => 'nullable|numeric',
            'total_amount'     => 'required|numeric',
            'status'           => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        $data['pay_now'] = $request->has('pay_now') ? 1 : 0;

        $appointment->update($data);

        // Payment handling
        if ($data['pay_now']) {
            Payment::updateOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'amount'       => $data['total_amount'],
                    'deposit_type' => $request->input('payment_mode'),
                    'card_number'  => $request->input('card_number'),
                    'expiry_date'  => $request->input('expiry_date'),
                    'cvv'          => $request->input('cvv'),
                    'is_paid'      => true,
                    'status'       => 'paid',
                    'paid_at'      => now(),
                ]
            );
        }

        return redirect()->route('admin.appointment.index')->with('success', 'Appointment updated successfully!');
    }

    public function destroy($id)
    {
        Appointment::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Appointment deleted successfully.'
        ]);
    }

    public function todaysAppointment(Request $request)
    {
        $pageTitle = 'Todays Appointment';
        if ($request->ajax()) {
            $query = Appointment::with(['patient', 'doctor', 'visit', 'payment'])
                ->whereDate('appointment_date', today()); // ✅ Date-only comparison

            return \DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('patient', fn($row) => $row->patient->name ?? 'N/A')
                ->addColumn('doctor', fn($row) => $row->doctor->name ?? 'N/A')
                ->addColumn('visit_type', fn($row) => $row->visit->visit_description ?? 'N/A')
                ->addColumn('bill_status', function ($row) {
                    if ($row->payment && $row->payment->status) {
                        return '<span class="btn btn-sm btn-success text-white">' . ucfirst($row->payment->status) . '</span>';
                    }
                    return '<span class="btn btn-sm btn-danger text-white">Unpaid</span>';
                })
                ->editColumn('appointment_date', function ($row) {
                    $date = \Carbon\Carbon::parse($row->appointment_date)->format('d-m-Y');
                    $slot = $row->time_slot;
                    return $date . '<br>' . ($slot ? ' (' . $slot . ')' : '');
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.appointment.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <button data-id="' . $row->id . '" class="deleteBtn btn btn-sm btn-danger">Delete</button>';
                })
                ->rawColumns(['actions', 'bill_status', 'appointment_date'])
                ->make(true);
        }

        return view('admin.appointments.today_appointment', compact('pageTitle'));
    }
    public function upcomingAppointment(Request $request)
    {
        $pageTitle = 'Upcoming Appointment';
        if ($request->ajax()) {
            $query = Appointment::with(['patient', 'doctor', 'visit', 'payment'])
                ->whereDate('appointment_date', '>', today()); // ✅ Date-only comparison

            return \DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('patient', fn($row) => $row->patient->name ?? 'N/A')
                ->addColumn('doctor', fn($row) => $row->doctor->name ?? 'N/A')
                ->addColumn('visit_type', fn($row) => $row->visit->visit_description ?? 'N/A')
                ->addColumn('bill_status', function ($row) {
                    if ($row->payment && $row->payment->status) {
                        return '<span class="btn btn-sm btn-success text-white">' . ucfirst($row->payment->status) . '</span>';
                    }
                    return '<span class="btn btn-sm btn-danger text-white">Unpaid</span>';
                })
                ->editColumn('appointment_date', function ($row) {
                    $date = \Carbon\Carbon::parse($row->appointment_date)->format('d-m-Y');
                    $slot = $row->time_slot;
                    return $date . '<br>' . ($slot ? ' (' . $slot . ')' : '');
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.appointment.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <button data-id="' . $row->id . '" class="deleteBtn btn btn-sm btn-danger">Delete</button>';
                })
                ->rawColumns(['actions', 'bill_status', 'appointment_date'])
                ->make(true);
        }

        return view('admin.appointments.upcoming_appointment', compact('pageTitle'));
    }

    public function requestedAppointment(Request $request)
    {
        $pageTitle = 'Requested Appointment';
        if ($request->ajax()) {
            $query = Appointment::with(['patient', 'doctor', 'visit', 'payment'])
                ->where('status', '=', 'Requested');

            return \DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('patient', fn($row) => $row->patient->name ?? 'N/A')
                ->addColumn('doctor', fn($row) => $row->doctor->name ?? 'N/A')
                ->addColumn('visit_type', fn($row) => $row->visit->visit_description ?? 'N/A')
                ->addColumn('bill_status', function ($row) {
                    if ($row->payment && $row->payment->status) {
                        return '<span class="btn btn-sm btn-success text-white">' . ucfirst($row->payment->status) . '</span>';
                    }
                    return '<span class="btn btn-sm btn-danger text-white">Unpaid</span>';
                })
                ->editColumn('appointment_date', function ($row) {
                    $date = \Carbon\Carbon::parse($row->appointment_date)->format('d-m-Y');
                    $slot = $row->time_slot;
                    return $date . '<br>' . ($slot ? ' (' . $slot . ')' : '');
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.appointment.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <button data-id="' . $row->id . '" class="deleteBtn btn btn-sm btn-danger">Delete</button>';
                })
                ->rawColumns(['actions', 'bill_status', 'appointment_date'])
                ->make(true);
        }

        return view('admin.appointments.requested_appointment',compact('pageTitle'));
    }

    public function calendar(){
        $appointments = Appointment::with('patient', 'doctor')->get();
        return view('admin.appointments.calendar', compact('appointments'));
    }


    
}
