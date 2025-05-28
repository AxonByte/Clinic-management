@extends('layouts.app')
@section('content')

@php
    $weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $appoinmentDuration = ['5 minutes', '10 minutes', '20 minutes', '30 minutes', '40 minutes', '50 minutes', '60 minutes'];
    $today = \Carbon\Carbon::now()->format('l'); // Full day name
@endphp
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <!-- Header -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div id="messageToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastBody"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        

         <div class="row">
        <div class="col-sm-12">
            <div class="card">


         <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Schedule</h4>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                +</i> Add New Schedule
            </button>

        </div>
<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" class="modal-content" id="scheduleForm">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label for="doctor_id" class="form-label text-secondary fw-bold">DOCTOR<span style="color:red"> *<span></label>
                    <select name="doctor_id" class="form-select" id="doctor_id" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="weekday" class="form-label text-secondary fw-bold">WEEKDAY<span style="color:red"> *<span></label>
                    <select name="weekday" class="form-select" id="weekday" required>
                        @foreach($weekdays as $day)
                            <option value="{{ $day }}" {{ $day === $today ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="start_time" class="form-label text-secondary fw-bold">START TIME <span style="color:red"> *<span></label>
                    <input type="time" name="start_time" class="form-control" id="start_time" required>
                </div>

                <div class="col-md-6">
                    <label for="end_time" class="form-label text-secondary fw-bold">END TIME <span style="color:red"> *<span></label>
                    <input type="time" name="end_time" class="form-control" id="end_time" required>
                </div>

                <div class="col-12">
                     <div class="col-md-6">
                    <label for="appointment_duration" class="form-label text-secondary fw-bold">APPOINMENT DURATION  <span style="color:red"> *<span></label>
                    <select name="appointment_duration" class="form-select" id="appointment_duration" required>
                        @foreach($appoinmentDuration as $appoinment)
                            <option value="{{ $appoinment }}">{{ $appoinment }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>


        <!-- Table Tools -->
       
            <div class="card-body">
                    <div class="table-responsive">
                    <table id="doctorScheduleTable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Doctor Name</th>
                                <th> Weekday</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Duration</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        
    </div>
    </div>
    </div>
    </div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
$(document).ready(function () {
    $('#doctorScheduleTable').DataTable({
         processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: "{{ route('admin.doctor.schedules') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'doctor_name', name: 'doctor_name'},
            {data: 'weekday', name: 'weekday'},
            {data: 'start_time', name: 'start_time'},
            {data: 'end_time', name: 'end_time'},
            {data: 'appointment_duration', name: 'appointment_duration'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        
    });
$('#scheduleForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);
    let url =  "{{route('admin.doctor.schedules.store')}}";
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
            $('#addScheduleModal').modal('hide');
             $('#doctorScheduleTable').DataTable().ajax.reload();
            const message = 'Doctor Schedule added successfully!.';
            window.location.reload();
            showToast(message, 'success');
            
        },
        error: function(xhr) {
            let msg = 'Something went wrong.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
            }
            showToast(msg, 'danger');
        }
    });
});

});

</script>

<script>
$(document).on('click', '.deleteBtn', function () {
    let id = $(this).data('id');

    if (confirm("Are you sure you want to delete this doctor schedule?")) {
        $.ajax({
            url: "{{ url('admin/doctor/schedules') }}/" + id,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                showToast('Doctor schedule deleted successfully!', 'success');
                window.location.reload();
            },
            error: function (xhr) {
                showToast('Failed to delete doctor schedule.', 'danger');
            }
        });
    }
});

function showToast(message, type) {
    const toastEl = document.getElementById('messageToast');
    const toastBody = document.getElementById('toastBody');
    toastBody.textContent = message;
    toastEl.className = 'toast align-items-center text-white bg-' + type + ' border-0';
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}
</script>
@endsection
