<?php

namespace App\Http\Controllers\Admin\BedManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\BedCategory;

class BedCategoryController extends Controller
{
    public function index(Request $request)
      {
        $pageTitle = 'Bed Category';
        if ($request->ajax()) {
            $data = BedCategory::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.bedmanagement.bed-category.index', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
       ]);
       BedCategory::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'description' => $request->description
            ]
        );

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $data = BedCategory::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        BedCategory::find($id)->delete();
        return response()->json(['success' => true]);
    }
}
