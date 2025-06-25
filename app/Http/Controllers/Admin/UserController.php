<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Models\Role;
class UserController extends Controller
{
    public function index(Request $request){
        $pageTitle = 'List of Users';
        $department = Department::get();
        if ($request->ajax()) {
      $departments = User::with(['department', 'role'])
    ->where('hospital_id', auth()->user()->hospital_id)
    ->where('is_super_admin', 0);
           return DataTables::of($departments)
    ->addIndexColumn()
    
    // Department Name
    ->addColumn('department_name', function ($user) {
        return optional($user->department)->name ?? 'N/A';
    })

    // Role Name (safely)
    ->addColumn('role_name', function ($user) {
        return optional($user->role)->name ?? 'N/A';
    })

    // Photo
    ->addColumn('photo', function ($user) {
        return $user->photo
            ? '<img src="' . asset('storage/' . $user->photo) . '" width="50px" height="50px" />'
            : '-';
    })

    // Action Buttons
    ->addColumn('action', function ($row) {
        $photoUrl = $row->photo ? asset('storage/' . str_replace('public/', '', $row->photo)) : '';
        $signatureUrl = $row->sign ? asset('storage/' . str_replace('public/', '', $row->sign)) : '';
        $department = optional($row->department)->name ?? 'N/A';

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
            <button class="btn btn-sm btn-secondary view-btn" 
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
                View
            </button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                Delete
            </button>
        ';
    })

    ->rawColumns(['action', 'photo'])
    ->make(true);

      }
	  $hospitalId = auth()->user()->hospital_id;
    $roles = Role::where('hospital_id', $hospitalId)->get(); 
        
        return view('admin.users.index',compact('roles','department','pageTitle'));
    }

    public function create(){
         $pageTitle = 'Add New User';
		  $hospitalId = auth()->user()->hospital_id;
      $roles = Role::where('hospital_id', $hospitalId)->get(); 
         $department = Department::get();
        return view('admin.doctors.create',compact('roles','department','pageTitle'));
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
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'department_id' => $request->department,
            'description' => $request->description,
            'photo' => $photoPath,
            'hospital_id' => auth()->user()->hospital_id  ,
            'role' => 'doctor',
			'role_id' => $request->role,
			'is_super_admin' => 0,
        ]);
         return redirect('admin/users/')->with('success', 'User added successfully!');
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
			print_r($doctor); die();
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
    

}
