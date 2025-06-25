@extends('layouts.app')
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Doctor</h4>
                            </div>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('admin.doctor.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name"
                                            class="form-label required text-secondary fw-bold">NAME</label>
                                        <input type="text" id="name" class="form-control" name="name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email"
                                            class="form-label required text-secondary fw-bold">EMAIL</label>
                                        <input type="email" id="email" class="form-control" name="email" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password"
                                            class="form-label required text-secondary fw-bold">PASSWORD</label>
                                        <input type="password" id="password" class="form-control" name="password" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="address"
                                            class="form-label required text-secondary fw-bold">ADDRESS</label>
                                        <input type="text" id="address" class="form-control" name="address" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="phone"
                                            class="form-label required text-secondary fw-bold">PHONE</label>
                                        <input type="number" id="phone" class="form-control" name="phone" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="department" class="form-label fw-bold text-secondary">DEPARTMENT</label>
                                        <select class="form-select" id="department" name="department" required>
                                            <option value="">Select Department</option>
                                            @foreach ($department as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                    <label for="description" class="form-label fw-bold text-secondary">DOCTOR
                                        DESCRIPTION</label>
                                    <textarea id="doctorDesc" class="form-control" name="description"></textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="signature" class="form-label fw-bold text-secondary">SIGNATURE <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="signature" name="signature" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="photo" class="form-label fw-bold text-secondary">IMAGE</label>
                                        <input type="file" class="form-control" id="photo" name="photo">
                                    </div>
                                </div>

                        </div>
                        <div class="row mb-5">
                          <div class="text-end" style="margin-right: 10px;">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="submit" class="btn btn-danger">cancel</button>
                        </div>
                        </div>
                    </div>


                    </form>
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
@endsection
<script>
$(document).ready(function () {
  $('#department').on('change', function () {
    const deptId = $(this).val();
    const $services = $('#services');
    $services.html('<option value="">Loading...</option>');

    if (deptId) {
      $.ajax({
        url: "{{ route('get.services.by.department') }}",
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