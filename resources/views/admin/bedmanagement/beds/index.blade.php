
@extends('layouts.app')
@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Bed List</h4>
               </div>
                <button class="btn btn-primary mb-3" id="createBtn">Add Bed</button>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="bedList" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Bed Number</th>
                            <th>Category</th>
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
<div class="modal fade" id="bedModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="categoryForm">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add/Edit Bed</h5></div>
            <div class="modal-body">
                <label for="category_id" class="text-secondary">Description</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Select Category</option>
                    @foreach ($categories as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
                <label for="bed_number" class="text-secondary">Bed Number</label>
                <input type="number" name="bed_number" class="form-control mb-2" placeholder="Enter Bed no." required>
                <label for="description" class="text-secondary">Description</label>
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
    let table = $('#bedList').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.bedmanagement.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'bed_number' },
            { data: 'category' },
            { data: 'description' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#createBtn').click(function() {
        $('#categoryForm')[0].reset();
        $('#id').val('');
        $('#bedModal').modal('show');
    });
$('#bedList').on('click', '.editBtn', function() {
    let id = $(this).data('id');
    $.get("{{ url('admin/bedmanagement/') }}/" + id + "/edit", function(data) {
        console.log(data);
        $('#id').val(data.id);
        $('input[name="bed_number"]').val(data.bed_number);
        $('#category_id').val(data.category_id); // ← FIXED LINE
        $('textarea[name="description"]').val(data.description);
        $('#bedModal').modal('show');
    });
});


  $('#categoryForm').submit(function(e) {
    e.preventDefault();
    $.post("{{ route('admin.bedmanagement.store') }}", $(this).serialize())
        .done(function(response) {
            $('#bedModal').modal('hide');
            $('#bedList').DataTable().ajax.reload();
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


    $('#bedList').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure want to delete this bed?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/bedmanagement/') }}/" + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#bedList').DataTable().ajax.reload();
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

