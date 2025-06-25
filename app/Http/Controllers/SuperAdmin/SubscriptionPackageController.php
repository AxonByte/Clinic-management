<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Module;

class SubscriptionPackageController extends Controller
{
  

public function index(Request $request)
{
    if ($request->ajax()) {
        $query = SubscriptionPackage::query();

        return DataTables::of($query)
            ->addColumn('module_names', function ($package) {
               
                $modulesMap = [
                    1 => 'Accountant',
                    2 => 'Appointment',
                    3 => 'Lab Tests',
                    4 => 'Bed',
                    5 => 'Department',
                    6 => 'Doctor',
                    7 => 'Donor',
                    8 => 'Financial Activities',
                    9 => 'Pharmacy',
                    10 => 'Laboratorist',
                    11 => 'Medicine',
                    12 => 'Nurse',
                    13 => 'Patient',
                    14 => 'Pharmacist',
                    15 => 'Prescription',
                    16 => 'Receptionist',
                    17 => 'Report',
                    18 => 'Notice',
                    19 => 'Email',
                    20 => 'SMS',
                    21 => 'File',
                    22 => 'Payroll',
                    23 => 'Attendance',
                    24 => 'Leave',
                    25 => 'Chat'
                ];

                $ids = explode(',', $package->module_ids);
                $names = array_map(fn($id) => $modulesMap[(int)$id] ?? 'Unknown', $ids);
                return implode(', ', $names);
            })
            ->addColumn('actions', function ($package) {
        $editUrl = route('superadmin.subscription_packages.edit', $package->id);
        $deleteUrl = route('superadmin.subscription_packages.destroy', $package->id);
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

    return view('superadmin.subscription_packages.index');
}

    public function create()
    {  $modules = Module::all(); 

        return view('superadmin.subscription_packages.create',compact('modules'));
    }

 public function store(Request $request)
{
    $request->validate([
        'package_name' => 'required|string|max:255',
        'duration' => 'required|integer',
        'patient_limit' => 'required|integer',
        'doctor_limit' => 'required|integer',
        'original_price' => 'required|numeric',
        'discounted_price' => 'required|numeric',
        'module_ids' => 'required|array',
    ]);

    $data = $request->except('_token');
    $data['module_ids'] = implode(',', $request->input('module_ids'));
    $data['show_in_frontend'] = $request->has('show_in_frontend') ? 1 : 0;
    $data['is_recommended'] = $request->has('is_recommended') ? 1 : 0;

    SubscriptionPackage::create($data);

    return redirect()->route('superadmin.subscription_packages.index')
        ->with('success', 'Subscription Package created successfully.');
}

    public function edit(SubscriptionPackage $subscriptionPackage)
    {   $modules = Module::all(); 
        return view('superadmin.subscription_packages.edit', compact('subscriptionPackage','modules'));
    }

 public function update(Request $request, SubscriptionPackage $subscriptionPackage)
{
    $request->validate([
        'package_name' => 'required|string|max:255',
        'duration' => 'required|integer',
        'patient_limit' => 'required|integer',
        'doctor_limit' => 'required|integer',
        'original_price' => 'required|numeric',
        'discounted_price' => 'required|numeric',
        'module_ids' => 'required|array',
    ]);

    $data = $request->except(['_token', '_method']);
    $data['module_ids'] = implode(',', $request->input('module_ids'));
    $data['show_in_frontend'] = $request->has('show_in_frontend') ? 1 : 0;
    $data['is_recommended'] = $request->has('is_recommended') ? 1 : 0;

    $subscriptionPackage->update($data);

    return redirect()->route('superadmin.subscription_packages.index')
        ->with('success', 'Subscription Package updated successfully.');
}

    public function destroy(SubscriptionPackage $subscriptionPackage)
    {
        $subscriptionPackage->delete();

        return redirect()->route('superadmin.subscription_packages.index')->with('success', 'Package deleted successfully.');
    }
}
