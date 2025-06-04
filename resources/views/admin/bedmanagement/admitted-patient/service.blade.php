@extends('layouts.app')

@section('content')
<div class="container mt-4">
   @include('admin.bedmanagement.admitted-patient.partials.tabs', ['active' => 'service', 'admission' => $admission])

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">service List</h5>

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Date</th>
                    <th>Nurse</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->created_at }}</td>
                        <td>{{ $service->nurse->name ?? '--' }}</td>
                        <td>{{ $service->service->name ?? '--' }}</td>
                        <td>{{ $service->sales_price }}</td>
                        <td>{{ $service->quantity }}</td>
                        <td>{{ $service->total }}</td>
                        <td>
                            <form action="{{ route('admin.bedmanagement.admission.service.delete', $service->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i>Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($services->isEmpty())
                    <tr><td colspan="8" class="text-center text-muted">No services added yet.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
</div>

<hr>

<!-- Add New service Form -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title text-center mb-4"><u>Add New service</u></h5>
        <form action="{{ route('admin.bedmanagement.admission.service.store', $admission->id) }}" method="POST">
            @csrf
            <input type="hidden" name="patient_admitted_id" value="{{ $admission->id }}">

            <div class="row g-2">
                <div class="col-md-12">
                    <label for="nurse_id" class="text-secondary">Nurse</label>
                    <select name="nurse_id" id="nurse_id" class="form-control" required>
                        <option value="">Select Nurse</option>
                            @foreach($nurses as $med)
                                <option value="{{ $med->id }}">{{ $med->name }}</option>
                            @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="serviceselect" class="text-secondary">Services</label>
                    <select name="service_id" id="serviceselect" class="form-control" required>
                        <option value="">Select service</option>
                            @foreach($servicesList as $med)
                                <option value="{{ $med->id }}" data-price="{{ $med->price }}">{{ $med->name }}</option>
                            @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="salesPrice" class="text-secondary">Price</label>
                    <input type="number" name="sales_price" id="salesPrice" class="form-control" placeholder="$" readonly>
                </div>
                <div class="col-md-3">
                    <label for="quantity" class="text-secondary">Quantity</label>
                    <input type="number" name="quantity" id="quantity"class="form-control" placeholder="Qty" required>
                </div>
                <div class="col-md-3">
                    <label for="totalAmount" class="text-secondary">Total</label>
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
    const serviceselect = document.getElementById('serviceselect');
    const salesPriceInput = document.getElementById('salesPrice');
    const quantityInput = document.getElementById('quantity');
    const totalInput = document.getElementById('totalAmount');
    const genNameInput = document.getElementById('genNameSelect'); // can be input or select

    serviceselect.addEventListener('change', function () {
        const selected = serviceselect.options[serviceselect.selectedIndex];
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
