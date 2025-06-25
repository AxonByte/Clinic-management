@extends('superadmin.dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5>Create Hospital</h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">
            <form action="{{ route('superadmin.hospitals.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="hospital_name" class="form-label">Hospital Name</label>
                        <input type="text" id="hospital_name" name="hospital_name" class="form-control" value="{{ old('hospital_name') }}" required autocomplete="off">
                    </div>

                    <div class="col-md-6">
                        <label for="admin_name" class="form-label">Admin Name</label>
                        <input type="text" id="admin_name" name="admin_name" class="form-control" value="{{ old('admin_name') }}" required autocomplete="off">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="admin_email" class="form-label">Admin Email</label>
                        <input type="email" id="admin_email" name="admin_email" class="form-control" value="{{ old('admin_email') }}" required autocomplete="email">
                    </div>

                    <div class="col-md-6">
                        <label for="admin_password" class="form-label">Admin Password</label>
                        <input type="password" id="admin_password" name="admin_password" class="form-control" required autocomplete="new-password">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" autocomplete="off">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="2" autocomplete="off">{{ old('address') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="2" autocomplete="off">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="subscription_package" class="form-label">Select Subscription Package</label>
                    <select name="subscription_package" id="subscription_package" class="form-select" required autocomplete="off">
                        <option value="">-- Select Package --</option>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}" {{ old('subscription_package') == $package->id ? 'selected' : '' }}>
                                {{ $package->package_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <h6>Package Modules:</h6>
                    <ul id="modules_list" style="list-style-type:none; padding-left: 0; opacity: 0; transition: opacity 0.5s ease;">
                        <li>Select a package to see modules</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-success">Create Hospital</button>
            </form>
        </div>
    </div>

</div>

<script>
    const packages = @json($packages);
    const modules = @json($modules);

    const packageSelect = document.getElementById('subscription_package');
    const modulesList = document.getElementById('modules_list');

    packageSelect.addEventListener('change', function() {
        const packageId = this.value;
        modulesList.style.opacity = 0;

        setTimeout(() => {
            modulesList.innerHTML = '';

            if (!packageId) {
                modulesList.innerHTML = '<li>Select a package to see modules</li>';
                modulesList.style.opacity = 1;
                return;
            }

            const selectedPackage = packages.find(pkg => pkg.id == packageId);
            if (!selectedPackage) {
                modulesList.innerHTML = '<li>No package found</li>';
                modulesList.style.opacity = 1;
                return;
            }

            const packageModuleIds = selectedPackage.module_ids ? selectedPackage.module_ids.split(',').map(id => id.trim()) : [];

            modules.forEach(mod => {
                const included = packageModuleIds.includes(mod.id.toString());

                const li = document.createElement('li');
                li.style.marginBottom = '5px';
                li.style.fontSize = '16px';
                li.innerHTML = included 
                    ? `<span style="color:green;">&#10004;</span> ${mod.name}`   
                    : `<span style="color:red;">&#10008;</span> ${mod.name}`;

                modulesList.appendChild(li);
            });

            modulesList.style.opacity = 1;
        }, 250);
    });
</script>
@endsection
