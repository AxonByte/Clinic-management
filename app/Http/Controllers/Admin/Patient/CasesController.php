<?php

namespace App\Http\Controllers\Admin\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CaseRecord;
use App\Models\User;
use App\Models\Symptom;
use App\Models\Treatment;
use App\Models\Advice;
use App\Models\Diagnosis;
use Yajra\DataTables\Facades\DataTables;

class CasesController extends Controller
{
    public function index(Request $request)
    {
         $pageTitle = 'Cases List';
        if ($request->ajax()) {
            $data = CaseRecord::with('patient')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('patient', fn($row) => $row->patient->name ?? 'N/A')
                ->addColumn('action', fn($row) => '
                    <a href="' . route('admin.patient.cases.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                    <button data-id="'.$row->id.'" class="deleteBtn btn btn-sm btn-danger">Delete</button>
                ')
                ->rawColumns(['action'])
                ->make(true);
        }

        
        return view('admin.patients.case-records.list', compact('pageTitle'));
    }

    public function create(){
         $pageTitle = 'Add Cases';
        $patients = User::where('role','patient')->get();
        $symptoms = Symptom::get();
        $treatments = Treatment::get();
        $advices = Advice::get();
        $diagnoses = Diagnosis::get();
        return view('admin.patients.case-records.create', compact('patients','symptoms','diagnoses','advices','treatments','pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'patient_id' => 'required|exists:users,id',
        ]);

        // dd($request->all());

        CaseRecord::create($request->all());

        return redirect()->route('admin.patient.cases.index')->with('success', 'Cases added successfully.');
    }

    public function edit($id)
    {
         $pageTitle = 'Edit Cases';
        $case = CaseRecord::findOrFail($id);
        return view('admin.patients.case-records.edit', [
            'case' => $case,
            'patients' => User::where('role','patient')->get(),
            'symptoms' => Symptom::all(),
            'diagnoses' => Diagnosis::all(),
            'advices' => Advice::all(),
            'treatments' => Treatment::all(),
        ], compact('pageTitle'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'patient_id' => 'required|exists:users,id',
        ]);

        $case = CaseRecord::findOrFail($id);
        $case->update($request->all());

        return redirect()->route('admin.patient.cases.index')->with('success', 'Cases updated successfully.');
    }

    public function destroy($id)
    {
        CaseRecord::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Cases deleted successfully.'
        ]);
    }

}
