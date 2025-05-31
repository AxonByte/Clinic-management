<?php

namespace App\Http\Controllers\Admin\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Treatment;
use DataTables;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
         $pageTitle = 'Treatment List';
        if ($request->ajax()) {
            $data = Treatment::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <button data-id="'.$row->id.'" class="editBtn btn btn-sm btn-primary">Edit</button>
                        <button data-id="'.$row->id.'" class="deleteBtn btn btn-sm btn-danger">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.patients.treatment.list', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:treatments,code,' . $request->id,
            'description' => 'nullable'
        ]);

        Treatment::updateOrCreate(
            ['id' => $request->id],
            $request->only('name', 'code', 'description')
        );
        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Treatment updated successfully.' : 'Treatment added successfully.'
        ]);
    }

    public function edit($id)
    {
        return response()->json(Treatment::find($id));
    }

    public function destroy($id)
    {
        Treatment::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Treatment deleted successfully.'
        ]);
    }

}
