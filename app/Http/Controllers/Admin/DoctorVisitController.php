<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorVisit;
use App\Models\User;
use DataTables;

class DoctorVisitController extends Controller
{
    

    public function index(Request $request)
    {
        $doctors = User::where('role','doctor')->get();
        if ($request->ajax()) {
            $data = DoctorVisit::with('doctor');
            return DataTables::of($data)
               ->addColumn('doctor_name', function($user) {
                    return $user->doctor_id ? $user->doctor->name : 'N/A';
                })
               ->addColumn('status', function ($row) {
                    $badgeClass = $row->status === 'Active' ? 'success' : 'danger';
                    return "<span class='badge bg-{$badgeClass}'>".ucfirst($row->status)."</span>";
                })

                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-primary editBtn" 
                    data-id="'.$row->id.'"
                    data-doctor_id="' . htmlspecialchars($row->doctor_id, ENT_QUOTES) . '" 
                    data-visit_description="' . htmlspecialchars($row->visit_description, ENT_QUOTES) . '" 
                    data-status="' . htmlspecialchars($row->status, ENT_QUOTES) . '" 
                    data-visit_charges="' . htmlspecialchars($row->visit_charges, ENT_QUOTES) . '" 
                    >Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.doctors.doctor_visits.doctor_visits_list', compact('doctors'));
    }

    public function store(Request $request)
    {
       
        try {
            
            $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'visit_description' => 'required|string|max:255',
                'visit_charges' => 'required',
                'status' => 'required|in:Active,Inactive',
            ]);
            $visit = DoctorVisit::create($request->only([
                'doctor_id', 'visit_description', 'visit_charges', 'status'
            ]));

            return back()->with('success', 'Doctor added successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'visit_description' => 'required|string|max:255',
                'visit_charges' => 'required',
                'status' => 'required|in:Active,Inactive',
            ]);

            $visit = DoctorVisit::findOrFail($id);
            $visit->update($request->only([
                'doctor_id', 'visit_description', 'visit_charges', 'status'
            ]));

             return back()->with('success', 'Doctor Visit updated successfully!');
        } catch (\Exception $e) {
             return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $visit = DoctorVisit::findOrFail($id);
            $visit->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }



}
