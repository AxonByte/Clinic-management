
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
@endsection

@push('scripts')
 <script>
$(function () {
    if ($.fn.DataTable.isDataTable('#salesTable')) {
      $('#salesTable').DataTable().clear().destroy();
    }
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

    $('#salesTable').on('click', '.deleteBtn', function() {
        if (confirm("Are you sure want to delete this sales?")) {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ url('admin/pharmacy/sales') }}/" + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#salesTable').DataTable().ajax.reload();
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
 @endpush

