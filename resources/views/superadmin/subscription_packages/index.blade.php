@extends('superadmin.dashboard.layouts.app')

@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Subscription Packages</h3>
        <a href="{{ route('superadmin.subscription_packages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Package
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="packagesTable" style="width:100%">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Package Name</th>
                        <th>Duration (Months)</th>
                        <th>Patient Limit</th>
                        <th>Doctor Limit</th>
                        <th>Original Price </th>
                        <th>Discounted Price </th>
                        <th>Show In Frontend</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Toast for feedback -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
    <div id="messageToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#packagesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('superadmin.subscription_packages.index') }}",  // Make sure your controller returns JSON for datatables
        columns: [
            { data: 'id', name: 'id' },
            { data: 'package_name', name: 'package_name' },
            { data: 'duration', name: 'duration' },
            { data: 'patient_limit', name: 'patient_limit' },
            { data: 'doctor_limit', name: 'doctor_limit' },
            { data: 'original_price', name: 'original_price', render: $.fn.dataTable.render.number(',', '.', 2, '$') },
            { data: 'discounted_price', name: 'discounted_price', render: $.fn.dataTable.render.number(',', '.', 2, '$') },
          
            { data: 'show_in_frontend', name: 'show_in_frontend', render: function(d) { return d ? 'Yes' : 'No'; } },
        
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
    });

    // Show toast messages if session exists
    @if(session('success') || session('error'))
        const toastEl = document.getElementById('messageToast');
        const toastBody = document.getElementById('toastBody');
        @if(session('success'))
            toastBody.textContent = "{{ session('success') }}";
            toastEl.classList.remove('bg-danger');
            toastEl.classList.add('bg-success');
        @elseif(session('error'))
            toastBody.textContent = "{{ session('error') }}";
            toastEl.classList.remove('bg-success');
            toastEl.classList.add('bg-danger');
        @endif
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    @endif
});
</script>

@endsection
