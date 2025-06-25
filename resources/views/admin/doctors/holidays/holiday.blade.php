@extends('layouts.app')
@section('content')
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
            {{-- <div>
                <h4><i class="bi bi-building me-2 text-primary"></i>Holiday</h4>
                
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                <i class="bi bi-plus-lg"></i> Add New Holiday
            </button> --}}
            <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Holiday</h4>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                +</i> Add New Holiday
            </button>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="addHolidayForm" enctype="multipart/form-data"  method="POST">
                        @csrf
                        <input type="hidden" name="holidays_id" id="holidays_id">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="addHolidayModalLabel">Add New Holiday</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                         <div class="modal-body row g-4 mb-5">
                           <div class="col-md-6">
                                <label for="doctor_id" class="form-label text-secondary fw-bold">DOCTOR <span
                                        style="color:red;">*</span></label>
                               <select name="doctor_id" class="form-select" id="doctor_id" required style="width:300px!important;">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                            </div>
                           <div class="col-md-6">
                                <label for="date" class="form-label text-secondary fw-bold">DATE<span
                                        style="color:red;">*</span></label>
                               <input type="date" name="date" id="date" class="form-control" style="width:300px!important;">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- Table Tools -->
       
            <div class="card-body">
               
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mt-5" id="holiday">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>#</th>
                                <th>Doctor</th>
                                <th>Date</th>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script> const BASE_URL = "{{ url('/') }}";
$(document).ready(function () {
    $('#holiday').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: "{{ route('admin.doctor.holidays') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'doctor_name', name: 'doctor_name' },
            { data: 'date', name: 'date' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

$('#addHolidayForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);
    let visitId = $('#holidays_id').val();
    let url = visitId 
        ? `${BASE_URL}/admin/doctor/holidays/${visitId}` 
        : `${BASE_URL}/admin/doctor/holidays`;
    
    if (visitId) {
        formData.append('_method', 'PUT'); // Spoof PUT for update
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
           const modal = bootstrap.Modal.getInstance(document.getElementById('addHolidayModal'));
            modal.hide();
            const message = visitId 
                ? 'Holiday updated successfully!.' 
                : 'Holiday saved successfully.';
            
            showToast(message, 'success');
             $('#addHolidayModal').modal('hide');
             window.location.reload();
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
$(document).on('click', '.editBtn', function () {
    const doctorholidayId = $(this).data('id');
    const doctor_id = $(this).data('doctor_id');
    const date = $(this).data('date');

    $('#addHolidayModalLabel').text('Edit Doctor Visit');
    $('#addHolidayForm button[type="submit"]').text('Update Visit');

    $('#holidays_id').val(doctorholidayId);
    $('#doctor_id').val(doctor_id);
    $('#date').val(date);
    $('#addHolidayModal').modal('show');

});

$('#addHolidayModal').on('hidden.bs.modal', function () {
    $('#addHolidayModalLabel').text('Add New Department');
    $('#addHolidayForm button[type="submit"]').text('Save Holiday');
    $('#addHolidayForm').attr('action', "{{ route('admin.doctor.holidays.store') }}");
    $('#addHolidayForm input[name="_method"]').remove();
    $('#date').val('');
    
});
</script>

<script>
$(document).on('click', '.deleteBtn', function () {
    let id = $(this).data('id');

    if (confirm("Are you sure you want to delete this department?")) {
        $.ajax({
            url: "{{ url('admin/doctor/holidays') }}/" + id,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#department').DataTable().ajax.reload();
                showToast('Holiday deleted successfully!', 'success');
                window.location.reload();
            },
            error: function (xhr) {
                showToast('Failed to delete holiday.', 'danger');
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
