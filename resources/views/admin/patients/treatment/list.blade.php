

@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Treatment List</h4>
               </div>
                <button class="btn btn-primary mb-3" id="createBtn">Add Treatment</button>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="treatmentTable" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
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
<div class="modal fade" id="treatmentModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="treatmentForm">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add/Edit Treatment</h5></div>
            <div class="modal-body">
                <label for="name" class="fw-bold text-secondary">Name</label>
                <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                <label for="code" class="fw-bold text-secondary">ICD-10-PCS Code</label>
                <input type="text" name="code" class="form-control mb-2" placeholder="Code" required>
                <label for="description" class="fw-bold text-secondary">Description</label>
                <textarea name="description" class="form-control" placeholder="Description"></textarea>
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
    let table = $('#treatmentTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.patient.treatments.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'code' },
            { data: 'description' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#createBtn').click(function() {
        $('#treatmentForm')[0].reset();
        $('#id').val('');
        $('#treatmentModal').modal('show');
    });

    $('#treatmentTable').on('click', '.editBtn', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/patient/treatments') }}/" + id + "/edit", function(data) {
            $('#id').val(data.id);
            $('input[name="name"]').val(data.name);
            $('input[name="code"]').val(data.code);
            $('textarea[name="description"]').val(data.description);
            $('#treatmentModal').modal('show');
        });
    });

    // $('#treatmentForm').submit(function(e) {
    //     e.preventDefault();
    //     $.post("{{ route('admin.patient.treatments.store') }}", $(this).serialize(), function() {
    //         $('#treatmentModal').modal('hide');
    //         table.ajax.reload();
    //     });
    // });

    // $('#treatmentTable').on('click', '.deleteBtn', function() {
    //     if (confirm("Are you sure?")) {
    //         let id = $(this).data('id');
    //         $.ajax({
    //             url: "{{ url('admin/patient/treatments') }}/" + id,
    //             type: 'DELETE',
    //             data: { _token: '{{ csrf_token() }}' },
    //             success: function() {
    //                 table.ajax.reload();
    //             }
    //         });
    //     }
    // });


    
  $('#treatmentForm').submit(function(e) {
    e.preventDefault();
    $.post("{{ route('admin.patient.treatments.store') }}", $(this).serialize())
        .done(function(response) {
            $('#treatmentModal').modal('hide');
            $('#treatmentTable').DataTable().ajax.reload();
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


    $('#treatmentTable').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/patient/treatments') }}/" + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#treatmentTable').DataTable().ajax.reload();
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

