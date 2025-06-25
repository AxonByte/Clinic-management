<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\Subcategory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Department;
class ServiceController extends Controller
{
 public function index(Request $request)
{
    if ($request->ajax()) {
        $query = Service::with('department');

        return DataTables::of($query)
            ->addColumn('department', fn($s) => $s->department->name ?? '-')
            ->addColumn('type', fn($s) => ucfirst($s->service_type))
            ->addColumn('price', fn($s) => '₹' . number_format($s->default_price, 2))
            ->addColumn('status', fn($s) => ucfirst($s->status))
            ->addColumn('actions', function ($s) {
                $editUrl = route('admin.services.edit', $s->id);
                $deleteUrl = route('admin.services.destroy', $s->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <a href='{$editUrl}' class='btn btn-sm btn-primary'>Edit</a>
                    <form action='{$deleteUrl}' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure?\")'>
                        {$csrf}{$method}
                        <button class='btn btn-sm btn-danger'>Delete</button>
                    </form>
                ";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    return view('admin.services.index');
}

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
		$departments = Department::where('hospital_id', auth()->user()->hospital_id)
                         ->whereNull('deleted_at')
                         ->get();
        return view('admin.services.create', compact('departments','categories', 'subcategories'));
    }

  public function store(Request $request)
{
    $request->validate([
        'name'              => 'required|string|max:255',
        'image'             => 'nullable|image|max:2048',
        'department_id'     => 'required|exists:departments,id',
        'service_duration'  => 'nullable|integer|min:1',
        'default_price'     => 'required|numeric|min:0',
        
        
    ]);

    $data = $request->except(['image', 'has_discount']);
    $data['has_discount'] = $request->has('has_discount');

   
    $data['hospital_id'] = auth()->user()->hospital_id;


    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('services', 'public');
    }
	
    Service::create($data);

    return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
}



    public function edit(Service $service)
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.services.edit', compact('service', 'categories', 'subcategories'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'image'             => 'nullable|image|max:2048',
            'category_id'       => 'required|exists:categories,id',
            'subcategory_id'    => 'nullable|exists:subcategories,id',
            'service_duration'  => 'nullable|integer|min:1',
            'default_price'     => 'required|numeric|min:0',
            'service_type'      => 'required|in:online,inclinic',
            'description'       => 'nullable|string',
            'has_discount'      => 'nullable|boolean',
            'discount_type'     => 'nullable|in:fixed,percent',
            'discount_value'    => 'nullable|numeric|min:0',
            'status'            => 'required|in:active,inactive',
        ]);

        $data = $request->except(['image', 'has_discount']);
        $data['has_discount'] = $request->has('has_discount');

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
