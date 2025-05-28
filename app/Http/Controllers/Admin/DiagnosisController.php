<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diagnosis;

class DiagnosisController extends Controller
{
 

    public function index(Request $request)
    {

        return view('admin.diagnosis.index', ['diagnoses' => Diagnosis::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'disease_name' => 'required|string|max:255',
        ]);

        Diagnosis::create([
            'disease_name' => $request->disease_name,
            'icd_code' => $request->icd_code,
            'description' => $request->description,
            'has_outbreak_potential' => $request->has_outbreak_potential ? 1 : 0,
            'max_weekly_patients' => $request->max_weekly_patients,
        ]);

        return redirect()->back()->with('success', 'Diagnosis added successfully');
    }

    public function update(Request $request, Diagnosis $diagnosis)
    {
        $diagnosis->update($request->all());
        return redirect()->back()->with('success', 'Diagnosis updated successfully');
    }

    public function edit($id)
    {
        $diagnosis = Diagnosis::findOrFail($id);
        return response()->json([
            'id' => $diagnosis->id,
            'disease_name' => $diagnosis->disease_name,
            'icd_code' => $diagnosis->icd_code,
            'description' => $diagnosis->description,
            'is_outbreak' => $diagnosis->has_outbreak_potential,
            'max_weekly_patients' => $diagnosis->max_weekly_patients,
        ]);
    }

    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();
        return redirect()->back()->with('success', 'Diagnosis deleted');
    }
}
