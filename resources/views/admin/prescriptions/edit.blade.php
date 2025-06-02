@extends('layouts.app')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Edit Prescription</h4>
                    </div>
                    <a class="btn btn-secondary" href="{{ route('admin.prescription.index') }}">Back to Prescriptions</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.prescription.update', $prescription->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- BASIC INFO -->
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header border-bottom d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                        <h5 class="mb-0">BASIC INFORMATION</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="date" class="form-control" value="{{ old('date', $prescription->date) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Doctor</label>
                                            <select name="doctor_id" class="form-select">
                                                <option value="">Select Doctor</option>
                                                @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" {{ $prescription->doctor_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Patient</label>
                                            <select name="patient_id" class="form-select">
                                                <option value="">Select Patient</option>
                                                @foreach($patients as $patient)
                                                    <option value="{{ $patient->id }}" {{ $prescription->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control">{{ old('notes', $prescription->notes) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">History</label>
                                            <textarea name="history" class="form-control">{{ old('history', $prescription->history) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Advice</label>
                                            <textarea name="advice" class="form-control">{{ old('advice', $prescription->advice) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MEDICINE INFO -->
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header border-bottom d-flex align-items-center">
                                        <i class="bi bi-capsule-pill text-danger me-2"></i>
                                        <h5 class="mb-0">MEDICINE INFORMATION</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Select Medicines</label>
                                            <select name="medicines[]" id="medicines" class="form-select" multiple>
                                                @foreach($all_medicines as $medicine)
                                                    <option value="{{ $medicine->id }}" data-name="{{ $medicine->name }}" {{ in_array($medicine->id, $prescription->prescriptionMedicines->pluck('medicine_id')->toArray()) ? 'selected' : '' }}>{{ $medicine->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="medicine-details-wrapper">
                                            @foreach($prescription->prescriptionMedicines as $med)
                                                <div class="card p-2 mb-2 medicine-detail" data-id="{{ $med->medicine_id }}">
                                                    <input type="hidden" name="medicine_ids[]" value="{{ $med->medicine_id }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Medicine</label>
                                                            <input type="text" class="form-control" value="{{ $med->medicine->name }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Dosage</label>
                                                            <input type="text" name="medicine_details[{{ $med->medicine_id }}][dosage]" class="form-control" value="{{ $med->dosage }}">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Frequency</label>
                                                            <input type="text" name="medicine_details[{{ $med->medicine_id }}][frequency]" class="form-control" value="{{ $med->frequency }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Days</label>
                                                            <input type="text" name="medicine_details[{{ $med->medicine_id }}][days]" class="form-control" value="{{ $med->days }}">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <label class="form-label">Instructions</label>
                                                            <textarea name="medicine_details[{{ $med->medicine_id }}][instructions]" class="form-control" rows="2">{{ $med->instructions }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#medicines').select2({ placeholder: 'Select Medicines', width: '100%' });

        const wrapper = $('#medicine-details-wrapper');

        function renderMedicineDetail(medicineId, medicineName) {
            return `
            <div class="card p-2 mb-2 medicine-detail" data-id="${medicineId}">
                <input type="hidden" name="medicine_ids[]" value="${medicineId}">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Medicine</label>
                        <input type="text" class="form-control" value="${medicineName}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dosage</label>
                        <input type="text" name="medicine_details[${medicineId}][dosage]" class="form-control" placeholder="100 mg">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Frequency</label>
                        <input type="text" name="medicine_details[${medicineId}][frequency]" class="form-control" placeholder="1+0+1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Days</label>
                        <input type="text" name="medicine_details[${medicineId}][days]" class="form-control" placeholder="7 days">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label class="form-label">Instructions</label>
                        <textarea name="medicine_details[${medicineId}][instructions]" class="form-control" rows="2" placeholder="Instructions"></textarea>
                    </div>
                </div>
            </div>`;
        }

        $('#medicines').on('change', function () {
            const selected = $(this).val();
            const existing = [];

            wrapper.find('.medicine-detail').each(function () {
                const id = $(this).data('id').toString();
                if (!selected.includes(id)) {
                    $(this).remove();
                } else {
                    existing.push(id);
                }
            });

            $('#medicines option:selected').each(function () {
                const id = $(this).val();
                const name = $(this).data('name');
                if (!existing.includes(id)) {
                    wrapper.append(renderMedicineDetail(id, name));
                }
            });
        });
    });
</script>
@endpush
