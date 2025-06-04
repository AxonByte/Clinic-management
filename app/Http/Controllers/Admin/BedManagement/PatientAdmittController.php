<?php

namespace App\Http\Controllers\Admin\BedManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientAdmitted;
use App\Models\User;
use App\Models\MedicineRecord;
use App\Models\BedCategory;
use App\Models\ProgressNote;
use App\Models\TakenService;
use App\Models\PatientService;
use App\Models\DischargeReport;
use App\Models\Bed;
use App\Models\Medicine;
use DataTables;

class PatientAdmittController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PatientAdmitted::with(['patient', 'doctor', 'acceptingDoctor', 'bedCategory','bed'])->latest();
            return DataTables::of($data)
              ->addIndexColumn()
              
               ->addColumn('bed_number', function ($row) {
                    return $row->bed->bed_number ?? 'N/A';
                })
              ->addColumn('patient', function ($row) {
                    return $row->patient->name ?? 'N/A';
                })
              ->addColumn('doctor', function ($row) {
                    return $row->doctor->name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = route('admin.bedmanagement.admition.edit', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>';
                    $viewLink = '<a href="'.$viewUrl.'" class="btn btn-sm btn-outline-success">View</a>';
                    
                    return $deleteBtn . ' ' . $viewLink;
                })

                ->make(true);
        }

        return view('admin.bedmanagement.admitted-patient.index');
    }

    public function create()
    {
        $doctors = User::where('role','doctor')->get();
        $patients = User::where('role','patient')->get();
        $category = BedCategory::get();
        $beds = Bed::get();
        return view('admin.bedmanagement.admitted-patient.create', compact('doctors','patients','category','beds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'category' => 'required',
            'admission_time' => 'required|date',
        ]);
    //   dd($request->all());
        PatientAdmitted::create($request->all());
        return redirect()->route('admin.bedmanagement.admition.list')->with('success', 'Patient Admitted successfully.');
    }

    public function edit($id)
    {
        $doctors = User::where('role','doctor')->get();
        $patients = User::where('role','patient')->get();
        $category = BedCategory::get();
        $beds = Bed::get();
        $admission = PatientAdmitted::findOrFail($id);
        return view('admin.bedmanagement.admitted-patient.edit',compact('admission','doctors','patients','category','beds'));
    }

    public function updatePaiient(Request $request, $id)
    {
       $request->validate([
        'admitted_at' => 'required|date',
        'bed_category_id' => 'required|exists:bed_categories,id',
        'bed_id' => 'required|exists:beds,id',
        'patient_id' => 'required|exists:users,id',
        'doctor_id' => 'nullable|exists:users,id',
        'category' => 'required|in:Urgent,Planned',
        'reactions' => 'nullable|string',
        'transferred_from' => 'nullable|string',
        'diagnosis_hospitalization' => 'nullable|string',
        'diagnosis' => 'nullable|string',
        'other_illnesses' => 'nullable|string',
        'history' => 'nullable|string',
        'blood_group' => 'nullable|string|max:3',
        'accepting_doctor_id' => 'nullable|exists:users,id',
    ]);

    $admission = PatientAdmitted::findOrFail($id);

    $admission->admission_time = $request->admitted_at;
    $admission->bed_category_id = $request->bed_category_id;
    $admission->bed_id = $request->bed_id;
    $admission->patient_id = $request->patient_id;
    $admission->doctor_id = $request->doctor_id;

    // Category checkboxes
    $admission->category = $request->category;

    // Additional fields
    $admission->reactions = $request->reactions;
    $admission->transferred_from = $request->transferred_from;
    $admission->diagnosis_at_hospitalization = $request->diagnosis_hospitalization;

    // New fields
    $admission->diagnosis = $request->diagnosis;
    $admission->other_illnesses = $request->other_illnesses;
    $admission->history = $request->history;
    $admission->blood_group = $request->blood_group;
    $admission->accepting_doctor_id = $request->accepting_doctor_id;

    $admission->save();
     return redirect()->back()->with('success', 'Admission updated successfully.');
   }

    public function destroy($id)
    {
        PatientAdmitted::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Admitted patient deleted successfully.'
        ]);
    }

    public function dailyProgress($id)
    {
        $admission = PatientAdmitted::with('patient')->findOrFail($id);
        $progressNotes = $admission->progressNotes()->with('nurse')->latest()->get();
        $nurses = User::where('role','nurse')->get();
        return view('admin.bedmanagement.admitted-patient.dailyprogress', compact('admission', 'progressNotes', 'nurses'));
    }


    public function storeDailyProgress(Request $request, $id)
    {
        $request->validate([
            'nurse_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'time' => 'required|date_format:H:i',
        ]);

        $admission = PatientAdmitted::findOrFail($id);

        $datetime = now()->setDateFrom($request->date)->setTimeFromTimeString($request->time);

        $admission->progressNotes()->create([
            'nurse_id' => $request->nurse_id,
            'description' => $request->description,
            'created_at' => $datetime,
        ]);

        return redirect()->back()->with('success', 'Progress note added.');
    }
    
   public function editdailyNotes($admissionId, $noteId)
    {
        $admission = PatientAdmitted::findOrFail($admissionId);
        $progressNotes = ProgressNote::where('patient_admitted_id', $admissionId)->with('nurse')->get();
        $editNote = ProgressNote::findOrFail($noteId);
        $nurses = User::where('role','nurse')->get();

        return view('admin.bedmanagement.admitted-patient.dailyprogress', compact('admission', 'progressNotes', 'editNote', 'nurses'));
    }

   public function update(Request $request, $admissionId, $noteId)
    {
        $validated = $request->validate([
            'nurse_id' => 'required|exists:users,id',
            'time' => 'required',
            'description' => 'required|string',
        ]);

        $note = ProgressNote::findOrFail($noteId);
        $note->nurse_id = $validated['nurse_id'];
        $note->description = $validated['description'];

        // Keep original date but update time
        $note->created_at = \Carbon\Carbon::parse($note->created_at->format('Y-m-d') . ' ' . $validated['time']);
        $note->save();

        return redirect()->route('admin.bedmanagement.admission.dailyprogress', $admissionId)
            ->with('success', 'Progress note updated successfully.');
    }

    //code for medicine tab section 

    public function medicines($admissionId)
    {
        $admission = PatientAdmitted::with('patient')->findOrFail($admissionId);
        $medicines = MedicineRecord::where('patient_admitted_id', $admissionId)->latest()->get();
        $medicinesList = Medicine::all();
// dd($medicinesList);
        return view('admin.bedmanagement.admitted-patient.medicines', compact('admission', 'medicines','medicinesList'));
    }

    public function medicineRecordStore(Request $request)
    {
        $validated = $request->validate([
            'patient_admitted_id' => 'required|exists:patient_admitteds,id',
            'brand_name' => 'required',
            'sales_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'total' => 'required|numeric',
        ]);

        MedicineRecord::create($validated);

        return back()->with('success', 'Medicine added successfully.');
    }

    public function deleteMedicineRecord($id)
    {
        MedicineRecord::destroy($id);
        return back()->with('success', 'Medicine deleted.');
    }
    //code for service tab section 

    public function services($admissionId)
    {
        $servicesList = PatientService::all();
        $admission = PatientAdmitted::with('patient')->findOrFail($admissionId);
        $services = TakenService::with(['nurse','service'])->where('patient_admitted_id', $admissionId)->latest()->get();
        $nurses = User::where('role','nurse')->get();
// dd($services);
        return view('admin.bedmanagement.admitted-patient.service', compact('admission', 'services','nurses','servicesList'));
    }

    public function serviceStore(Request $request)
    {
        $validated = $request->validate([
            'patient_admitted_id' => 'required|exists:patient_admitteds,id',
            'nurse_id' => 'required',
            'service_id' => 'required',
            'sales_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'total' => 'required|numeric',
        ]);
        TakenService::create($validated);

        return back()->with('success', 'Medicine added successfully.');
    }

    public function deleteService($id)
    {
        TakenService::destroy($id);
        return back()->with('success', 'Medicine deleted.');
    }

    public function discharge($admissionId, $id = null){
         $doctors = User::where('role','doctor')->get();
         $admission = PatientAdmitted::with('patient')->findOrFail($admissionId);
         $checkout = $id ? DischargeReport::findOrFail($id) : null;
        //  dd($checkout);
        return view('admin.bedmanagement.admitted-patient.checkout', compact('checkout','admission','doctors'));
    }

    public function dischargeStore(Request $request, $admitted_id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'patient_admitted_id' => 'required|exists:patient_admitteds,id',
            'discharge_time' => 'required',
            'final_diagnosis' => 'nullable|string',
            'anatomopatologic_diagnosis' => 'nullable|string',
            'checkout_diagnosis' => 'nullable|string',
            'doctor_id' => 'required|exists:users,id',
        ]);
    //   dd($request->all());
        if ($request->id) {
            $checkout = DischargeReport::findOrFail($request->id);
            $checkout->update($validated);
            $msg = 'Checkout updated successfully.';
        } else {
            DischargeReport::create($validated);
            $msg = 'Checkout created successfully.';
        }
        return redirect()->back()->with('success', $msg);
    }



}
