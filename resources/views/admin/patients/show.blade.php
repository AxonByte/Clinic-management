@extends('layouts.app')
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Patient Info</h4>
                    </div>
                    <a class="btn btn-primary"  href="{{ route('admin.patient.index')}}">List of Patient</a>
                </div>
        

    <div class="row mt-3">
        {{-- Left Column: Basic Information --}}
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-2"><i class="fas fa-user"></i> Basic Information</h5>
                </div>
                <div class="card-body row">
                    <div class="col-md-4 text-center">
                       <img 
                        id="preview" 
                        src="{{ $patient->photo ? asset('storage/' . $patient->photo) : asset('default-image.png') }}" 
                        alt="Profile Preview" 
                        class="border mb-2" 
                        width="150" 
                        height="150" 
                        style="object-fit: cover;border-radius:50%;margin-left:35px;">
                    </div>
                    <div class="col-md-8 text-start" style="margin-top:10px;margin-left:10px!important;">
                        <p><strong class="text-secondary">Patient ID:</strong> {{ $patient->id }}</p>
                        <p><strong class="text-secondary">Full Name:</strong> {{ $patient->name }}</p>
                        <p><strong class="text-secondary">Email Address:</strong> {{ $patient->email }}</p>
                        <p><strong class="text-secondary">Contact Number:</strong> {{ $patient->phone }}</p>
                        <p><strong class="text-secondary">National ID:</strong> {{ $patient->detail->nationalid }}</p>
                        <p><strong class="text-secondary">Residential Address:</strong> {{ $patient->address }}</p>
                        <p><strong class="text-secondary">National ID:</strong> {{ $patient->detail->gender }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-2"><i class="fas fa-heartbeat"></i> Medical Information</h5>
                </div>
                <div class="card-body">
                    <p><strong class="text-secondary">Blood Group:</strong> {{ optional($patient->detail)->blood_group ?? 'N/A' }}</p>
                    <p><strong class="text-secondary">Birth Date:</strong> {{ optional($patient->detail)->birth_date ?? 'N/A' }}</p>
                    <p><strong class="text-secondary">Doctor:</strong> {{ $patient_doctor ?? '' }}</p>
                    <p><strong class="text-secondary">Height:</strong> {{ optional($patient->detail)->height ?? 'N/A' }}</p>
                    <p><strong class="text-secondary">Weight:</strong> {{ optional($patient->detail)->weight ?? 'N/A' }}</p>
                    <p><strong class="text-secondary">Known Allergies:</strong> {{ optional($patient->detail)->allergies ?? 'N/A' }}</p>
                    <p><strong class="text-secondary">Medical History:</strong> {{ optional($patient->detail)->medical_history ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-2"><i class="fas fa-ambulance"></i> Emergency Contact</h5>
                </div>
                <div class="card-body">
                    <p><strong class="text-secondary">Emergency Contact Person:</strong> {{ optional($patient->detail)->emergency_contact_person ?? 'N/A' }}</p>
                    <p><strong class="text-secondary">Emergency Contact Number:</strong> {{ optional($patient->detail)->emergency_contact ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
