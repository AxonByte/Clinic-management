{{-- <!-- resources/views/diagnosis/index.blade.php -->
@extends('layouts.app')
@section('content')
<style>
    .modal-xl {
  max-width: 50% !important;
  width: 50% !important;
}
</style>
<div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between mb-2">
                    <div class="header-title">
    <h4>Diagnosis List</h4>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDiagnosisModal">+ Add Diagnosis</button>
   </div>

    <div class="card-body">
            <div class="table-responsive">
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Disease</th>
                            <th>ICD Code</th>
                            <th>Outbreak?</th>
                            <th>Max Patients/Week</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diagnoses as $item)
                            <tr>
                                <td>{{ $item->disease_name }}</td>
                                <td>{{ $item->icd_code }}</td>
                                <td>{{ $item->has_outbreak_potential ? 'Yes' : 'No' }}</td>
                                <td>{{ $item->max_weekly_patients }}</td>
                                <td>
                                <button class="btn btn-info btn-sm" onclick="editDiagnosis({{ $item->id }})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteDiagnosis({{ $item->id }})">Delete</button>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</div>
</div>

<!-- Add Diagnosis Modal -->
<div class="modal fade" id="addDiagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('diagnoses.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Disease Name</label>
                        <input type="text" name="disease_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>ICD-10 Code</label>
                        <input type="text" name="icd_code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" id="descEditor"></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="has_outbreak_potential" id="outbreakCheck" value="1">
                        <label class="form-check-label" for="outbreakCheck">Disease With Outbreak Potential</label>
                    </div>
                    <div class="mb-3">
                        <label>Maximum Expected Number Of Patients In A Week</label>
                        <input type="number" name="max_weekly_patients" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="diagnosisModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="diagnosisForm">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="modal-content">
            <div class="modal-header"><h5>Add Diagnosis</h5></div>
            <div class="modal-body">
                <input name="disease_name" id="disease_name" class="form-control mb-2" placeholder="Disease Name">
                <input name="icd_code" id="icd_code" class="form-control mb-2" placeholder="ICD Code">
                <textarea name="description" id="description" class="form-control mb-2" placeholder="Description"></textarea>
                <label><input type="checkbox" name="is_outbreak" id="is_outbreak"> Disease With Outbreak Potential</label>
                <input name="max_weekly_patients" id="max_weekly_patients" class="form-control mt-2" placeholder="Max Patients/Week">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
  </div>
</div>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
   
    // Add/Update form submission
$('#diagnosisForm').submit(function(e) {
    e.preventDefault();
    let form = $(this);
    let id = $('#id').val();
    let url = id ? `/diagnosis/${id}` : '/diagnosis';
    let type = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: type,
        data: form.serialize(),
        success: function(res) {
            $('#diagnosisModal').modal('hide');
            location.reload();
        }
    });
});

// Clear form for add
function clearForm() {
    $('#diagnosisForm')[0].reset();
    $('#id').val('');
}

// Edit data
function editDiagnosis(id) {
    $.get('/admin/diagnosis/' + id + '/edit', function(data) {
        $('#id').val(data.id);
        $('#disease_name').val(data.disease_name);
        $('#icd_code').val(data.icd_code);
        $('#description').val(data.description);
        $('#is_outbreak').prop('checked', data.is_outbreak);
        $('#max_weekly_patients').val(data.max_weekly_patients);
        $('#diagnosisModal').modal('show');
    });
}

// Delete
function deleteDiagnosis(id) {
    if (confirm('Are you sure?')) {
        $.ajax({
            url: '/admin/diagnosis/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                location.reload();
            }
        });
    }
}

</script>
@endsection --}}

@extends('layouts.app')
@section('content')
<style>
    .modal-xl {
        max-width: 50% !important;
        width: 50% !important;
    }
</style>

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between mb-2">
                    <div class="header-title">
                        <h4>Diagnosis List</h4>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDiagnosisModal">+ Add Diagnosis</button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Disease</th>
                                    <th>ICD Code</th>
                                    <th>Outbreak?</th>
                                    <th>Max Patients/Week</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($diagnoses as $item)
                                <tr>
                                    <td>{{ $item->disease_name }}</td>
                                    <td>{{ $item->icd_code }}</td>
                                    <td>{{ $item->has_outbreak_potential ? 'Yes' : 'No' }}</td>
                                    <td>{{ $item->max_weekly_patients }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="editDiagnosis({{ $item->id }})">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteDiagnosis({{ $item->id }})">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add Diagnosis Modal -->
<div class="modal fade" id="addDiagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('diagnoses.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold text-secondary">Disease Name</label>
                        <input type="text" name="disease_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-secondary">ICD-10 Code</label>
                        <input type="text" name="icd_code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-secondary">Description</label>
                        <textarea name="description" class="form-control" id="descEditor"></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="has_outbreak_potential" id="outbreakCheck" value="1">
                        <label class="form-check-label" for="outbreakCheck">Disease With Outbreak Potential</label>
                    </div>
                    <div class="mb-3" id="maxPatientsInput" style="display: none;">
                        <label class="text-secondary">Maximum Expected Number Of Patients In A Week</label>
                        <input type="number" name="max_weekly_patients" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Diagnosis Modal -->
<div class="modal fade" id="diagnosisModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="diagnosisForm">
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Diagnosis</h5>
                </div>
                <div class="modal-body">
                     <label class="fw-bold text-secondary">Disease Name</label>
                    <input name="disease_name" id="disease_name" class="form-control mb-2" placeholder="Disease Name">
                    <label class="fw-bold text-secondary">ICD-10 Code</label>
                    
                    <input name="icd_code" id="icd_code" class="form-control mb-2" placeholder="ICD Code">
                    <label class="fw-bold text-secondary">Description</label>
                    <textarea name="description" id="description" class="form-control mb-2" placeholder="Description"></textarea>
                    
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_outbreak" id="is_outbreak" class="form-check-input">
                        <label for="is_outbreak" class="form-check-label text-secondary">Disease With Outbreak Potential</label>
                    </div>
                    <div id="maxPatientsInputEdit" style="display: none;">
                        <label class="fw-bold text-secondary">Maximum Expected Number Of Patients In A Week</label>
                        <input name="max_weekly_patients" id="max_weekly_patients" class="form-control" placeholder="Max Patients/Week">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Add/Update form submission
    $('#diagnosisForm').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let id = $('#id').val();
        let url = id ? `/diagnosis/${id}` : '/diagnosis';
        let type = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: type,
            data: form.serialize(),
            success: function(res) {
                $('#diagnosisModal').modal('hide');
                location.reload();
            }
        });
    });

    // Clear form for add
    function clearForm() {
        $('#diagnosisForm')[0].reset();
        $('#id').val('');
        $('#maxPatientsInputEdit').hide();
    }

    // Edit data
    function editDiagnosis(id) {
        $.get('/admin/diagnosis/' + id + '/edit', function(data) {
            $('#id').val(data.id);
            $('#disease_name').val(data.disease_name);
            $('#icd_code').val(data.icd_code);
            $('#description').val(data.description);
            $('#is_outbreak').prop('checked', data.is_outbreak);
            $('#max_weekly_patients').val(data.max_weekly_patients);

            // Show or hide field based on checkbox
            $('#maxPatientsInputEdit').toggle(data.is_outbreak);

            $('#diagnosisModal').modal('show');
        });
    }

    // Delete
    function deleteDiagnosis(id) {
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '/admin/diagnosis/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload();
                }
            });
        }
    }

    // Toggle maxWeeklyPatients input in add modal
    $(document).ready(function() {
        $('#outbreakCheck').change(function() {
            $('#maxPatientsInput').toggle(this.checked);
        });

        $('#is_outbreak').change(function() {
            $('#maxPatientsInputEdit').toggle(this.checked);
        });
    });
</script>
@endsection

