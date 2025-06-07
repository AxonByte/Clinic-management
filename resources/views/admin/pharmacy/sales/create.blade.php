@extends('layouts.app')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h2>{{ isset($sale) ? 'Edit Sale' : 'New Sale' }}</h2>
                    </div>
                    <a class="btn btn-primary" href="{{ route('admin.pharmacy.sales.index') }}">Sales List</a>
                </div>
                <div class="card-body">
                    <form action="{{ isset($sale) ? route('admin.pharmacy.sales.update', $sale->id) : route('admin.pharmacy.sales.store') }}" method="POST" id="saleForm">
                        @csrf
                        @if(isset($sale)) @method('PUT') @endif
<div class="row">
    <div class="col-md-6">

                        <div class="mb-3">
                            <label>Select Item(s)</label>
                            <select id="itemSelect" class="form-control" name="medicines[]" multiple>
                                @foreach ($medicines as $item)
                                    <option 
                                        value="{{ $item->id }}"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-price="{{ $item->sale_price }}"
                                        data-stock="{{ $item->quantity }}"
                                        data-company="{{ $item->company ?? 'N/A' }}"
                                    >
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                         <input type="hidden" name="subtotal" id="subtotalInput">
                        <input type="hidden" name="total" id="totalInput">

                        <div class="form-group">
                            <label>Sub Total</label>
                            <input type="text" class="form-control" id="subtotal" readonly>
                        </div>

                        <div class="form-group">
                            <label>Discount</label>
                            <input type="number" class="form-control" name="discount" id="discount" value="{{ $sale->discount ?? 0 }}">
                        </div>

                        <div class="form-group">
                            <label>Total</label>
                            <input type="text" class="form-control" id="total" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
    </div>

    <div class="col-md-6">
         <div id="itemDetailsWrapper" class="row mb-3"></div>
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
     $('#itemSelect').select2({ placeholder: 'Select Medicines', width: '100%' });

    $('#itemSelect').on('change', function () {
        const selectedIds = $(this).val();
        const wrapper = $('#itemDetailsWrapper');
        wrapper.empty();

        selectedIds.forEach(id => {
            const option = $(`#itemSelect option[value="${id}"]`);
            const name = option.data('name');
            const price = option.data('price');
            const stock = option.data('stock');
            const company = option.data('company');

            wrapper.append(`
                <div class="col-md-12 item-box" data-id="${id}">
                    <div class="card mb-2">
                        <div class="card-body">
                            <p><strong>Name:</strong> ${name}</p>
                            <p><strong>Company:</strong> ${company}</p>
                            <p><strong>Price:</strong> ${price}</p>
                            <p><strong>Stock:</strong> ${stock}</p>
                           <input type="hidden" name="items[${id}][item_id]" value="${id}">
                            <input type="hidden" name="items[${id}][item_name]" value="${name}">
                            <input type="hidden" name="items[${id}][price]" value="${price}">
                            <input type="hidden" name="items[${id}][stock]" value="${stock}">
                            <label>Quantity</label>
                            <input type="number" class="form-control quantity" name="items[${id}][quantity]" data-price="${price}" value="1" min="1">
                        </div>
                    </div>
                </div>
            `);
        });

        calculateTotals();
    });

    $(document).on('input', '.quantity', function () {
        calculateTotals();
    });

    $('#discount').on('input', function () {
        calculateTotals();
    });

    function calculateTotals() {
        let subtotal = 0;
        $('.quantity').each(function () {
            const qty = parseInt($(this).val()) || 0;
            const price = parseFloat($(this).data('price')) || 0;
            subtotal += qty * price;
        });
        const discount = parseFloat($('#discount').val()) || 0;
        const total = subtotal - discount;

        $('#subtotal').val(subtotal);
        $('#total').val(total);

        $('#subtotalInput').val(subtotal);
        $('#totalInput').val(total);
    }
});
</script>
@endpush
