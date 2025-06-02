
@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
  
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Medicine</h4>
               </div>
               @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <a class="btn btn-primary" href="{{ route('admin.medicine.create')}}">+Add Medicine</a>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="madicine" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Generic Name</th>
                            <th>Category</th>
                            <th>Purchase Price</th>
                            <th>Sale Price</th>
                            <th>Quantity</th>
                            <th>Expiry Date</th>
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

<!-- Quantity Update Modal -->
<div class="modal fade" id="updateQuantityModal" tabindex="-1" aria-labelledby="updateQuantityLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="update-quantity-form" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="medicine_id" name="medicine_id">
                <div class="mb-3">
                    <label for="add_quantity" class="form-label">Add Quantity</label>
                    <input type="number" class="form-control" id="add_quantity" name="add_quantity" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
 <script>
$(function() {
    let table = $('#madicine').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.medicine.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'generic_name', name: 'generic_name' },
            { data: 'category', name: 'category.name' },
            { data: 'purchase_price', name: 'purchase_price' },
            { data: 'sale_price', name: 'sale_price' },
            { data: 'quantity', name: 'quantity' },
            { data: 'expiry_date', name: 'expiry_date' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#madicine').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure want to delete this medicine?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/medicine') }}/" + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#madicine').DataTable().ajax.reload();
                showMessage('success', response.message || 'Deleted successfully.');
            },
            error: function() {
                showMessage('danger', 'Error deleting data.');
            }
        });
    }
});

 $(document).on('click', '.update-qty-btn', function () {
        const id = $(this).data('id');
        const qty = $(this).data('qty');
        $('#medicine_id').val(id);
        $('#new_quantity').val(qty);
        $('#updateQuantityModal').modal('show');
    });

    // Handle form submission (AJAX)
    $('#update-quantity-form').submit(function (e) {
        e.preventDefault();
        const id = $('#medicine_id').val();
        const quantity = $('#add_quantity').val();
        const url = `/admin/medicine/${id}/update-quantity`;
        $.ajax({
            url: url,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                add_quantity: quantity
            },
            success: function (response) {
                $('#updateQuantityModal').modal('hide');
                table.ajax.reload(null, false);
                 showMessage('success', response.message || 'Quantity updated successfully');
            },
            error: function () {
                showMessage('danger', 'Error deleting data.');
            }
        });
    });

});
</script>
 @endsection

