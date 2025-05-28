@extends('layouts.app')
@section('content')
<style>
    .modal-xl {
  max-width: 50% !important;
  width: 50% !important;
}
</style>
  <div class="conatiner-fluid content-inner mt-n5 py-0">
        <!-- Header -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div id="messageToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastBody"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        
        <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Patient</h4>
               </div>
               <a class="btn btn-primary"  href="{{ route('admin.patient.index')}}">List of Patient</a>
            </div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container my-4">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white mt-0">
      <h4 class="mb-2">Update Patient</h4>
    </div>

    <div class="card-body">
      <form class="needs-validation" id="updatePatientForm" action="{{ route('admin.patient.update', $patient->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person-circle text-primary"></i> PERSONAL DETAILS</h5>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label fw-bold text-secondary">NAME <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $patient->name ?? '' }}" required>
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label fw-bold text-secondary">EMAIL <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $patient->email ?? '' }}" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="phone" class="form-label fw-bold text-secondary">CONTACT NUMBER</label>
            <input type="number" class="form-control" id="phone" name="phone" value="{{ $patient->phone ?? ''}}">
          </div>
           <div class="col-md-6">
            <label for="dob" class="form-label fw-bold text-secondary">DATE OF BIRTH</label>
            <input type="date" class="form-control" id="dob" name="dob" value="{{ $patient->detail->dob ?? '' }}">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="n_id" class="form-label fw-bold text-secondary">NATIONAL ID</label>
            <input type="text" class="form-control" id="n_id" name="n_id" value="{{ $patient->detail->nationalid ?? '' }}">
          </div>
        </div>

        <h5 class="border-bottom border-danger pb-2 mb-4 mt-3"><i class="bi bi-heart-pulse text-danger"></i> MEDICAL PROFILE</h5>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-bold text-secondary">GENDER</label>
            <select name="gender" class="form-control">
              <option value="">Select Gender</option>
             <option value="male" {{ optional($patient->detail)->gender == 'male' ? 'selected' : '' }}>Male</option>
             <option value="female" {{ optional($patient->detail)->gender == 'female' ? 'selected' : '' }}>Female</option>

            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold text-secondary">Blood Group</label>
            <select name="blood_group" class="form-control">
                @foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $group)
                    <option value="{{ $group }}" 
                        {{ optional($patient->detail)->blood_group == $group ? 'selected' : '' }}>
                        {{ $group }}
                    </option>
                @endforeach
            </select>

          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-bold text-secondary">HEIGHT (cm)</label>
            <input type="number" name="height" class="form-control" value="{{ $patient->detail->height ?? '' }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">WEIGHT (kg)</label>
            <input type="number" name="weight" class="form-control" value="{{ $patient->detail->weight ?? '' }}">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="allergies" class="form-label fw-bold text-secondary">KNOWN ALLERGIES</label>
            <textarea name="allergies" class="form-control" id="allergies">{{ $patient->detail->allergies ?? ''}}</textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold text-secondary">MEDICAL HISTORY</label>
            <textarea name="medical_history" class="form-control" id="medical_history">{{ $patient->detail->medical_history ?? ''}}</textarea>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-bold text-secondary">DOCTOR</label>
            <select name="docter_id" class="form-control">
              <option value="">Select Doctor</option>
              @foreach ($doctors as $doc)
                <option value="{{ $doc->id }}" {{ $patient->docter_id == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <h5 class="border-bottom border-danger pb-2 mb-4 mt-4"><i class="bi bi-contact text-danger"></i> CONTACT INFORMATION</h5>

        <div class="row mb-3">
          <div class="col-md-12">
            <label for="address" class="form-label fw-bold text-secondary">RESIDENTIAL ADDRESS</label>
            <textarea class="form-control" name="address" id="address">{{ $patient->address ?? ''}}</textarea>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="emergency_contact_person" class="form-label fw-bold text-secondary">EMERGENCY CONTACT NAME</label>
            <input type="text" class="form-control" id="emergency_contact_person" name="emergency_contact_person" value="{{ $patient->detail->emergency_contact_person ?? '' }}">
          </div>
          <div class="col-md-6">
            <label for="emergency_contact" class="form-label fw-bold text-secondary">EMERGENCY CONTACT NUMBER</label>
            <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="{{ $patient->detail->emergency_contact ?? '' }}">
          </div>
        </div>

        <h5 class="border-bottom border-danger pb-2 mb-4 mt-5"><i class="bi bi-camera text-danger"></i> PROFILE IMAGE</h5>

        <div class="mb-3">
          <img 
            id="preview" 
            src="{{ $patient->photo ? asset('storage/' . $patient->photo) : asset('default-image.png') }}" 
            alt="Profile Preview" 
            class="border mb-2" 
            width="150" 
            height="150" 
            style="object-fit: cover;">

          <div>
            <label for="photo" class="btn btn-outline-primary">Select image</label>
            <input type="file" class="d-none" id="photo" accept="image/*" name="photo" onchange="previewImage(event)">
          </div>
        </div>

       

        <div class="mt-4 text-end">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
</div>

<script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
      const preview = document.getElementById('preview');
      preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
@endsection
