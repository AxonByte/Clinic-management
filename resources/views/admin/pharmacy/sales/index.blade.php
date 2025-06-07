
@extends('layouts.app')
@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Sales List</h4>
               </div>
               <a class="btn btn-primary" href="{{ route('admin.pharmacy.sales.create') }}">Add Sales</a>

                {{-- <button class="btn btn-primary mb-3" id="createBtn">Add Sales</button> --}}
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="salesTable" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>Items</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Actions</th>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
 <script>
<script>
$(function () {
    $('#salesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.pharmacy.sales.index') }}',
        columns: [
            { data: 'id' },
            { data: 'items' },
            { data: 'subtotal' },
            { data: 'discount' },
            { data: 'total' },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
});
  $('#categoryForm').submit(function(e) {
    e.preventDefault();
    $.post("{{ route('admin.medicine.medicine-categories.store') }}", $(this).serialize())
        .done(function(response) {
            $('#categoryModal').modal('hide');
            $('#madicineCategory').DataTable().ajax.reload();
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


    $('#madicineCategory').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure want to delete this category?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/medicine/medicine-categories') }}/" + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#madicineCategory').DataTable().ajax.reload();
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

