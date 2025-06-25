<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request){
        $pageTitle = 'Department';
        if ($request->ajax()) {
$departments = Department::where('hospital_id', auth()->user()->hospital_id)->get();

        return DataTables::of($departments)
            ->addColumn('action', function ($row) {
                return '
                    <button 
                        class="btn btn-sm btn-primary edit-btn" 
                        data-id="' . $row->id . '" 
                        data-name="' . htmlspecialchars($row->name, ENT_QUOTES) . '" 
                        data-description="' . htmlspecialchars($row->description, ENT_QUOTES) . '"
                    >
                        <i class="bi bi-pencil-square">Edit</i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">
                        <i class="bi bi-trash">Delete</i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
      }
       return view('admin.department.index', compact('pageTitle'));
    }

    public function store(Request $request)
    {  
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {   $hospital_id=auth()->user()->hospital_id; 
            Department::create([
                'name' => $request->name,
                'description' => $request->description,'hospital_id' => $hospital_id,
            ]);

            return redirect()->back()->with('success', 'Department created successfully.');
        } catch (Exception $e) {
            Log::error('Something went wrong: ' . $e->getMessage());
        }
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            $department = Department::findOrFail($id);
            $department->name = $request->name;
            $department->description = $request->description;
            $department->save();

            return redirect()->back()->with('success', 'Department updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Department not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }



}
