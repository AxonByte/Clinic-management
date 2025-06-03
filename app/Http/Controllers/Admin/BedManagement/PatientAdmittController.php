<?php

namespace App\Http\Controllers\Admin\BedManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientAdmitted;
use App\Models\User;
use App\Models\BedCategory;
use App\Models\Bed;
use DataTables;

class PatientAdmittController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PatientAdmitted::with(['patient', 'doctor', 'acceptingDoctor', 'bedCategory','bed'])->latest();
            return DataTables::of($data)
              ->addIndexColumn()
              
               ->addColumn('bed_number', function ($row) {
                    return $row->bed->bed_number ?? 'N/A';
                })
              ->addColumn('patient', function ($row) {
                    return $row->patient->name ?? 'N/A';
                })
              ->addColumn('doctor', function ($row) {
                    return $row->doctor->name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-outline-danger deleteBtn" data-id="'.$row->id.'">Delete</button>
                    <button class="btn btn-sm btn-outline-success viewbtn" data-id="'.$row->id.'">View</button>';
                })
                ->make(true);
        }

        return view('admin.bedmanagement.admitted-patient.index');
    }

    public function create()
    {
        $doctors = User::where('role','doctor')->get();
        $patients = User::where('role','patient')->get();
        $category = BedCategory::get();
        $beds = Bed::get();
        return view('admin.bedmanagement.admitted-patient.create', compact('doctors','patients','category','beds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'category' => 'required',
            'admission_time' => 'required|date',
        ]);
    //   dd($request->all());
        PatientAdmitted::create($request->all());
        return redirect()->route('admin.bedmanagement.admition.list')->with('success', 'Patient Admitted successfully.');
    }

    public function edit($id)
    {
        $data = PatientAdmitted::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        PatientAdmitted::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Admitted patient deleted successfully.'
        ]);
    }
}
