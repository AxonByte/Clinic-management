@extends('layouts.app')
@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Edit Medicine</h4>
                    </div>
                    <a class="btn btn-primary" href="{{ route('admin.medicine.index')}}">Medicine List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.medicine.update', $medicine->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                    <div class="row mb-3">
                       <div class="col-md-6">
                            <label class="text-secondary mb-1">NAME <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $medicine->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">GENERIC NAME <span class="text-danger">*</span></label>
                            <input type="text" name="generic_name" class="form-control" value="{{ old('generic_name', $medicine->generic_name ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">CATEGORY <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $medicine->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">COMPANY</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $medicine->company ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">PURCHASE PRICE <span class="text-danger">*</span></label>
                            <input type="text" name="purchase_price" class="form-control" value="{{ old('purchase_price', $medicine->purchase_price ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">SALE PRICE <span class="text-danger">*</span></label>
                            <input type="text" name="sale_price" class="form-control" value="{{ old('sale_price', $medicine->sale_price ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">EFFECTS</label>
                            <input type="text" name="effects" class="form-control" value="{{ old('effects', $medicine->effects ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">STORE BOX</label>
                            <input type="text" name="store_box" class="form-control" value="{{ old('store_box', $medicine->store_box ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">QUANTITY <span class="text-danger">*</span></label>
                            <input type="text" name="quantity" class="form-control" value="{{ old('quantity', $medicine->quantity ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">EXPIRY DATE <span class="text-danger">*</span></label>
                            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', isset($medicine->expiry_date) ? $medicine->expiry_date : '') }}" required>
                        </div>
                    </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
