<?php

namespace App\Http\Controllers\Admin\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
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
        $statuses = ['All', 'Pending Confirmation', 'Confirmed', 'Treated', 'Cancelled', 'Requested'];
        return view('admin.appointments.index', compact('statuses'));
    }

    public function getData($status)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($status !== 'All') {
            $query->where('status', $status);
        }

        return \DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('patient', fn($row) => $row->patient->name ?? 'N/A')
            ->addColumn('doctor', fn($row) => $row->doctor->name ?? 'N/A')
            ->editColumn('appointment_date', fn($row) => \Carbon\Carbon::parse($row->appointment_date)->format('d-m-Y'))
            ->addColumn('actions', function ($row) {
                return '<a href="' . route('admin.appointment.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create(){
        $doctors = User::where('role','doctor')->get();
        $patients = User::where('role','patient')->get();
        return view('admin.appointments.create', compact('doctors','patients'));
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
        $interval = 60;

        $slots = [];
        while ($start->lt($end)) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($interval);
        }

        return response()->json(['slots' => $slots]);
    }
    
}
