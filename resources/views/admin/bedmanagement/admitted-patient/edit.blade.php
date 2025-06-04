@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <i class="bi bi-hospital fs-3 text-primary me-2"></i>
        <h4 class="mb-0">Admission Details | {{ $admission->patient->name }}</h4>
    </div>

    <!-- Tabs -->
    @include('admin.bedmanagement.admitted-patient.partials.tabs', ['active' => 'checkin', 'admission' => $admission])


    <div class="row">
        <!-- Editable Form -->
        <div class="col-md-8">

             <div class="col-md-8">
                @yield('tab-content')
            </div>
            <form method="POST" action="{{ route('admin.bedmanagement.admition.update', $admission->id) }}">
                @csrf
                @method('PUT')

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Check In</h5>

                        <div class="mb-3">
                            <label class="form-label">Allotted Time</label>
                            <input type="datetime-local" name="admitted_at" class="form-control"
                                   value="{{ \Carbon\Carbon::parse($admission->admitted_at)->format('Y-m-d\TH:i') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bed Category</label>
                            <select name="bed_category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($category as $categories)
                                    <option value="{{ $categories->id }}"
                                        {{ $admission->bed_category_id == $categories->id ? 'selected' : '' }}>
                                        {{ $categories->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bed ID</label>
                            <select name="bed_id" class="form-control" required>
                                <option value="">Select Bed id</option>
                                @foreach($beds as $bed)
                                    <option value="{{ $bed->id }}" {{ $admission->bed_id == $bed->id ? 'selected' : '' }}>{{ $bed->bed_number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" class="form-control" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{$admission->patient_id == $patient->id ? 'selected' : ''}}>{{ $patient->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Checkboxes -->
                       <div class="mb-3">
                            <label class="form-label">Category:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category" value="Urgent"
                                    {{ old('category', $admission->category) == 'Urgent' ? 'checked' : '' }}>
                                <label class="form-check-label">Urgent</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category" value="Planned"
                                    {{ old('category', $admission->category) == 'Planned' ? 'checked' : '' }}>
                                <label class="form-check-label">Planned</label>
                            </div>
                        </div>

                        <!-- Reactions -->
                        <div class="mb-3">
                            <label class="form-label">Reactions:</label>
                            <textarea name="reactions" class="form-control">{{ old('reactions', $admission->reactions) }}</textarea>
                        </div>

                        <!-- Transferred From -->
                        <div class="mb-3">
                            <label class="form-label">Transferred From:</label>
                            <textarea name="transferred_from" class="form-control">{{ old('transferred_from', $admission->transferred_from) }}</textarea>
                        </div>

                        <!-- Diagnosis A Hospitalization -->
                        <div class="mb-3">
                            <label class="form-label">Diagnosis A Hospitalization:</label>
                            <textarea name="diagnosis_hospitalization" class="form-control">{{ old('diagnosis_hospitalization', $admission->diagnosis_hospitalization) }}</textarea>
                        </div>

                        <!-- Doctor -->
                        <div class="mb-3">
                            <label class="form-label">Doctor</label>
                            <select name="doctor_id" class="form-select" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ $admission->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} (Id:{{ $doctor->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Diagnosis -->
                        <div class="mb-3">
                            <label class="form-label">Diagnosis:</label>
                            <textarea name="diagnosis" class="form-control">{{ old('diagnosis', $admission->diagnosis) }}</textarea>
                        </div>

                        <!-- Other Illnesses -->
                        <div class="mb-3">
                            <label class="form-label">Other Illnesses:</label>
                            <textarea name="other_illnesses" class="form-control">{{ old('other_illnesses', $admission->other_illnesses) }}</textarea>
                        </div>

                        <!-- History -->
                        <div class="mb-3">
                            <label class="form-label">History:</label>
                            <textarea name="history" class="form-control">{{ old('history', $admission->history) }}</textarea>
                        </div>

                        <!-- Blood Group -->
                        <div class="mb-3">
                            <label class="form-label">Blood Group</label>
                            <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group', $admission->blood_group) }}">
                        </div>

                        <!-- Accepting Doctor -->
                        <div class="mb-3">
                            <label class="form-label">Accepting Doctor</label>
                            <select name="accepting_doctor_id" class="form-select">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ $admission->accepting_doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Sidebar -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">Admission Details</h6>

                    <x-detail-row label="Admission ID" :value="$admission->id" />
                    <x-detail-row label="Admission Date" :value="$admission->admission_time" />
                    <x-detail-row label="Discharge Date" :value="$admission->discharge_date ?? '--'" />
                    <x-detail-row label="Patient Name" :value="$admission->patient->name" />
                    <x-detail-row label="Patient ID" :value="$patient->id" />
                    <x-detail-row label="Gender" :value="$admission->patient->gender" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
