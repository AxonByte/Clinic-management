<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    // Display all categories (ajax DataTables support)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::withCount('subcategories')->select('categories.*');

            return DataTables::of($query)
                ->addColumn('subcategory_count', function ($category) {
                    return $category->subcategories_count;
                })
                ->addColumn('actions', function ($category) {
                    $editUrl = route('superadmin.categories.edit', $category->id);
                    $deleteUrl = route('superadmin.categories.destroy', $category->id);
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

        return view('superadmin.categories.index');
    }

    // Show form to create category
    public function create()
    {
        return view('superadmin.categories.create');
    }

    // Store new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->only('name'));

        return redirect()->route('superadmin.categories.index')->with('success', 'Category created successfully.');
    }

    // Show form to edit category
    public function edit(Category $category)
    {
        return view('superadmin.categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->only('name'));

        return redirect()->route('superadmin.categories.index')->with('success', 'Category updated successfully.');
    }

    // Delete category
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('superadmin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
