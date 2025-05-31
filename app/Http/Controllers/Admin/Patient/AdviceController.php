<?php

namespace App\Http\Controllers\Admin\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advice;
use DataTables;

class AdviceController extends Controller
{
    public function index(Request $request)
    {
         $pageTitle = 'Advice List';
        if ($request->ajax()) {
            $data = Advice::latest()->get();
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

        return view('admin.patients.advices.list', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        Advice::updateOrCreate(
            ['id' => $request->id],
            $request->only('name', 'description')
        );

        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Advice updated successfully.' : 'Advice added successfully.'
        ]);
    }

    public function edit($id)
    {
        return response()->json(Advice::find($id));
    }

    public function destroy($id)
    {
        Advice::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Advice deleted successfully.'
        ]);
    }
}
