@extends('superadmin.dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5>Edit Service</h5>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the errors:
                    <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('superadmin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Name <span class="text-danger">*</span></label>
                        <input name="name" type="text" class="form-control" value="{{ old('name', $service->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Image</label>
                        <input name="image" type="file" class="form-control">
                        @if ($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" alt="Service Image" width="100" class="mt-2">
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $service->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Subcategory</label>
                        <select name="subcategory_id" class="form-select">
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategory_id', $service->subcategory_id) == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Service Duration (minutes)</label>
                        <input name="service_duration" type="number" class="form-control" min="1" value="{{ old('service_duration', $service->service_duration) }}">
                    </div>
                    <div class="col-md-6">
                        <label>Price ($) <span class="text-danger">*</span></label>
                        <input name="default_price" type="number" step="0.01" class="form-control" min="0" value="{{ old('default_price', $service->default_price) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Service Type <span class="text-danger">*</span></label>
                        <select name="service_type" class="form-select" required>
                            <option value="online" {{ old('service_type', $service->service_type) == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="inclinic" {{ old('service_type', $service->service_type) == 'inclinic' ? 'selected' : '' }}>In-clinic</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $service->description) }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input name="has_discount" type="checkbox" class="form-check-input" id="hasDiscount"
                        {{ old('has_discount', $service->has_discount) ? 'checked' : '' }}>
                    <label class="form-check-label" for="hasDiscount">Has Discount?</label>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Discount Type</label>
                        <select name="discount_type" class="form-select">
                            <option value="fixed" {{ old('discount_type', $service->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="percent" {{ old('discount_type', $service->discount_type) == 'percent' ? 'selected' : '' }}>Percent</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Discount Value</label>
                        <input name="discount_value" type="number" step="0.01" class="form-control" min="0" value="{{ old('discount_value', $service->discount_value) }}">
                    </div>
                </div>

                <div class="d-grid">
                    <button class="btn btn-success">Update Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
