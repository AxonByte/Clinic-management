<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Subcategory::with('category')->select('subcategories.*');

            return DataTables::of($query)
                ->addColumn('category_name', function ($subcategory) {
                    return $subcategory->category->name ?? '-';
                })
                ->addColumn('actions', function ($subcategory) {
                    $editUrl = route('superadmin.subcategories.edit', $subcategory->id);
                    $deleteUrl = route('superadmin.subcategories.destroy', $subcategory->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" action="' . $deleteUrl . '" style="display:inline-block;" onsubmit="return confirm(\'Are you sure?\')">
                            ' . $csrf . '
                            ' . $method . '
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('superadmin.subcategories.index');
    }

    public function create()
    {
        $categories = Category::all();
        return view('superadmin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        Subcategory::create($request->only('name', 'category_id'));

        return redirect()->route('superadmin.subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('superadmin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subcategory->update($request->only('name', 'category_id'));

        return redirect()->route('superadmin.subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('superadmin.subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
