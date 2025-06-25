@extends('layouts.app')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">Services</h4>
          <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
            + Add Service
          </a>
        </div>

        @if(session('success'))
          <div class="alert alert-success m-3">
            {{ session('success') }}
          </div>
        @endif

        <div class="card-body">
          <table class="table table-striped table-bordered" id="servicesTable" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Type</th>
                <th>Price(&#8377;)</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
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
    ajax: "{{ route('admin.services.index') }}",
    columns: [
      { data: 'id', name: 'id' },
      { data: 'name', name: 'name' },
      { data: 'type', name: 'service_type' },
      { data: 'price', name: 'default_price' },
      { data: 'status', name: 'status' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false, className: "text-center" },
    ],
    order: [[0, 'desc']],
    pageLength: 10,
  });
});
</script>
@endsection
