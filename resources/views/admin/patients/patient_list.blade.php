@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Patient</h4>
               </div>
               <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
               + Add New Patient
            </button>
            </div>

<!-- Modal start -->

<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:800px">
    <div class="modal-content">
    
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="registerPatientLabel">Register Patient</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <h5 class="border-bottom pb-2 mb-3">
            <i class="bi bi-person-circle text-primary"></i> PERSONAL DETAILS
          </h5>
        <form class="needs-validation" id="addPatientForm" action="{{route('admin.patient.store')}}" method="POST" enctype="multipart/form-data">
           @csrf
            <div class="row mb-3">
            <div class="col-md-6">
              <label for="name" class="form-label fw-bold text-secondary">NAME <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label fw-bold text-secondary">EMAIL <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="password" class="form-label fw-bold text-secondary">PASSWORD <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password" name="password" placeholder="*****" required>
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label fw-bold text-secondary">CONTACT NUMBER <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="phone" name="phone">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="dob" class="form-label fw-bold text-secondary">DATE OF BIRTH</label>
              <input type="date" class="form-control" id="dob" name="dob">
            </div>
            <div class="col-md-6">
              <label for="n_id" class="form-label fw-bold text-secondary">NATIONAL ID</label>
              <input type="text" class="form-control" id="n_id" name="n_id">
            </div>
          </div>
          
         <h5 class="border-bottom border-danger pb-2 mb-4 mt-3">
            <i class="bi bi-heart-pulse text-danger"></i> MEDICAL PROFILE
          </h5>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-secondary">GENDER</label>
              <select name="gender" class="form-control">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
             <div class="col-md-6">
              <label class="form-label fw-bold text-secondary">Blood Group</label>
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
              <label class="form-label fw-bold text-secondary">HEIGHT (cm)</label>
              <input type="number" name="height" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-secondary">WEIGHT (kg)</label>
              <input type="number" name="weight" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
              <div class="col-md-6">
              <label for="allergies" class="form-label fw-bold text-secondary">KNOWN ALLERGIES</label>
              <textarea name="allergies" class="form-control" placeholder="List any known allergies or sensitivities" id="allergies"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-secondary">MEDICAL HISTORY</label>
              <textarea name="medical_history" class="form-control" placeholder="Brief medical history or ongoing conditions" id="medical_history"></textarea>
             
            </div>
        </div>
        <div class="row mb-3">
              <div class="col-md-6">
              <label for="allergies" class="form-label fw-bold text-secondary">DOCTOR</label>
               <select name="docter_id" class="form-control">
                <option value="">Select Doctor</option>
                @foreach ($doctors as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
             
            </div>
        </div>

         <h5 class="border-bottom border-danger pb-2 mb-4 mt-4">
            <i class="bi bi-contact text-danger"></i> CONTACT INFORMATION
          </h5>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="address" class="form-label fw-bold text-secondary">RESIDENTIAL ADDRESS <span style="color:red;">*</span> </label>
                    <textarea class="form-control" name="address" id="address"></textarea>
                </div>
            </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="emergency_contact_person" class="form-label fw-bold text-secondary">EMERGENCY CONTACT NAME </label>
              <input type="text" class="form-control" id="emergency_contact_person" name="emergency_contact_person" placeholder="Emergency contact person" required>
             </div>
            <div class="col-md-6">
              <label for="emergency_contact" class="form-label fw-bold text-secondary">EMERGENCY CONTACT NUMBER</label>
              <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" placeholder="Emergenct contact number">
             </div>
          </div>

         <h5 class="border-bottom border-danger pb-2 mb-4 mt-5">
            <i class="bi bi-camera text-danger"></i> PROFILE IMAGE
          </h5>

        <div class="modal-body text-start">
        <div class="mb-3">
          <img id="preview" src="default-image.png" alt="Profile Preview" class="border" width="150" height="150" style="object-fit: cover;">
        </div>

        <div class="mb-3">
          <label for="photo" class="btn btn-outline-primary">Select image</label>
          <input type="file" class="d-none" id="photo" accept="image/*" name="photo" onchange="previewImage(event)">
        </div>

        <div class="form-check text-start mt-4">
          <input class="form-check-input" type="checkbox" id="sendSms" name="sms">
          <label class="form-check-label fw-bold" for="sendSms" >Send SMS</label>
        </div>
      </div>
       
      </div>
      
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="addPatientForm">Submit</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-- Modal End -->
            <div class="card-body">
               <div class="table-responsive">
                  <table id="Patients" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            {{-- <th>Due Balance</th> --}}
                            <th>Options</th>
                        </tr>
                     </thead>
                     <tbody>

                     </tbody>
                     
                  </table>
               </div>
            </div>
            
         </div>
      </div>
   </div>
 </div>
 
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $('#Patients').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: "{{ route('admin.patient.index') }}",
        columns: [ 
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });


});

</script>

<script>
$(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');

    if (confirm("Are you sure you want to delete this patient?")) {
        $.ajax({
            url: "{{ url('admin/patient/delete') }}/" + id,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#doctors').DataTable().ajax.reload();
                showToast('Patient deleted successfully!', 'success');
                 window.location.reload();
            },
            error: function (xhr) {
                showToast('Failed to delete patient.', 'danger');
            }
        });
    }
});

function showToast(message, type) {
    const toastEl = document.getElementById('messageToast');
    const toastBody = document.getElementById('toastBody');
    toastBody.textContent = message;
    toastEl.className = 'toast align-items-center text-white bg-' + type + ' border-0';
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}
$(document).on('click', '.view-btn', function () {
    $('#modalName').text($(this).data('name'));
    $('#modalEmail').text($(this).data('email'));
    $('#modalPhone').text($(this).data('phone'));
    $('#modalAddress').text($(this).data('address'));
    $('#modalDepartment').text($(this).data('department_id'));
    $('#modalDescription').text($(this).data('description'));
    $('#modalPhoto').attr('src', $(this).data('photo'));
    $('#modalSignature').attr('src', $(this).data('signature'));
});

</script>
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
<script>
  // Bootstrap 5 custom validation
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();
</script>

<script>
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-btn')) {
        const id = e.target.closest('.edit-btn').getAttribute('data-id');
        window.location.href = `/admin/patient/edit/${id}`; 
    }
});
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-btn')) {
        const id = e.target.closest('.view-btn').getAttribute('data-id');
        window.location.href = `/admin/patient/show/${id}`; 
    }
});
</script>

 @endsection