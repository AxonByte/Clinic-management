@extends('layouts.app')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">Create New Service</h4>
          <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-sm">Back to Services</a>
        </div>

        <div class="card-body">

          @if(session('success'))
            <div class="alert alert-success mb-3">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form id="serviceForm" action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Service Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control">
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" class="form-select" required>
                  <option value="">-- Select Department --</option>
                  @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Service Duration (minutes)</label>
                <input type="number" name="service_duration" class="form-control" min="1" value="{{ old('service_duration') }}">
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Price (&#8377;) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="default_price" class="form-control" min="0" value="{{ old('default_price') }}" required>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Service Type <span class="text-danger">*</span></label>
                <select name="service_type" class="form-select" required>
                  <option value="">-- Select Type --</option>
                  <option value="online" {{ old('service_type') == 'online' ? 'selected' : '' }}>Online</option>
                  <option value="inclinic" {{ old('service_type') == 'inclinic' ? 'selected' : '' }}>In-clinic</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
              </div>

              <div class="col-md-12 mb-3 form-check">
                <input type="checkbox" name="has_discount" class="form-check-input" id="hasDiscount" {{ old('has_discount') ? 'checked' : '' }}>
                <label class="form-check-label" for="hasDiscount">Has Discount?</label>
              </div>

              <div id="discountFields" class="row" style="display: none;">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Discount Type</label>
                  <select name="discount_type" id="discount_type" class="form-select">
                    <option value="">-- Select Type --</option>
                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Percent</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Discount Value</label>
                  <input name="discount_value" id="discount_value" type="number" step="0.01" class="form-control" min="0" value="{{ old('discount_value') }}">
                </div>
              </div>

              <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Create Service</button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection



<!-- jQuery and jQuery Validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>

<script>
$(document).ready(function () { 
  // Function to show/hide and enable/disable discount fields
  function toggleDiscountFields() {
    const isDiscountChecked = $('#hasDiscount').is(':checked');
    const discountSection = $('#discountFields');

    if (isDiscountChecked) {
      discountSection.show();
      $('#discount_type').prop('disabled', false);
      $('#discount_value').prop('disabled', false);
    } else {
      discountSection.hide();
      $('#discount_type').prop('disabled', true).val('');
      $('#discount_value').prop('disabled', true).val('');
    }
  }

  // Run once on load and on checkbox toggle
  toggleDiscountFields();
  $('#hasDiscount').on('change', toggleDiscountFields);

  // Initialize jQuery validation
  $('#serviceForm').validate({
    ignore: [], // Validate hidden fields if visible
    rules: {
      name: { required: true },
      department_id: { required: true },
      default_price: { required: true, number: true, min: 0 },
      service_type: { required: true },
      discount_type: {
        required: {
          depends: function () {
            return $('#hasDiscount').is(':checked');
          }
        }
      },
      discount_value: {
        required: {
          depends: function () {
            return $('#hasDiscount').is(':checked');
          }
        },
        number: true,
        min: 0
      }
    },
    messages: {
      name: "Please enter service name",
      department_id: "Please select a department",
      default_price: "Please enter a valid price",
      service_type: "Please select service type",
      discount_type: "Select discount type",
      discount_value: "Enter valid discount value"
    },
    errorElement: 'div',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.mb-3').append(error);
    },
    highlight: function (element) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>

