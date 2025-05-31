<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DoctorHoliday;
use DataTables;

class DoctorHolidayController extends Controller
{
    
    public function index(Request $request)
    {
        $pageTitle = 'Doctor Holiday';
        $doctors = User::where('role','doctor')->get();
        if ($request->ajax()) {
            $data = DoctorHoliday::with('doctor');
            return DataTables::of($data)
               ->addColumn('doctor_name', function($user) {
                    return $user->doctor_id ? $user->doctor->name : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <button 
                        class="btn btn-sm btn-primary editBtn" 
                        data-id="' . $row->id . '" 
                        data-doctor_id="' . htmlspecialchars($row->doctor_id, ENT_QUOTES) . '" 
                        data-date="' . htmlspecialchars($row->date, ENT_QUOTES) . '"
                    >
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.doctors.holidays.holiday', compact('doctors','pageTitle'));
    }
     public function store(Request $request)
    {
        
        try{
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required',
        ]);
            //  dd($request->all());
        DoctorHoliday::create($request->all());

        return back()->with('success', 'Holiday added successfully.');
        }catch(Exception $ex){
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required',
        ]);

        try {
            $holiday = DoctorHoliday::findOrFail($id);
            $holiday->doctor_id = $request->doctor_id;
            $holiday->date = $request->date;
            $holiday->save();

            return redirect()->back()->with('success', 'Holiday updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Holiday not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function destroy($id)
    {
        try {
            $visit = DoctorHoliday::findOrFail($id);
            $visit->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
