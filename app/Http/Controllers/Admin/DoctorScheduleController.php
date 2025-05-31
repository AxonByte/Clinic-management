<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

use DataTables;

class DoctorScheduleController extends Controller
{
    
    public function index(Request $request)
    {
        $pageTitle = 'Doctor Schedule';
        $doctors = User::where('role','doctor')->get();
        if ($request->ajax()) {
            $data = DoctorSchedule::with('doctor');
            return DataTables::of($data)
               ->addColumn('doctor_name', function($user) {
                    return $user->doctor_id ? $user->doctor->name : 'N/A';
                })
                ->addColumn('status', fn($row) => "<span class='badge bg-info'>{$row->status}</span>")
                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.doctors.schedule.schedule', compact('doctors','pageTitle'));
    }

    public function store(Request $request)
    {
        
        try{
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'weekday' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'appointment_duration' => 'required',
        ]);
            //  dd($request->all());
        DoctorSchedule::create($request->all());

        return back()->with('success', 'Schedule added successfully.');
        }catch(Exception $ex){
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $visit = DoctorSchedule::findOrFail($id);
            $visit->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
