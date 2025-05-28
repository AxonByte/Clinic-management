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
                        <h4 class="card-title">Symptom List</h4>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSymptomModal">+ Add Symptom</button>
                </div>
        

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped" id="symptomTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            @foreach($symptoms as $index => $symptom)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $symptom->name }}</td>
                <td>{{ $symptom->description }}</td>
                <td>
                    <button class="btn btn-sm btn-info edit-btn" 
                        data-id="{{ $symptom->id }}" 
                        data-name="{{ $symptom->name }}" 
                        data-description="{{ $symptom->description }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#editSymptomModal">
                        ✏️
                    </button>
                    <form action="{{ route('symptoms.destroy', $symptom->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addSymptomModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <form action="{{ route('symptoms.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Symptom</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editSymptomModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <form method="POST" class="modal-content" id="editForm">
      @csrf @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Symptom</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-id">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" id="edit-name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" id="edit-description" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const description = this.dataset.description;

            document.getElementById('edit-name').value = name;
            document.getElementById('edit-description').value = description;
            const form = document.getElementById('editForm');
            form.action = `/symptoms/${id}`;
        });
    });
</script>
<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const description = this.dataset.description;
            // Set form action dynamically
            const form = document.getElementById('editForm');
            form.action = `/symptoms/${id}`;

            // Set field values
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-description').value = description || '';
        });
    });
</script>
@endsection
