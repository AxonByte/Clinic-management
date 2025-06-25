@extends('superadmin.dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Services</h3>
        <a href="{{ route('superadmin.services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Service
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="servicesTable" style="width:100%">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index:1055">
    <div id="messageToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(() => {
    const table = $('#servicesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('superadmin.services.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'category', name: 'category.name', orderable: false, searchable: false },
            { data: 'subcategory', name: 'subcategory.name', orderable: false, searchable: false },
            { data: 'type', name: 'service_type' },
            { data: 'price', name: 'default_price' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className:"text-center" },
        ],
        order: [[0, 'desc']],
        pageLength: 10,
    });

    @if(session('success') || session('error'))
        const toastEl = $('#messageToast');
        $('#toastBody').text("{{ session('success') ?? session('error') }}");
        toastEl.removeClass('bg-success bg-danger')
               .addClass(session('success') ? 'bg-success' : 'bg-danger')
               .toast('show');
    @endif
});
</script>
@endsection
