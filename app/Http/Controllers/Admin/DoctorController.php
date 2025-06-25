<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Models\Service;
use App\Models\Hospital;

use App\Models\SubscriptionPackage;
class DoctorController extends Controller
{
    public function index(Request $request){
        $pageTitle = 'List of Doctor';
        $department = Department::get();
        if ($request->ajax()) {
      $departments = User::with('department')
                   ->where('role', 'doctor')
                   ->where('hospital_id', auth()->user()->hospital_id)
                   ->get();

            return DataTables::of($departments)
               ->addIndexColumn() 
                ->addColumn('department_name', function($user) {
                    return $user->department_id ? $user->department->name : 'N/A';
                })
                ->addColumn('photo', function($user) {
                    if ($user->photo) {
                        return '<img src="' . asset('storage/' . $user->photo) . '" width="50px" height="50px" />';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) {
                    $photoPath = str_replace('public/', '', $row->photo);
                    $photoUrl = $row->photo ? asset('storage/' . $photoPath) : '';

                    $signaturePath = str_replace('public/', '', $row->sign);
                    $signatureUrl = $row->sign ? asset('storage/' . $signaturePath) : '';

                    $department = $row->department_id ? $row->department->name : 'N/A';

                    return '
                        <button 
                        class="btn btn-sm btn-primary edit-btn" 
                        data-id="' . $row->id . '" 
                        data-name="' . htmlspecialchars($row->name, ENT_QUOTES) . '" 
                        data-email="' . htmlspecialchars($row->email, ENT_QUOTES) . '" 
                        data-phone="' . htmlspecialchars($row->phone, ENT_QUOTES) . '" 
                        data-address="' . htmlspecialchars($row->address, ENT_QUOTES) . '" 
                        data-department_id="' . htmlspecialchars($row->department_id, ENT_QUOTES) . '" 
                        data-description="' . htmlspecialchars($row->description, ENT_QUOTES) . '" 
                        data-photo="' . $photoUrl . '" 
                        data-signature="' . $signatureUrl . '"
                        >
                        Edit
                        </button>
                        <button class="btn btn-sm btn-secondary view-btn" data-id="'.$row->id.'" 
                        data-id="' . $row->id . '" 
                        data-name="' . htmlspecialchars($row->name, ENT_QUOTES) . '" 
                        data-email="' . htmlspecialchars($row->email, ENT_QUOTES) . '" 
                        data-phone="' . htmlspecialchars($row->phone, ENT_QUOTES) . '" 
                        data-address="' . htmlspecialchars($row->address, ENT_QUOTES) . '" 
                        data-department_id="' . $department . '" 
                        data-description="' . htmlspecialchars(strip_tags($row->description), ENT_QUOTES) . '" 
                        data-photo="' . $photoUrl . '" 
                        data-signature="' . $signatureUrl . '" 
                        data-bs-toggle="modal"
                        data-bs-target="#doctorDetailModal">
                        view</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">
                           Delete
                        </button>
                    ';
                })
            ->rawColumns(['action','photo'])
            ->make(true);
      }

        return view('admin.doctors.index',compact('department','pageTitle'));
    }

    public function create(){
         $pageTitle = 'Add New Doctor';
         $department = Department::get();
        return view('admin.doctors.create',compact('department','pageTitle'));
    }

    public function store(Request $request){ 
       $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'phone' => 'required|digits_between:10,15',
            'description' => 'nullable|string',
            'department' => 'required|string',
            'signature' => 'required|mimes:jpg,jpeg,png|max:2048',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);
        $photoPath = null;
        $signaturePath = null;
        if ($request->hasFile('photo')) {
           $photoPath = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('signatures', 'public');
        }
        try {
			if (auth()->check()) {
   $hospitalId = auth()->user()->hospital_id;
   $hospital = Hospital::find($hospitalId);
   $subscriptionPackage = $hospital->subscription_package;
   $package = SubscriptionPackage::where('package_name', $subscriptionPackage)->first();
   
   if (!$package) {
    return redirect('admin/doctor/')->with('error', 'Subscription package not found!');
}
   
   $doctorLimit = $package->doctor_limit;
   $currentDoctorCount = User::where('role', 'doctor')
                          ->where('hospital_id', $hospitalId)
                          ->count();
		if ($currentDoctorCount >= $doctorLimit) {
    return redirect('admin/doctor/')->with('error', 'Doctor limit reached for this subscription package.');
}
		$servicesString = $request->has('services') 
    ? implode(',', $request->services) 
    : null;		  
			dd([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'phone' => $request->phone,
    'address' => $request->address,
    'department_id' => $request->department,
    'description' => $request->description,
    'photo' => $photoPath,
    'hospital_id' => $hospitalId,
    'sign' => $signaturePath,
    'service_ids' => $servicesString,
    'role' => 'doctor',
    'is_super_admin' => 0,
]); die();			  
	     User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'department_id' => $request->department,
            'description' => $request->description,
            'photo' => $photoPath, 'hospital_id' =>$hospitalId,
            'sign' => $signaturePath,'service_ids' => $servicesString, 
            'role' => 'doctor','is_super_admin'=>0]); 
} else {
   

}
   
    
         return redirect('admin/doctor/')->with('success', 'Doctor added successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }

     }

     public function update(Request $request, $id)
     {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'phone' => 'required|digits_between:10,15',
            'description' => 'nullable|string',
            'department' => 'required|string',
            'signature' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);
        try {
            
            $doctor = User::findOrFail($id);
            $doctor->name = $request->name;
            $doctor->email = $request->email;
            $doctor->phone = $request->phone;
            $doctor->address = $request->address;
            $doctor->department_id = $request->department;
			$doctor->hospital_id = $doctor->hospital_id;
            if ($request->filled('password')) {
            $doctor->password = Hash::make($request->password);
            }
            if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                $oldPhotoPath = public_path('storage/' . str_replace('public/', '', $doctor->photo));
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            $photoPath = $request->file('photo')->store('photos', 'public');;
            $doctor->photo = $photoPath;
            }

            if ($request->hasFile('signature')) {
                if ($doctor->sign) {
                    $oldSignaturePath = public_path('storage/' . str_replace('public/', '', $doctor->sign));
                    if (file_exists($oldSignaturePath)) {
                        unlink($oldSignaturePath);
                    }
                }

                $signaturePath = $request->file('signature')->store('signatures', 'public');;
                $doctor->sign = $signaturePath;
            }

            $doctor->description = $request->description;
            $doctor->save();

            return redirect()->back()->with('success', 'Doctor updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Doctor not found.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $doctor = User::findOrFail($id);
            if ($doctor->photo) {
                $photoPath = public_path('storage/' . str_replace('public/', '', $doctor->photo));
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            if ($doctor->sign) {
                $signPath = public_path('storage/' . str_replace('public/', '', $doctor->sign));
                if (file_exists($signPath)) {
                    unlink($signPath);
                }
            }
            $doctor->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
public function getServicesByDepartment(Request $request)
{
    $request->validate([
        'department_id' => 'required|integer|exists:departments,id'
    ]);

    $services = Service::where('department_id', $request->department_id)
                       ->select('id', 'name')
                       ->orderBy('name')
                       ->get();

    return response()->json($services);
}
}
