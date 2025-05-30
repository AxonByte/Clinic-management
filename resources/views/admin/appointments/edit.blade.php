@extends('layouts.app')
@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Edit Appointment</h4>
                    <a class="btn btn-primary" href="{{ route('admin.appointment.index') }}">Appointment List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.appointment.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Patient --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Patient</label>
                            <select name="patient_id" class="form-select" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Doctor and Visit Type --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Doctor</label>
                                <select name="doctor_id" id="doctor_id" class="form-select" required>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Visit Type</label>
                                <select name="visit_type" id="visit_type" class="form-select" required>
                                    @foreach($visitTypes as $vt)
                                        <option value="{{ $vt->id }}" {{ $appointment->visit_type_id == $vt->id ? 'selected' : '' }}>
                                            {{ $vt->visit_description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Date & Time Slot --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Appointment Date</label>
                                <input type="date" name="appointment_date" id="appointment_date" class="form-control" value="{{ $appointment->appointment_date }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Time Slot</label>
                                <select name="time_slot" id="time_slot" class="form-select" required>
                                    <option value="{{ $appointment->time_slot }}" selected>{{ $appointment->time_slot }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Payment --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Visit Charge</label>
                                <input type="text" name="visit_charges" id="visit_charge" class="form-control" value="{{ $appointment->visit_charges }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Discount</label>
                                <input type="number" name="discount" id="discount" class="form-control" value="{{ $appointment->discount ?? 0 }}" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Total Amount</label>
                            <input type="text" name="total_amount" id="total_amount" class="form-control" value="{{ $appointment->total_amount }}" readonly>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="pay_now" name="pay_now" {{ optional($appointment->payment)->status === 'paid' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="pay_now">PAY NOW</label>
                        </div>

                        <div class="mb-3" id="payment_mode_div" style="{{ optional($appointment->payment)->status === 'paid' ? '' : 'display:none;' }}">
                            <label class="form-label fw-bold">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-control">
                                <option value="cash" {{ optional($appointment->payment)->payment_mode === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ optional($appointment->payment)->payment_mode === 'card' ? 'selected' : '' }}>Card</option>
                            </select>

                        </div>

                        <div class="mt-4" id="card_fields" style="{{ optional($appointment->payment)->payment_mode === 'card' ? '' : 'display: none;' }}">
                            <label class="form-label fw-bold">Card Number</label>
                            <input type="text" name="card_number" class="form-control" value="{{ $appointment->payment->card_number ?? '' }}">

                            <label class="form-label fw-bold mt-2">Expiry Date</label>
                            <input type="text" name="expiry_date" class="form-control" value="{{ $appointment->payment->expiry_date ?? '' }}">

                            <label class="form-label fw-bold mt-2">CVV</label>
                            <input type="text" name="cvv" class="form-control" value="{{ $appointment->payment->cvv ?? '' }}">
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['Pending Confirmation', 'Confirmed', 'Treated', 'Cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $appointment->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes</label>
                            <textarea name="notes" class="form-control">{{ $appointment->notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Update Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('doctor_id').addEventListener('change', function () {
        const doctorId = this.value;
        const visitTypeSelect = document.getElementById('visit_type');
        visitTypeSelect.innerHTML = '<option value="">Loading...</option>';

        if (!doctorId) {
            visitTypeSelect.innerHTML = '<option value="">-- Select Visit Type --</option>';
            return;
        }

        fetch(`/admin/get-visit-types/${doctorId}`)
            .then(response => response.json())
            .then(data => {
                visitTypeSelect.innerHTML = '<option value="">-- Select Visit Type --</option>';
                if (data.visit_types.length > 0) {
                    data.visit_types.forEach(vt => {
                        const option = document.createElement('option');
                        option.value = vt.id;
                        option.textContent = vt.visit_description;
                        visitTypeSelect.appendChild(option);
                    });
                } else {
                    visitTypeSelect.innerHTML = '<option value="">No Visit Types Found</option>';
                }
            })
            .catch(error => {
                visitTypeSelect.innerHTML = '<option value="">Error loading visit types</option>';
                console.error('Error:', error);
            });
    });

document.getElementById('visit_type').addEventListener('change', function () {
    const doctorId = document.getElementById('doctor_id').value;
    const visitTypeId = this.value;
    const visitChargeInput = document.getElementById('visit_charge');
    const discountInput = document.getElementById('discount');
    const totalAmountInput = document.getElementById('total_amount');
    visitChargeInput.value = 'Loading...';

    if (doctorId && visitTypeId) {
        fetch(`/admin/get-visit-charge/${doctorId}/${visitTypeId}`)
            .then(res => res.json())
            .then(data => {
                visitChargeInput.value = data.visit_charges ? `${data.visit_charges}` : 'N/A';
                totalAmountInput.value = data.visit_charges ? `${data.visit_charges}` : 'N/A';
            });
    } else {
        visitChargeInput.value = '';
        totalAmountInput.value = '';
    }
  });
    
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const visitChargeInput = document.getElementById('visit_charge');
    const discountInput = document.getElementById('discount');
    const totalAmountInput = document.getElementById('total_amount');

    function calculateTotal() {
        const charge = parseFloat(visitChargeInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const total = Math.max(charge - discount, 0);
        totalAmountInput.value = total.toFixed(2);
    }

    // Update total when visit charge is fetched from server
    function setVisitChargeAndTotal(charge) {
        visitChargeInput.value = charge;
        discountInput.value = 0;
        totalAmountInput.value = charge.toFixed(2);
    }

    // Listen to manual changes
    discountInput.addEventListener('input', calculateTotal);
    visitChargeInput.addEventListener('input', calculateTotal); // optional if manual

    // Example: When loading charge from API
    window.setVisitChargeFromApi = function(charge) {
        setVisitChargeAndTotal(charge);
    };
});
</script>



<script>
document.getElementById('appointment_date').addEventListener('change', function () {
    const date = this.value;
    const doctorId = document.getElementById('doctor_id').value;
    const timeslot = document.getElementById('time_slot');
    timeslot.innerHTML = '<option value="">Loading...</option>';
    if (!doctorId || !date) return;

    fetch(`/admin/schedule/slots?doctor_id=${doctorId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            const timeSlotDropdown = document.getElementById('time_slot');
            timeSlotDropdown.innerHTML = '<option value="">-- Select Time Slot --</option>';
            data.slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                timeSlotDropdown.appendChild(option);
            });
        });
});
</script>

<script>
    const payNowCheckbox = document.getElementById('pay_now');
    const paymentModeDiv = document.getElementById('payment_mode_div');
    const paymentModeSelect = document.getElementById('payment_mode');
    const cardFields = document.getElementById('card_fields');

    payNowCheckbox.addEventListener('change', function () {
        paymentModeDiv.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            cardFields.style.display = 'none';
            paymentModeSelect.value = '';
        }
    });

    paymentModeSelect.addEventListener('change', function () {
        cardFields.style.display = this.value === 'card' ? 'block' : 'none';
    });
</script>
@endsection
