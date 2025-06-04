@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <i class="bi bi-hospital fs-3 text-primary me-2"></i>
        <h4 class="mb-0">Admission Details | {{ $admission->patient->name }}</h4>
    </div>

    <!-- Tabs -->
    @include('admin.bedmanagement.admitted-patient.partials.tabs', ['active' => 'discharge', 'admission' => $admission])


    <div class="row">
        <!-- Editable Form -->
        <div class="col-md-8">

             <div class="col-md-8">
                @yield('tab-content')
            </div>
            <form action="{{ route('admin.bedmanagement.admission.discharge.store',$admission->id) }}" method="POST">
                @csrf

                  <div class="card shadow-sm mb-4">
                    <div class="card-body">
                @if(isset($checkout))
                     <input type="hidden" name="checkout_id" value="{{ $discharge->id }}">
                @endif

                <input type="hidden" name="patient_admitted_id" value="{{ $admission->id }}">
                <div class="mb-3">
                <label class="text-secondary mb-2">Checkout Date Time</label>
                <input type="datetime-local" name="discharge_time" class="form-control"
                    value="{{ old('discharge_time', isset($checkout) ? $checkout->discharge_time : '') }}" required>

                </div>
                <div class="mb-3">
                <label class="text-secondary mb-2">Final Diagnosis:</label>
                <textarea name="final_diagnosis" class="form-control">{{ old('final_diagnosis', $checkout->final_diagnosis ?? '') }}</textarea>
                </div>
                <!-- Anatomopathologic Diagnosis -->
                <div class="mb-3">
                <label class="text-secondary mb-2">Anatomopatologic Diagnosis:</label>
                <textarea name="anatomopatologic_diagnosis" class="form-control">{{ old('anatomopatologic_diagnosis', $checkout->anatomopatologic_diagnosis ?? '') }}</textarea>
                </div>
                <!-- Checkout Diagnosis -->
                <div class="mb-3">
                <label class="text-secondary mb-2">Checkout Diagnosis:</label>
                <textarea name="checkout_diagnosis" class="form-control">{{ old('checkout_diagnosis', $checkout->checkout_diagnosis ?? '') }}</textarea>
                </div>

                <!-- Checkout State -->
                <div class="mb-3">
                 <label class="text-secondary mb-2">Checkout State:</label>
                <textarea name="checkout_state" class="form-control">{{ old('checkout_state', $checkout->checkout_state ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                 <label class="text-secondary mb-2">Initial diagnosis:</label>
                <textarea name="initial_diagnosis" class="form-control">{{ old('initial_diagnosis', $checkout->initial_diagnosis ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                 <label class="text-secondary mb-2">Medicine To Take After Discharge:</label>
                <textarea name="medicine_description" class="form-control">{{ old('medicine_description', $checkout->medicine_description ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                 <label class="text-secondary mb-2 ">Instruction:</label>
                <textarea name="instruction" class="form-control">{{ old('instruction', $checkout->instruction ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                 <label class="text-secondary mb-2">Doctor:</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}"
                            {{ old('doctor_id', $checkout->doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
                </div>
                <button class="btn btn-primary" type="submit">{{ isset($checkout) ? 'Update' : 'Save' }}</button>
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
                    <x-detail-row label="Patient ID" :value="$admission->patient->id" />
                    <x-detail-row label="Gender" :value="$admission->patient->gender" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
