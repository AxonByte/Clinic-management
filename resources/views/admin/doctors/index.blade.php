@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Doctor List</h4>
               </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                +</i> Add New Doctor
            </button>
            </div>

<!-- Modal start -->

<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width:800px">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="addDoctorModalLabel">
          <i class="bi bi-person-circle me-2"></i> Add New Doctor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <form id="addDoctorForm" action="{{route('admin.doctor.store')}}" method="POST" enctype="multipart/form-data">
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
              <label for="address" class="form-label fw-bold text-secondary">ADDRESS <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="address" name="address" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="phone" class="form-label fw-bold text-secondary">PHONE <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="col-md-6">
            <label for="department" class="form-label fw-bold text-secondary">DEPARTMENT<span class="text-danger">*</span></label>
            <select class="form-select" id="department" name="department" required>
                <option value="">Select Department</option>
                @foreach ($department as $item)
                     <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
               
            </select>
            </div>

          </div>
          <div class="col-md-6">
  <label for="services" class="form-label fw-bold text-secondary">SERVICES</label>
  <select class="form-select" id="services" name="services[]" multiple required>
    <option value="">Select Service</option>
  
  </select>
</div>
       
          <div class="mb-3">
            <label for="description" class="form-label fw-bold text-secondary">DOCTOR DESCRIPTION</label>
            <textarea id="doctorDesc" class="form-control" name="description"></textarea>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="signature" class="form-label fw-bold text-secondary">SIGNATURE <span class="text-danger">*</span></label>
              <input type="file" class="form-control" id="signature" name="signature" required>
               <img id="signaturePreview" src="" alt="Signature Preview" style="max-width: 100%; margin-top: 10px; display: none;">
            </div>
            <div class="col-md-6">
              <label for="photo" class="form-label fw-bold text-secondary">IMAGE</label>
              <input type="file" class="form-control" id="photo" name="photo">
               <img id="photoPreview" src="" alt="Photo Preview" style="max-width: 100px; margin-top: 10px; display: none;">
            </div>
          </div>
       
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" form="addDoctorForm">Save Doctor</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-- Modal End -->

<div class="modal fade" id="doctorDetailModal" tabindex="-1" aria-labelledby="doctorDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="doctorDetailLabel">Doctor Info</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">

        <div class="spinner-border text-primary d-none" id="modal-loader" role="status"></div>

        <div class="my-3">
          <img id="doctor-photo" src="" alt="Doctor Photo" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
        </div>
        <div class="card shadow mt-3 mx-3">
          <div class="card-body text-start">
            <div class="row mb-2">
              <div class="col-md-3 fw-bold text-uppercase">NAME</div>
              <div class="col-md-9" id="doctor-name"></div>
            </div>
            <div class="row mb-2">
              <div class="col-md-3 fw-bold text-uppercase">EMAIL</div>
              <div class="col-md-9" id="doctor-email"></div>
            </div>
            <div class="row mb-2">
              <div class="col-md-3 fw-bold text-uppercase">ADDRESS</div>
              <div class="col-md-9" id="doctor-address"></div>
            </div>
            <div class="row mb-2">
              <div class="col-md-3 fw-bold text-uppercase">PHONE</div>
              <div class="col-md-9" id="doctor-phone"></div>
            </div>
            <div class="row mb-2">
              <div class="col-md-3 fw-bold text-uppercase">DEPARTMENT</div>
              <div class="col-md-9" id="doctor-department"></div>
            </div>
            <div class="row mb-2">
              <div class="col-md-3 fw-bold text-uppercase">DESCRIPTION</div>
              <div class="col-md-9" id="doctor-description"></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="doctor" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department Name</th>
                            <th>Profile</th>
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
 <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let departmentEditor;
    ClassicEditor
        .create(document.querySelector('#doctorDesc'))
        .then(editor => {
            departmentEditor = editor;
            editor.ui.view.editable.element.style.height = '300px';
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> --}}
<script>
  $(document).ready(function () {
    $('#doctor').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: "{{ route('admin.doctor.index') }}",
        columns: [ 
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'department_name', name: 'department_name' },
            { data: 'photo', name: 'photo' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

$(document).on('click', '.edit-btn', function () {
    const doctorId = $(this).data('id');
    const name = $(this).data('name');
    const email = $(this).data('email');
    const phone = $(this).data('phone');
    const address = $(this).data('address');
    const departmentId = $(this).data('department_id');
    const description = $(this).data('description');

    let photo = $(this).data('photo');
    let signature = $(this).data('signature');
    // Set the src attribute of preview images
    if(photo) {
        $('#photoPreview').attr('src', photo).show();
    } else {
        $('#photoPreview').hide();
    }

    if(signature) {
        $('#signaturePreview').attr('src', signature).show();
         $('#signature').removeAttr('required'); 
         $('#signature').attr('data-editing', 'true');
    } else {
        $('#signaturePreview').hide();
        $('#signature').attr('required', true);
        $('#signature').attr('data-editing', 'false');
    }
    // You may need to add other data attributes for photo, signature if required

    // Change modal title and button
    $('#addDoctorModalLabel').text('Edit Doctor');
    $('#addDoctorForm button[type="submit"]').text('Update Doctor');

    // Change form action & method
    $('#addDoctorForm').attr('action', `/admin/doctor/${doctorId}`);
    if ($('#addDoctorForm input[name="_method"]').length === 0) {
        $('#addDoctorForm').append('<input type="hidden" name="_method" value="PUT">');
    } else {
        $('#addDoctorForm input[name="_method"]').val('PUT');
    }

    // Fill form fields
    $('#name').val(name);
    $('#email').val(email);
    $('#phone').val(phone);
    $('#address').val(address);
    $('#department').val(departmentId);
   
    if (departmentEditor) {
        departmentEditor.setData(description);
    }

    // Optionally clear or disable password input if you want
    $('#password').val('').prop('required', false);

    // Show modal
    $('#addDoctorModal').modal('show');
});

$('#addDoctorModal').on('hidden.bs.modal', function () {
    $('#addDoctorModalLabel').text('Add New Doctor');
    $('#addDoctorForm button[type="submit"]').text('Save Doctor');
    $('#addDoctorForm').attr('action', "{{ route('admin.doctor.store') }}");
    $('#addDoctorForm input[name="_method"]').remove();
    $('#addDoctorForm')[0].reset();
    if (departmentEditor) {
        departmentEditor.setData('');
    }
    $('#password').prop('required', true);
});

$(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');

    if (confirm("Are you sure you want to delete this doctor?")) {
        $.ajax({
            url: "{{ url('admin/doctor') }}/" + id,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#doctors').DataTable().ajax.reload();
                showToast('Doctor deleted successfully!', 'success');
            },
            error: function (xhr) {
                showToast('Failed to delete doctor.', 'danger');
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

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('doctorDetailModal');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const address = button.getAttribute('data-address');
        const phone = button.getAttribute('data-phone');
        const department = button.getAttribute('data-department_id');
        const description = button.getAttribute('data-description');
        const photo = button.getAttribute('data-photo');

        document.getElementById('doctor-name').textContent = name;
        document.getElementById('doctor-email').textContent = email;
        document.getElementById('doctor-address').textContent = address;
        document.getElementById('doctor-phone').textContent = phone;
        document.getElementById('doctor-department').textContent = department;
        document.getElementById('doctor-description').textContent = description;
        document.getElementById('doctor-photo').src = photo;
    });
});
</script>
<script>
$(document).ready(function () {
  $('#department').on('change', function () {
    const deptId = $(this).val();
    const $services = $('#services');
    $services.html('<option value="">Loading...</option>');

    if (deptId) {
      $.ajax({
        url: "{{ route('admin.doctor.doctor.get.services') }}",
        method: "GET",
        data: { department_id: deptId },
        success: function (response) {
          $services.empty();
          if (response.length) {
            $.each(response, function (i, item) {
              $services.append(`<option value="${item.id}">${item.name}</option>`);
            });
          } else {
            $services.append('<option value="">No services found</option>');
          }
        },
        error: function () {
          $services.html('<option value="">Error loading services</option>');
        }
      });
    } else {
      $services.html('<option value="">Select Department First</option>');
    }
  });
});
</script>
 @endsection