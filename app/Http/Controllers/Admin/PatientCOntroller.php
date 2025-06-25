<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDetail;
use DataTables;
use App\Models\Hospital;
use App\Models\SubscriptionPackage;
class PatientCOntroller extends Controller
{
    public function index(Request $request){
        $pageTitle = 'Patient List';
        $doctors = User::with('detail')->where('role','doctor')->get();
         if ($request->ajax()) {
        $hospitalId = auth()->user()->hospital_id;


$departments = User::where('role', 'patient')
                   ->where('hospital_id', $hospitalId)
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

                    return '
                        <button 
                        class="btn btn-sm btn-primary edit-btn" 
                        data-id="' . $row->id . '">
                        Edit
                        </button>
                        <button class="btn btn-sm btn-secondary view-btn"
                        data-id="' . $row->id . '">
                        View</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">
                            Delete
                        </button>
                    ';
                })
            ->rawColumns(['action','photo'])
            ->make(true);
      }
        return view('admin.patients.patient_list', compact('doctors','pageTitle'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'dob' => 'nullable|date',
            'n_id' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'height' => 'nullable',
            'weight' => 'nullable',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'emergency_contact_person' => 'nullable|string|max:255',
            'doctor_id' => 'nullable|exists:users,id',
            'address' => 'nullable|string',
        ]);

        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }
    $hospitalId = auth()->user()->hospital_id;
   $hospital = Hospital::find($hospitalId);
   $subscriptionPackage = $hospital->subscription_package;
   $package = SubscriptionPackage::where('package_name', $subscriptionPackage)->first();
   
   if (!$package) {
    return redirect('admin/doctor/')->with('error', 'Subscription package not found!');
}
   
   $doctorLimit = $package->patient_limit;
   $currentDoctorCount = User::where('role', 'admin')
                          ->where('hospital_id', $hospitalId)
                          ->count();
		if ($currentDoctorCount >= $doctorLimit) {
    return redirect('admin/doctor/')->with('error', 'Doctor limit reached for this subscription package.');
}
	
        // Create user
        $patient = new User();
        $patient->name = $request->name;
        $patient->email = $request->email;
        $patient->password = Hash::make($request->password);
        $patient->phone = $request->phone;
        $patient->photo = $photoPath;
        $patient->doctor_id = $request->doctor_id ?? null;
        $patient->address = $request->input('address');
		 $patient->address = $request->input('address');
		$patient->hospital_id= $hospitalId;
        $patient->role = 'patient';
        $patient->save();

        // Create user detail
        $detail = new UserDetail();
        $detail->user_id = $patient->id;
        $detail->dob = $request->dob ?? null;
        $detail->nationalid = $request->n_id ?? null;
        $detail->gender = $request->gender ?? null;
        $detail->blood_group = $request->blood_group ?? null;
        $detail->height = $request->height ?? null;
        $detail->weight = $request->weight ?? null;
        $detail->allergies = $request->allergies ?? null;
        $detail->medical_history = $request->medical_history ?? null;
        $detail->emergency_contact_person = $request->emergency_contact_person ?? null;
        $detail->emergency_contact = $request->emergency_contact ?? null;
        $detail->sms = $request->has('sms');
        $detail->save();

        return redirect()->back()->with('success', 'Patient registered successfully.');
    }

    public function edit(Request $request, $id)
    {
        $pageTitle = 'Edit Patient';
        $doctors = User::with('detail')->where('role','doctor')->get();
        $patient = User::with('detail')->findOrFail($id);
        return view('admin.patients.edit', compact('patient','doctors','pageTitle'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'dob' => 'nullable|date',
            'n_id' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'height' => 'nullable',
            'weight' => 'nullable',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'emergency_contact_person' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:users,id',
            'address' => 'nullable|string',
        ]);

        $patient = User::findOrFail($id);

        // Handle photo replacement
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($patient->photo && Storage::disk('public')->exists($patient->photo)) {
                Storage::disk('public')->delete($patient->photo);
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('photos', 'public');
            $patient->photo = $photoPath;
        }

        // Update User
        $patient->name = $request->name;
        $patient->email = $request->email;
    
        $patient->phone = $request->phone;
        $patient->doctor_id = $request->doctor_id;
        $patient->address = $request->address;
        $patient->save();

        // Update or Create UserDetail
        $detail = $patient->detail()->first();
        if (!$detail) {
            $detail = new UserDetail();
            $detail->user_id = $patient->id;
        }

        $detail->dob = $request->dob;
        $detail->nationalid = $request->n_id;
        $detail->gender = $request->gender;
        $detail->blood_group = $request->blood_group;
        $detail->height = $request->height;
        $detail->weight = $request->weight;
        $detail->allergies = $request->allergies;
        $detail->medical_history = $request->medical_history;
        $detail->emergency_contact_person = $request->emergency_contact_person;
        $detail->emergency_contact = $request->emergency_contact;
        $detail->sms = $request->has('sms');
        $detail->save();

        return redirect()->route('admin.patient.index')->with('success', 'Patient updated successfully.');
    }

     public function destroy($id)
        {
            try {
                $patient = User::findOrFail($id);
                if ($patient->photo) {
                    $photoPath = public_path('storage/' . str_replace('public/', '', $patient->photo));
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }
                }
            
                $patient->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

    public function show(Request $request, $id)
    {
        $pageTitle = 'Patient Details';
        $patient = User::with('detail')->findOrFail($id);
        $patient_doctor = User::where('id', $patient->doctor_id)->pluck('name');
        return view('admin.patients.show', compact('patient','patient_doctor','pageTitle'));

    }
}
