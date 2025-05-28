@extends('layouts.app')
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Doctor Visit</h4>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visitModal">
                        +</i> Add Doctor Visit
                    </button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="visitModal" tabindex="-1" aria-labelledby="addDoctorVisitModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" style="width:800px">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="addDoctorVisitModalLabel">
                                    <i class="bi bi-person-circle me-2"></i> Add Doctor Visit
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form id="visitForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="visit_id" name="visit_id">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="doctor_id" class="form-label fw-bold text-secondary">DOCTOR<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="doctor_id" name="doctor_id" required>
                                                <option value="">Select Doctor</option>
                                                @foreach ($doctors as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label for="visit_description" class="form-label fw-bold text-secondary">VISIT
                                            DESCRIPTION <span class="text-danger">*</span></label>
                                        <input type="text" name="visit_description" id="visit_description"
                                            class="form-control" />
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="visit_charges" class="form-label fw-bold text-secondary">VISIT
                                                CHARGES <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="visit_charges"
                                                name="visit_charges" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="status" class="form-label fw-bold text-secondary">VISIT
                                                STATUS<span class="text-danger">*</span></label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
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
                </div>


                <!-- Table Tools -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="doctorVisitTable" class="table table-striped" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Doctor Name</th>
                                    <th>Visit Description</th>
                                    <th>Visit Charges</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

            </div>

                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

                <script>
                    $(document).ready(function() {
                        $('#doctorVisitTable').DataTable({
                            processing: true,
                            serverSide: true,
                            destroy: true,
                            scrollX: true,
                            paging: true,
                            autoWidth: false,
                            responsive: true,
                            ajax: "{{ route('admin.doctor.doctor-visits.index') }}",
                            columns: [{
                                    data: 'id',
                                    name: 'id'
                                },
                                {
                                    data: 'doctor_name',
                                    name: 'doctor_name'
                                },
                                {
                                    data: 'visit_description',
                                    name: 'visit_description'
                                },
                                {
                                    data: 'visit_charges',
                                    name: 'visit_charges'
                                },
                                {
                                    data: 'status',
                                    name: 'status'
                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });

                        $('#visitForm').submit(function(e) {
                            e.preventDefault();

                            let formData = new FormData(this);
                            let visitId = $('#visit_id').val();
                            let url = visitId ?
                                `/admin/doctor/doctor-visits/${visitId}` :
                                `/admin/doctor/doctor-visits`;

                            if (visitId) {
                                formData.append('_method', 'PUT'); // Spoof PUT for update
                            }

                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(res) {
                                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                                        'visitModal'));
                                    modal.hide();

                                    $('#doctorVisitTable').DataTable().ajax.reload();

                                    const message = visitId ?
                                        'Success! Doctor visit updated.' :
                                        'Success! Doctor visit saved.';

                                    showToast(message, 'success');
                                    $('#visitModal').modal('hide');
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

                    $(document).on('click', '.editBtn', function() {
                        const doctorVisitId = $(this).data('id');
                        const doctor_id = $(this).data('doctor_id');
                        const visit_description = $(this).data('visit_description');
                        const status = $(this).data('status');
                        const visit_charges = $(this).data('visit_charges');

                        $('#addDoctorVisitModalLabel').text('Edit Doctor Visit');
                        $('#visitForm button[type="submit"]').text('Update Visit');

                        $('#visit_id').val(doctorVisitId);
                        $('#doctor_id').val(doctor_id);
                        $('#visit_description').val(visit_description);
                        $('#visit_charges').val(visit_charges);
                        $('#status').val(status);

                        $('#visitModal').modal('show');

                    });

                    $('#visitModal').on('hidden.bs.modal', function() {
                        $('#visitForm')[0].reset(); // Reset fields
                        $('#visit_id').val('');
                        $('#addDoctorVisitModalLabel').text('Add Doctor Visit');
                        $('#visitForm button[type="submit"]').text('Submit');
                        $('#visitForm input[name="_method"]').remove();
                        $('#doctor_id, #status').val('').change(); // reset dropdowns
                    });
                </script>

                <script>
                    $(document).on('click', '.deleteBtn', function() {
                        let id = $(this).data('id');

                        if (confirm("Are you sure you want to delete this doctor visit?")) {
                            $.ajax({
                                url: "{{ url('admin/doctor/doctor-visits') }}/" + id,
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    $('#doctors').DataTable().ajax.reload();
                                    showToast('Doctor visit deleted successfully!', 'success');
                                },
                                error: function(xhr) {
                                    showToast('Failed to delete doctor.', 'danger');
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
