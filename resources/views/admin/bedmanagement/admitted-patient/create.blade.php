@extends('layouts.app')
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Admit Patient</h4>
                            </div>
                            <a class="btn btn-primary" href="{{ route('admin.bedmanagement.admition.list')}}">Admitted patient</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.bedmanagement.admition.store') }}" method="POST">
                                    @csrf

                                <h5 class="border-bottom border-primary pb-2 mb-4 mt-5">
                                    <i class="bi bi-contact text-danger"></i> ADMIT DETAILS
                                </h5>
                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">ADMIT TIME <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="admission_time" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1 d-block">CATEGORY <span class="text-danger">*</span></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="urgent" value="Urgent" required>
                                            <label class="form-check-label" for="urgent">Urgent</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="planned" value="Planned">
                                            <label class="form-check-label" for="planned">Planned</label>
                                        </div>
                                    </div>


                                </div>
                                 <h5 class="border-bottom border-success pb-2 mb-4 mt-5">
                                    <i class="bi bi-contact text-danger"></i> BED INFORMATION
                                </h5>
                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">BED CATEGORY <span class="text-danger">*</span></label>
                                        <select name="bed_category_id " class="form-control" required>
                                            <option value="">Select Category</option>
                                            @foreach($category as $categories)
                                                <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">BED ID <span class="text-danger">*</span></label>
                                        <select name="bed_id" class="form-control" required>
                                            <option value="">Select Bed id</option>
                                            @foreach($beds as $bed)
                                                <option value="{{ $bed->id }}">{{ $bed->bed_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   
                                </div>

                                <h5 class="border-bottom border-info pb-2 mb-4 mt-5">
                                    <i class="bi bi-contact text-danger"></i> PATIENT & DOCTOR DETAILS
                                </h5>
                               <div class="row mb-3">
                                     <div class="col-md-6">
                                        <label class="text-secondary mb-1">PATIENT<span class="text-danger">*</span></label>
                                        <select name="patient_id" class="form-control" required>
                                            <option value="">Select Patient</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                     <div class="col-md-6">
                                        <label class="form-label text-secondary">Blood Group</label>
                                        <select name="blood_group" class="form-control">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                    
                                </div>

                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">DOCTOR<span class="text-danger">*</span></label>
                                        <select name="doctor_id" class="form-control" required>
                                            <option value="">Select Doctor</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   <div class="col-md-6">
                                        <label class="text-secondary mb-1">ACCEPTING DOCTOR<span class="text-danger">*</span></label>
                                        <select name="accepting_doctor_id " class="form-control" required>
                                            <option value="">Select Doctor</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                 <h5 class="border-bottom border-danger pb-2 mb-4 mt-5">
                                    <i class="bi bi-contact text-danger"></i> MEDICAL INFORMATION
                                </h5>

                               <div class="row mb-3">
                                    <div class="col-md-12">
                                    <label class="text-secondary mb-1">DIAGNOSIS</label>
                                    <textarea name="diagnosis" id="diagnosis" class="form-control form-control-lg shadow-sm"></textarea>
                                    </div>
                                </div>
                               <div class="row mb-3">
                                    <div class="col-md-12">
                                    <label class="text-secondary mb-1">DIAGNOSIS A HOSPITALIZATION</label>
                                    <textarea name="diagnosis_at_hospitalization" id="diagnosis_at_hospitalization" class="form-control form-control-lg shadow-sm"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                    <label class="text-secondary mb-1">OTHER ILLNESSES</label>
                                    <textarea name="other_illnesses" id="other_illnesses" class="form-control form-control-lg shadow-sm"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                    <label class="text-secondary mb-1">HISTORY</label>
                                    <textarea name="history" id="history" class="form-control form-control-lg shadow-sm"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                    <label class="text-secondary mb-1">REACTIONS</label>
                                    <textarea name="reactions" id="reactions" class="form-control form-control-lg shadow-sm"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                    <label class="text-secondary mb-1">TRANSFERREF FROM</label>
                                    <textarea name="transferred_from" id="transferred_from" class="form-control form-control-lg shadow-sm"></textarea>
                                    </div>
                                </div>

                             <div class="row">
                                    <div class="text-end" style="margin-right: 10px;">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                            </div>
                            </form>
                        </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    
@endsection
