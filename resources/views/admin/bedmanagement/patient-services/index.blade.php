
@extends('layouts.app')
@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Services List</h4>
               </div>
                <button class="btn btn-primary mb-3" id="createBtn">Add Services</button>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="serviceList" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Service Name</th>
                            <th>Code</th>
                            <th>Alpha Code</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
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

 <!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="serviceForm">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add/Edit Patient Service</h5>
            </div>
            <div class="modal-body">
                <label for="name" class="text-secondary">Service Name</label>
                <input type="text" name="name" class="form-control mb-2" placeholder="Enter service name" required>

                <label for="code" class="text-secondary">Service Code</label>
                <input type="text" name="code" class="form-control mb-2" placeholder="Enter service code">

                <label for="alphacode" class="text-secondary">Alpha Code</label>
                <input type="text" name="alphacode" class="form-control mb-2" placeholder="Enter alpha code">

                <label for="price" class="text-secondary">Price</label>
                <input type="number" step="0.01" name="price" class="form-control mb-2" placeholder="Enter price">

                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1">
                    <label for="is_active" class="form-check-label">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
 <script>
$(function() {
    let table = $('#serviceList').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.bedmanagement.services.list") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'code' },
            { data: 'alphacode' },
            { data: 'price' },
            { data: 'is_active' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#createBtn').click(function() {
        $('#serviceForm')[0].reset();
        $('#id').val('');
        $('#serviceModal').modal('show');
    });
$('#serviceList').on('click', '.editBtn', function() {
    let id = $(this).data('id');
    $.get("{{ url('admin/bedmanagement/patient-services') }}/" + id + "/edit", function(data) {
        
        $('#id').val(data.id);
        $('input[name="name"]').val(data.name);
        $('input[name="code"]').val(data.code);
        $('input[name="alphacode"]').val(data.alphacode);
        $('input[name="price"]').val(data.price);
        $('#is_active').prop('checked', data.is_active == 1);
        $('#serviceModal').modal('show');
    });
});


 $('#serviceForm').submit(function(e) {
    e.preventDefault();
    let formData = $(this).serializeArray();

    // Ensure unchecked checkbox is sent as 0
    if (!$('#is_active').is(':checked')) {
        formData.push({ name: 'is_active', value: 0 });
    }

    $.post("{{ route('admin.bedmanagement.services.store') }}", formData)
        .done(function(response) {
            $('#serviceModal').modal('hide');
            $('#serviceList').DataTable().ajax.reload();
            showMessage('success', response.message || 'Saved successfully.');
        })
        .fail(function(xhr) {
            let errors = xhr.responseJSON.errors;
            let message = 'Something went wrong.';
            if (errors) {
                message = Object.values(errors).map(msgArr => msgArr[0]).join(' ');
            }
            showMessage('danger', message);
        });
    });



    $('#serviceList').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure want to delete this service?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/bedmanagement/patient-services') }}/" + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#serviceList').DataTable().ajax.reload();
                showMessage('success', response.message || 'Deleted successfully.');
            },
            error: function() {
                showMessage('danger', 'Error deleting data.');
            }
        });
    }
});

});
</script>
 @endsection

