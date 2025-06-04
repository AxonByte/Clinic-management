@extends('layouts.app')

@section('content')
<div class="container mt-4">
   @include('admin.bedmanagement.admitted-patient.partials.tabs', ['active' => 'medicines', 'admission' => $admission])

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">Medicine List</h5>

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Date</th>
                    <th>Medicine Gen Name</th>
                    <th>Medicine</th>
                    <th>Sales</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->created_at }}</td>
                        <td>{{ $medicine->generic_name ?? '--' }}</td>
                        <td>{{ $medicine->medicine->name ?? '--' }}</td>
                        <td>{{ $medicine->sales_price }}</td>
                        <td>{{ $medicine->quantity }}</td>
                        <td>{{ $medicine->total }}</td>
                        <td>
                            <form action="{{ route('admin.bedmanagement.admission.medicines.delete', $medicine->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i>Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($medicines->isEmpty())
                    <tr><td colspan="8" class="text-center text-muted">No medicines added yet.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
</div>

<hr>

<!-- Add New Medicine Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title text-center mb-4"><u>Add New Medicine</u></h5>
        <form action="{{ route('admin.bedmanagement.admission.medicines.store', $admission->id) }}" method="POST">
            @csrf
            <input type="hidden" name="patient_admitted_id" value="{{ $admission->id }}">

            <div class="row g-2">
                
                <div class="col-md-3">
                    <select name="brand_name" id="medicineSelect" class="form-control" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicinesList as $med)
                                <option value="{{ $med->id }}" data-price="{{ $med->sale_price }}" data-gen="{{ $med->generic_name ?? '' }}">{{ $med->name }}</option>
                        
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="genNameSelect" name="generic_name" class="form-control" readonly placeholder="Generic Name">
                </div>

                <div class="col-md-1">
                    <input type="number" name="sales_price" id="salesPrice" class="form-control" placeholder="$" readonly>
                </div>
                <div class="col-md-1">
                    <input type="number" name="quantity" id="quantity"class="form-control" placeholder="Qty" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="total" id="totalAmount" class="form-control" placeholder="Total" readonly>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const medicineSelect = document.getElementById('medicineSelect');
    const salesPriceInput = document.getElementById('salesPrice');
    const quantityInput = document.getElementById('quantity');
    const totalInput = document.getElementById('totalAmount');
    const genNameInput = document.getElementById('genNameSelect'); // can be input or select

    medicineSelect.addEventListener('change', function () {
        const selected = medicineSelect.options[medicineSelect.selectedIndex];
        const price = parseFloat(selected.getAttribute('data-price')) || 0;
        const genName = selected.getAttribute('data-gen') || '';

        salesPriceInput.value = price.toFixed(2);
        quantityInput.value = 1;
        totalInput.value = price.toFixed(2);

        if (genNameInput) {
            genNameInput.value = genName;
        }
    });

    quantityInput.addEventListener('input', function () {
        const price = parseFloat(salesPriceInput.value) || 0;
        const qty = parseInt(quantityInput.value) || 0;
        totalInput.value = (price * qty).toFixed(2);
    });
});

</script>

@endPush
