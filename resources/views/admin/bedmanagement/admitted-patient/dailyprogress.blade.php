@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Tabs -->
    @include('admin.bedmanagement.admitted-patient.partials.tabs', ['active' => 'dailyprogress', 'admission' => $admission])

    <!-- Progress Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Daily Progress Notes</h5>
            <table class="table table-bordered text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Description</th>
                        <th>Nurse</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($progressNotes as $note)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($note->created_at)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($note->created_at)->format('h:i A') }}</td>
                            <td>{{ $note->description }}</td>
                            <td>{{ $note->nurse->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.bedmanagement.admission.dailyprogress.edit', [$admission->id, $note->id]) }}" class="btn btn-sm btn-info">Edit</a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No progress notes yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Progress Note Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">{{ isset($editNote) ? 'Edit Progress Note' : 'Add Progress Note' }}</h5>

        <form action="{{ isset($editNote) ? route('admin.bedmanagement.admission.dailyprogress.update', [$admission->id, $editNote->id]) : route('admin.bedmanagement.admission.dailyprogress.store', $admission->id) }}" method="POST">
            @csrf
            @if(isset($editNote))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="nurse_id" class="form-label">Nurse</label>
                <select name="nurse_id" class="form-select" required>
                    <option value="">Search Nurse Name / ID</option>
                    @foreach($nurses as $nurse)
                        <option value="{{ $nurse->id }}" {{ isset($editNote) && $editNote->nurse_id == $nurse->id ? 'selected' : '' }}>
                            {{ $nurse->name }} (ID: {{ $nurse->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" value="{{ isset($editNote) ? $editNote->created_at->format('Y-m-d') : now()->format('Y-m-d') }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Time</label>
                <input type="time" class="form-control" name="time" value="{{ isset($editNote) ? $editNote->created_at->format('H:i') : '' }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control" required>{{ $editNote->description ?? '' }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($editNote) ? 'Update' : 'Submit' }}</button>
            @if(isset($editNote))
                <a href="{{ route('admin.bedmanagement.admission.dailyprogress', $admission->id) }}" class="btn btn-secondary">Cancel</a>
            @endif
        </form>
    </div>
</div>

</div>
@endsection
