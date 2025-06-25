<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/core/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.min.css?v=2.0.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.min.css?v=2.0.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/rtl.min.css') }}">
</head>
<body>

<div class="wrapper">
<section class="login-content">
    <div class="row m-0 align-items-center bg-white vh-100">            
        <div class="col-md-6">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card card-transparent shadow-none mb-0 auth-card">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/RCH_Logo.png')}}" alt="" width="150px" height="60px">
                            <h2 class="mb-2 text-center">Sign Up</h2>
                            <p class="text-center">Register your hospital and admin account.</p>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf

                                <div class="mb-3">
                                    <label for="hospital_name" class="form-label">Hospital Name</label>
                                    <input type="text" name="hospital_name" class="form-control" value="{{ old('hospital_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="admin_name" class="form-label">Admin Name</label>
                                    <input type="text" name="admin_name" class="form-control" value="{{ old('admin_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Admin Email</label>
                                    <input type="email" name="admin_email" class="form-control" value="{{ old('admin_email') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Password</label>
                                    <input type="password" name="admin_password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="admin_password_confirmation" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file" name="photo" class="form-control" accept="image/*">
                                </div>

                                <div class="mb-3">
                                    <label for="subscription_package" class="form-label">Select Subscription Package</label>
                                    <select name="subscription_package" id="subscription_package" class="form-select" required>
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

                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </div>

                                <div class="d-flex justify-content-center mt-3">
                                    <p>Already have an account? <a href="{{ route('login') }}" class="text-primary">Sign In</a></p>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
            <img src="{{ asset('assets/images/auth/01.png')}}" class="img-fluid gradient-main animated-scaleX" alt="images">
        </div>
    </div>
</section>
</div>

<!-- Scripts -->
<script>
    const packages = @json($packages);
    const modules = @json(\App\Models\Module::all()); // or pass $modules in controller
    const packageSelect = document.getElementById('subscription_package');
    const modulesList = document.getElementById('modules_list');

    packageSelect.addEventListener('change', function () {
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
            const packageModuleIds = selectedPackage?.module_ids?.split(',') || [];

            modules.forEach(mod => {
                const included = packageModuleIds.includes(mod.id.toString());
                const li = document.createElement('li');
                li.innerHTML = included
                    ? `<span style="color:green;">&#10004;</span> ${mod.name}`
                    : `<span style="color:red;">&#10008;</span> ${mod.name}`;
                modulesList.appendChild(li);
            });

            modulesList.style.opacity = 1;
        }, 250);
    });
</script>

<script src="{{ asset('assets/js/core/libs.min.js') }}"></script>
<script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>

</body>
</html>
