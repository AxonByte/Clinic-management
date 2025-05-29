@extends('layouts.app')
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Appointment</h4>
                            </div>
                            <a class="btn btn-primary" href="{{ route('admin.appointment.index')}}">Appointment List</a>
                        </div>
                        <hr style="color: blue;">
                        
                    <div class="card-body">
                        <form action="{{ route('admin.appointment.store') }}" method="POST">
                            @csrf

                            {{-- Patient Details --}}
                            <h5><i class="fas fa-user"></i> PATIENT DETAILS</h5>
                            <div style="height: 1px; background-color: hsl(236, 82%, 69%); margin: 20px 0;"></div>
                            <div class="card p-3">
                            <div class="mb-3">
                                <label for="patient_id" class="form-label fw-bold">Select Patient <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select" required>
                                    <option value="">-- Select Patient --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>

                            {{-- Appointment Details --}}
                            <h5 class="mt-4"><i class="fas fa-calendar-check"></i> APPOINTMENT DETAILS</h5>
                            <div style="height: 1px; background-color: hsl(120, 80%, 46%); margin: 20px 0;"></div>
                        <div class="card p-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="doctor_id" class="form-label fw-bold">Select Doctor <span class="text-danger">*</span></label>
                                    <select name="doctor_id" id="doctor_id" class="form-select" required>
                                        <option value="">-- Select Doctor --</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="visit_type" class="form-label fw-bold">Visit Type <span class="text-danger">*</span></label>
                                    <select name="visit_type" id="visit_type" class="form-select" required>
                                        <option value="">-- Select Visit Type --</option>
                                        
                                    </select>
                                </div>
                            </div>

                            {{-- Appointment Date & Time --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="appointment_date" class="form-label fw-bold">Appointment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                   <label for="time_slot" class="form-label">Available Time Slots <span class="text-danger">*</span></label>
                                    <select class="form-select" id="time_slot" name="time_slot" required>
                                        <option value="">-- Select Time Slot --</option>
                                    </select>
                                </div>
                            </div>
                            </div>

                            <h5 class="mt-4"><i class="fas fa-calendar-check"></i> PAYMENT DETAILS</h5>
                            <div style="height: 1px; background-color: hsl(14, 80%, 46%); margin: 20px 0;"></div>
                        <div class="card p-3">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="visit_charge" class="form-label fw-bold">Visit Charges</label>
                                    <input type="text" name="visit_charge" class="form-control" id="visit_charge" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="discount" class="form-label fw-bold">Discount</label>
                                    <input type="number" name="discount" id="discount" class="form-control" value="0" min="0">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="total_amount" class="form-label fw-bold">Total Amount</label>
                                    <input type="text" name="total_amount" class="form-control" id="total_amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pay_now" name="pay_now">
                                <label class="form-check-label fw-bold" for="pay_now">PAY NOW</label>
                            </div>
                            <small class="form-text text-muted">
                                If pay now checked, please select status to confirmed
                            </small>
                        </div>
                        <div class="form-group mt-2" id="payment_mode_div" style="display: none;">
                            <label for="payment_mode" class="form-label fw-bold">Payment Mode <span class="text-danger">*</span></label>
                            <select class="form-control form-control-lg shadow-sm" id="payment_mode" name="payment_mode">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                            </select>
                        </div>

                        <div class="mt-4" id="card_fields" style="display: none;">
                            <label class="form-label fw-bold">Accepted Cards</label><br>
                            <img src="https://img.icons8.com/color/48/000000/visa.png" alt="Visa" />
                            <img src="https://img.icons8.com/color/48/000000/mastercard.png" alt="MasterCard" />
                            <img src="https://img.icons8.com/color/48/000000/discover.png" alt="Discover" />
                            <img src="https://img.icons8.com/color/48/000000/amex.png" alt="Amex" />

                            <div class="form-group mt-3">
                                <label for="card_number" class="form-label fw-bold">Card Number</label>
                                <input type="text" class="form-control form-control-lg shadow-sm" id="card_number" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX">
                            </div>

                            <div class="form-group mt-2">
                                <label for="expiry_date" class="form-label fw-bold">Expiry Date</label>
                                <input type="text" class="form-control form-control-lg shadow-sm" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                            </div>

                            <div class="form-group mt-2">
                                <label for="cvv" class="form-label fw-bold">CVV</label>
                                <input type="text" class="form-control form-control-lg shadow-sm" id="cvv" name="cvv" placeholder="XXX">
                            </div>
                        </div>


                            {{-- Notes --}}
                            <div class="mb-3">
                                <label for="notes" class="form-label fw-bold">Status</label>
                                 <select name="status" id="status" class="form-control form-control-lg shadow-sm">
                                    <option value="Pending Confirmation">Pending Confirmation</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Treated">Treated</option>
                                    <option value="Cancelled">Cancelled</option>
                                 </select>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label fw-bold">Additional Notes</label>
                                <textarea name="notes" class="form-control form-control-lg shadow-sm" rows="3" placeholder="Additional details..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Book Appointment
                            </button>
                        </form>
                    </div>
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
