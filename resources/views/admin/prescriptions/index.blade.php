
@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
  
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Prescription</h4>
               </div>
               @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <a class="btn btn-primary" href="{{ route('admin.prescription.create')}}">+Add Pescription</a>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="prescription" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Patient</th>
                            <th>Medicines</th>
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
$(function() {
    let table = $('#prescription').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.prescription.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'date', name: 'date' },
            { data: 'doctor', name: 'doctor.name' },
            { data: 'patient', name: 'patient.name' },
            { data: 'medicines', name: 'medicines', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    $('#prescription').on('click', '.deleteBtn', function() {
    if (confirm("Are you sure want to delete this prescription ?")) {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/prescription') }}/" + id,
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

});
</script>
 @endsection

