<?php

namespace App\Http\Controllers\Admin\BedManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientService;
use DataTables;

class PatientServicesController extends Controller
{
    public function index(Request $request)
      {
        $pageTitle = 'Patient Services';
        if ($request->ajax()) {
            $data = PatientService::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return $row->is_active ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.bedmanagement.patient-services.index', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'alphacode' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        PatientService::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'code' => $request->code,
                'alphacode' => $request->alphacode,
                'price' => $request->price,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]
        );

        return response()->json(['success' => true]);
    }


    public function edit($id)
    {
        $data = PatientService::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        PatientService::find($id)->delete();
        return response()->json(['success' => true]);
    }
    

}
