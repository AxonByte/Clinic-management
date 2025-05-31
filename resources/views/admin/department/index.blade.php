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
                <div class="card-header d-flex justify-content-between mb-2">
                    <div class="header-title">
                        <h4>Departments</h4>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="bi bi-plus-lg"></i> Add New Department
            </button>
                </div>


        {{-- <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4><i class="bi bi-building me-2 text-primary"></i>Departments</h4>
                <nav>
                    <small><a href="#" class="text-decoration-none text-primary">Home</a> / Department</small>
                </nav>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="bi bi-plus-lg"></i> Add New Department
            </button>

        </div> --}}

        <!-- Modal -->
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addDepartmentForm" enctype="multipart/form-data" action="{{ route('admin.department.store')}}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="departmentName" class="form-label">Department Name <span
                                        style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="departmentName" name="name"
                                    placeholder="Enter department name">
                            </div>
                            <div class="mb-3">
                                <label for="departmentDesc" class="form-label"><span
                                        style="color:red;">*</span>Description</label>
                                <textarea id="departmentDesc" class="form-control" name="description"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- Table Tools -->
        
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mt-5" id="department">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        
      </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let departmentEditor;
    ClassicEditor
        .create(document.querySelector('#departmentDesc'))
        .then(editor => {
            departmentEditor = editor;
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });
</script>
@if(session('success') || session('error'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
    });
</script>
@endif
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $('#department').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.department.index') }}",
        columns: [
            { data: 'name', name: 'name' },
           {
                data: 'description',
                name: 'description',
                render: function(data, type, row) {
                    if (type === 'display' && data) {
                        const div = document.createElement("div");
                        div.innerHTML = data;
                        return div.textContent || div.innerText || "";
                    }
                    return data;
                }
            },

            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });


});

$(document).on('click', '.edit-btn', function() {
    let id = $(this).data('id');
    let name = $(this).data('name');
    let description = $(this).data('description');

    // Set modal title & button text
    $('#addDepartmentModalLabel').text('Edit Department');
    $('#addDepartmentForm button[type="submit"]').text('Update Department');

    $('#departmentName').val(name);

    // Set data into CKEditor instance
    if (departmentEditor) {
        departmentEditor.setData(description);
    }

    // Change form action to update route (adjust route as needed)
    let updateUrl = "{{ url('/admin/department') }}/" + id;
    $('#addDepartmentForm').attr('action', updateUrl);

    if ($('#addDepartmentForm input[name="_method"]').length === 0) {
        $('#addDepartmentForm').append('<input type="hidden" name="_method" value="PUT">');
    } else {
        $('#addDepartmentForm input[name="_method"]').val('PUT');
    }

    $('#addDepartmentModal').modal('show');
});

$('#addDepartmentModal').on('hidden.bs.modal', function () {
    $('#addDepartmentModalLabel').text('Add New Department');
    $('#addDepartmentForm button[type="submit"]').text('Save Department');
    $('#addDepartmentForm').attr('action', "{{ route('admin.department.store') }}");
    $('#addDepartmentForm input[name="_method"]').remove();
    $('#departmentName').val('');
    if (departmentEditor) {
        departmentEditor.setData('');
    }
});
</script>

<script>
$(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');

    if (confirm("Are you sure you want to delete this department?")) {
        $.ajax({
            url: "{{ url('admin/department') }}/" + id,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#department').DataTable().ajax.reload();
                showToast('Department deleted successfully!', 'success');
            },
            error: function (xhr) {
                showToast('Failed to delete department.', 'danger');
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
