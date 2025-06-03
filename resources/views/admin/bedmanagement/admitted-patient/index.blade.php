
@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
  
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Admitted Patient</h4>
               </div>
               @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <a class="btn btn-primary" href="{{ route('admin.bedmanagement.admition.create')}}">+Admit Patient</a>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="admitpatient" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Bed Id</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Admit Time</th>
                            {{-- <th>Descharge Time</th>
                            <th>Due Bill</th> --}}
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
 <script>
$(function() {
    let table = $('#admitpatient').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.bedmanagement.admition.list") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'bed_number', name: 'bed_number' },   
            { data: 'patient', name: 'patient' },
            { data: 'doctor', name: 'doctor' },
            { data: 'admission_time', name: 'admission_time' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#madicine').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure want to delete this admit patient?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/bedmanagement/admitted-patient') }}/" + id,
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

