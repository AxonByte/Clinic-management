<?php

namespace App\Http\Controllers\Admin\BedManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\BedCategory;
use App\Models\Bed;

class BedController extends Controller
{
    public function index(Request $request)
      {
        $pageTitle = 'Bed Category';
        $categories = BedCategory::all();
        if ($request->ajax()) {
            $data = Bed::with('category')->select('beds.*')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category->name ?? 'N/A';
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
        
        return view('admin.bedmanagement.beds.index', compact('pageTitle','categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bed_number' => 'required',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required'
       ]);
       Bed::updateOrCreate(
            ['id' => $request->id],
            [
                'description' => $request->description,
                'bed_number' => $request->bed_number,
                'category_id' => $request->category_id
            ]
        );

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $categories = BedCategory::all();
        $data = Bed::find($id);

        return response()->json($data);
    }

    public function destroy($id)
    {
        Bed::find($id)->delete();
        return response()->json(['success' => true]);
    }
}
