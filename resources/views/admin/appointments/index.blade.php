

@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
  

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Appointment</h4>
               </div>
                <a class="btn btn-primary" href="{{ route('admin.appointment.create')}}">+ Add Appointment</a>
            </div>

             {{-- @php 
                  $statuses = DB::table('statuses')->get();
                 
             @endphp --}}
            <div class="card-body">
                <ul class="nav nav-tabs" id="appointmentTabs" role="tablist">
                    @foreach($statuses as $key => $status)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $key == 0 ? 'active' : '' }}" id="tab-{{ $key }}"
                                    data-bs-toggle="tab"
                                    data-status="{{ $status }}"
                                    data-bs-target="#tab-pane-{{ $key }}" type="button" role="tab">
                                 {{ $status }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content pt-3">
                    @foreach($statuses as $key => $status)
                        <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="tab-pane-{{ $key }}" role="tabpanel">
                            <table id="table-{{ $key }}" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date-Time</th>
                                        <th>Visit Type</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Bill Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    @endforeach
                </div>


            </div>
            
         </div>
      </div>
   </div>
 </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script>   
$(function () {
    let dataTables = {};

    function loadTable(tabId, status) {
        if (dataTables[tabId]) return;

        const table = $('#table-' + tabId).DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            scrollX: true,
            paging: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: "{{ route('admin.appointment.data', ':status') }}".replace(':status', encodeURIComponent(status)),
                type: "GET"
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'patient', name: 'patient.name' },
                { data: 'doctor', name: 'doctor.name' },
                { data: 'appointment_date', name: 'appointment_date' },
                { data: 'visit_type', name: 'visit_type' },
                
                { data: 'status', name: 'status' },
                { data: 'notes', name: 'notes' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'bill_status', name: 'bill_status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ]
        });

        dataTables[tabId] = table;
    }

    // Load the first tab initially
    loadTable(0, 'All');

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
        const tabId = $(this).attr('id').split('-')[1];
        const status = $(this).data('status');
        loadTable(tabId, status);
    });

    $(document).on('click', '.deleteBtn', function() {
        if (confirm("Are you sure want to delete this record ?")) {
            let id = $(this).data('id');
            let tableId = $(this).closest('table').attr('id');

            $.ajax({
                url: "{{ url('admin/appointment') }}/" + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#' + tableId).DataTable().ajax.reload();
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

