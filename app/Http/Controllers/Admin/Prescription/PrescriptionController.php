<?php

namespace App\Http\Controllers\Admin\Prescription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;
use App\Models\Prescription;
use App\Models\User;
use App\Models\Medicine;

class PrescriptionController extends Controller
{

    public function index(Request $request)
    {
        $pageTitle = 'Prescription List';
        if ($request->ajax()) {
        $data = Prescription::with(['doctor', 'patient', 'medicines'])->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('doctor', fn($row) => $row->doctor->name ?? '-')
            ->addColumn('patient', fn($row) => $row->patient->name ?? '-')
            ->addColumn('medicines', function ($row) {
                return $row->medicines->pluck('name')->implode(', ');
            })
            ->addColumn('date', fn($row) => \Carbon\Carbon::parse($row->date)->format('d M Y'))
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('admin.prescription.show', $row->id) . '" class="btn btn-sm btn-secondary">View</a>
                    <a href="' . route('admin.prescription.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                    <button data-id="'.$row->id.'" class="deleteBtn btn btn-sm btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

        return view('admin.prescriptions.index', compact('pageTitle'));
    }

    public function create()
    {
       $pageTitle = 'Add Medicine';
       $doctors = User::where('role','doctor')->get();
       $patients = User::where('role','patient')->get();
       $all_medicines = Medicine::get();
       return view('admin.prescriptions.create', compact('doctors','patients','all_medicines','pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'medicines' => 'required|array',
            'medicine_details' => 'required|array',
        ]);
        $prescription = Prescription::create([
            'date' => $request->date,
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'note' => $request->notes,
            'history' => $request->history,
            'advice' => $request->advice,
        ]);

        foreach ($request->medicine_ids as $medicine_id) {
            $details = $request->medicine_details[$medicine_id];

            $prescription->medicines()->create([
                'medicine_id' => $medicine_id,
                'dosage' => $details['dosage'] ?? null,
                'frequency' => $details['frequency'] ?? null,
                'days' => $details['days'] ?? null,
                'instructions' => $details['instructions'] ?? null,
            ]);
        }

        return redirect()->route('admin.prescription.index')->with('success', 'Prescription added successfully.');
    }

    public function edit($id)
    {
        $pageTitle ='Update Prescription';
        $prescription = Prescription::with('prescriptionMedicines.medicine')->findOrFail($id);
        $doctors = User::where('role','doctor')->get();
        $patients = User::where('role','patient')->get();
        $all_medicines = Medicine::all();

        return view('admin.prescriptions.edit', compact('prescription', 'doctors', 'patients', 'all_medicines','pageTitle'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $prescription = Prescription::findOrFail($id);
            $prescription->update([
                'date' => $request->date,
                'doctor_id' => $request->doctor_id,
                'patient_id' => $request->patient_id,
                'notes' => $request->notes,
                'history' => $request->history,
                'advice' => $request->advice,
            ]);

            // Delete old medicines
            $prescription->prescriptionMedicines()->delete();

            // Add updated medicines
            if ($request->medicine_ids) {
                foreach ($request->medicine_ids as $medicineId) {
                    $details = $request->medicine_details[$medicineId];
                    $prescription->prescriptionMedicines()->create([
                        'medicine_id' => $medicineId,
                        'dosage' => $details['dosage'] ?? null,
                        'frequency' => $details['frequency'] ?? null,
                        'days' => $details['days'] ?? null,
                        'instructions' => $details['instructions'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.prescription.index')->with('success', 'Prescription updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error("Prescription Update Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.')->withInput();
        }
    }

    public function show($id)
    {
        $prescription = Prescription::with([
            'patient',
            'doctor',
            'medicines'
        ])->findOrFail($id);

        return view('admin.prescriptions.show', compact('prescription'));
    }



    public function destroy($id)
    {
        Prescription::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Medicine deleted successfully.'
        ]);
    }


}
