

@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Cases List</h4>
               </div>
                <a href="{{ route('admin.patient.cases.create') }}" class="btn btn-primary">Add New Cases</a>
            </div>


            <div class="card-body">
               <div class="table-responsive">
                  <table id="caseTable" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Title</th>
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
    let table = $('#caseTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: '{{ route("admin.patient.cases.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false },
            { data: 'date' },
            { data: 'patient', name: 'patient' },
            { data: 'title' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    
    $('#caseTable').on('click', '.deleteBtn', function() {
        if (confirm("Are you sure?")) {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ url('admin/patient/cases') }}/" + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#caseTable').DataTable().ajax.reload();
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

