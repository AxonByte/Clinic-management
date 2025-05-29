

@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
  

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Document List</h4>
               </div>
                <button class="btn btn-primary mb-3" id="createBtn">Add Document</button>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="documents-table" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Title</th>
                            <th>Document</th>
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
<div class="modal fade" id="documentModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="documentForm" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add/Edit Document</h5></div>
            <div class="modal-body">
                <label for="patient_id" class="fw-bold text-secondary">Patient <span style="color:red;">*</span></label>
                <select name="patient_id" class="form-control" id="patient_id">
                    <option value="">Select Patient</option>
                    @foreach ($patients as $item)
                        <option value="{{$item->id}}">{{ $item->name}}</option>
                    @endforeach
                </select>
                <label for="title" class="fw-bold text-secondary">Title <span style="color:red;">*</span></label>
                <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>
               <label for="file" class="fw-bold text-secondary">File <span style="color:red;">*</span></label>
                <input type="file" name="file" class="form-control" id="file">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
 <script>
$(function() {
    let table = $('#documents-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.patient.documents.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'date' },
            { data: 'patient' },
            { data: 'title' },
            { data: 'document' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#createBtn').click(function() {
        $('#documentForm')[0].reset();
        $('#id').val('');
        $('#documentModal').modal('show');
    });

    $('#documents-table').on('click', '.editBtn', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/patient/documents') }}/" + id + "/edit", function(data) {
            $('#id').val(data.id);
            $('#patient_id').val(data.patient_id);
            $('input[name="title"]').val(data.title);
            if (data.file_path) {
                let filePreview = `
                    <div class="mt-2">
                        <a href="/storage/${data.file_path}" target="_blank" class="text-primary">View Current File</a>
                    </div>
                `;
                $('#file').after(filePreview);
            }

            $('#documentModal').modal('show');
        });
    });


    $('#documentForm').submit(function(e) {
        e.preventDefault();

        let form = $(this)[0];
        let formData = new FormData(form);

        $.ajax({
            url: "{{ route('admin.patient.documents.store') }}",
            method: 'POST',
            data: formData,
            contentType: false, // Required for file upload
            processData: false, // Required for file upload
            success: function(response) {
                $('#documentModal').modal('hide');
                $('#documents-table').DataTable().ajax.reload();
                showMessage('success', response.success || 'Saved successfully.');
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let message = 'Something went wrong.';
                if (errors) {
                    message = Object.values(errors).map(msgArr => msgArr[0]).join(' ');
                }
                showMessage('danger', message);
            }
        });
    });



    $('#documents-table').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/patient/documents') }}/" + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#documents-table').DataTable().ajax.reload();
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

