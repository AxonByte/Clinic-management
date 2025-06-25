@extends('superadmin.dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Edit Subscription Package</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the following errors:
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('superadmin.subscription_packages.update', $subscriptionPackage) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Package Name <span class="text-danger">*</span></label>
                        <input type="text" name="package_name" class="form-control" value="{{ old('package_name', $subscriptionPackage->package_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Duration (in Months) <span class="text-danger">*</span></label>
                        <select name="duration" class="form-select" required>
                            <option value="1" {{ old('duration', $subscriptionPackage->duration) == 1 ? 'selected' : '' }}>1 Month</option>
                            <option value="3" {{ old('duration', $subscriptionPackage->duration) == 3 ? 'selected' : '' }}>3 Months</option>
                            <option value="6" {{ old('duration', $subscriptionPackage->duration) == 6 ? 'selected' : '' }}>6 Months</option>
                            <option value="12" {{ old('duration', $subscriptionPackage->duration) == 12 ? 'selected' : '' }}>12 Months</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient Limit <span class="text-danger">*</span></label>
                        <input type="number" name="patient_limit" class="form-control" min="0" value="{{ old('patient_limit', $subscriptionPackage->patient_limit) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doctor Limit <span class="text-danger">*</span></label>
                        <input type="number" name="doctor_limit" class="form-control" min="0" value="{{ old('doctor_limit', $subscriptionPackage->doctor_limit) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Original Price ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="original_price" class="form-control" min="0" value="{{ old('original_price', $subscriptionPackage->original_price) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Discounted Price ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="discounted_price" class="form-control" min="0" value="{{ old('discounted_price', $subscriptionPackage->discounted_price) }}" required>
                    </div>
                </div>

                @php
                    $selectedModules = explode(',', old('module_ids', $subscriptionPackage->module_ids));
                @endphp
                <div class="mb-3">
                    <label class="form-label">Modules <span class="text-danger">*</span></label>
                    <div class="row">
                        @foreach($modules as $module)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="module_ids[]"
                                        id="module_{{ $module->id }}"
                                        value="{{ $module->id }}"
                                        {{ in_array($module->id, $selectedModules) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="module_{{ $module->id }}">
                                        {{ $module->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="show_in_frontend" value="1" id="showFrontend" {{ old('show_in_frontend', $subscriptionPackage->show_in_frontend) ? 'checked' : '' }}>
                        <label class="form-check-label" for="showFrontend">Show in Frontend</label>
                    </div>
                    <div class="col-md-6 form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_recommended" value="1" id="isRecommended" {{ old('is_recommended', $subscriptionPackage->is_recommended) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isRecommended">Recommended Package</label>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Update Package</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
