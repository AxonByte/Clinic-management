<?php

namespace App\Http\Controllers\SuperAdmin;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Module;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class HospitalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Hospital::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subscription_package', function ($row) {
                    return $row->subscription_package ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('superadmin.hospitals.edit', $row->id);
                    $deleteUrl = route('superadmin.hospitals.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');
                    return "
                        <a href='{$editUrl}' class='btn btn-sm btn-primary'>Edit</a>
                        <form action='{$deleteUrl}' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure?\")'>
                            {$csrf}
                            {$method}
                            <button type='submit' class='btn btn-sm btn-danger'>Delete</button>
                        </form>
                    ";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('superadmin.hospitals.index');
    }

    public function create()
{
    $modules = Module::all();
    $packages = SubscriptionPackage::all();  // no with('modules')
    
    return view('superadmin.hospitals.create', compact('packages', 'modules'));
}



public function store(Request $request)
{ 
    $request->validate([
        'hospital_name' => 'required|string|max:255',
        'subscription_package' => 'required|exists:subscription_packages,id',
        'admin_name' => 'required|string|max:255',
      
    ]);

    // Find the selected subscription package
    $package = SubscriptionPackage::findOrFail($request->subscription_package);


    $startDate = Carbon::now();


    $endDate = (clone $startDate)->addMonths($package->duration)->subDay();

   
    $hospital = Hospital::create([
        'name' => $request->hospital_name,
        'subscription_package' =>$request->subscription_package,
        'subscription_state' => 'active',
        'subscription_start_date' => $startDate,
        'subscription_end_date' => $endDate,
        'status' => 'active',
    ]);

  
    User::create([
        'name' => $request->admin_name,
        'email' => $request->admin_email,
        'password' => Hash::make($request->admin_password),
        'phone' => $request->phone,
        'address' => $request->address,
        'photo' => $request->photo,
        'sign' => $request->sign,
        'description' => $request->description,
        'role' => 'admin',
        'role_id' => 1,
        'department_id' => null,
        'doctor_id' => null,
        'is_super_admin' => false,
        'email_verified_at' => now(),
        'hospital_id' => $hospital->id,
    ]);

    return redirect()->route('superadmin.hospitals.index')->with('success', 'Hospital and admin created successfully.');
}


    public function edit(Hospital $hospital)
    {
        $packages = SubscriptionPackage::all();
        return view('superadmin.hospitals.edit', compact('hospital', 'packages'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $request->validate([
            'hospital_name' => 'required|string|max:255',
            'subscription_package' => 'nullable|string|max:100',
        ]);

        $hospital->update([
            'name' => $request->hospital_name,
            'subscription_package' => $request->subscription_package,
        ]);

        return redirect()->route('superadmin.hospitals.index')->with('success', 'Hospital updated successfully.');
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return redirect()->route('superadmin.hospitals.index')->with('success', 'Hospital deleted successfully.');
    }
}
