@extends('superadmin.dashboard.layouts.app')

@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Hospitals</h3>
        <a href="{{ route('superadmin.hospitals.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Hospital
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="hospitalsTable" style="width:100%">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Hospital Name</th>
                        <th>Subscription</th>
                        <th>Subscription Start</th>
                        <th>Subscription End</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Toast Notification -->
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
    $('#hospitalsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('superadmin.hospitals.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'subscription_package', name: 'subscription_package' },
            { data: 'subscription_start_date', name: 'subscription_start_date' },
            { data: 'subscription_end_date', name: 'subscription_end_date' },
            { data: 'status', name: 'status' },
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
        new bootstrap.Toast(toastEl).show();
    @endif
});
</script>

@endsection
