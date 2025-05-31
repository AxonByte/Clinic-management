{{-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hospital Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #e8edf5;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .login-box {
      background: #fff;
      border-radius: 15px;
      padding: 30px 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    .logo img {
      width: 80px;
      border-radius: 50%;
    }
    .logo h4 {
      margin-top: 10px;
      font-weight: bold;
    }
    .form-control:focus {
      box-shadow: none;
    }
  </style>
</head>
<body>
  @if (session('error'))
  <div class="alert alert-danger">
      {{ session('error') }}
  </div>
@endif
<!-- Logo (outside box) -->
<div class="logo d-flex align-items-center justify-content-center mb-4">
  <img src="{{ asset('images/2037187.png') }}" alt="Hospital Logo">
  <h4>Hospital</h4>
</div>

<!-- Login Box -->
<div class="login-box">
 

  <h6 class="text-center text-muted mb-4">
    <i class="bi bi-box-arrow-in-right"></i> Sign in to start your session
  </h6>
  <form action="{{ route('login.post')}}" method="post">
    @csrf
    <div class="mb-3 input-group">
      <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
      <input type="email" class="form-control" placeholder="Email" name="email" required>
    </div>
    <div class="mb-3 input-group">
      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
      <input type="password" class="form-control" placeholder="Password" name="password" required>
    </div>
    <div class="d-grid mb-3">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
      </button>
    </div>
    <div class="text-center text-muted mb-2">or</div>
    <div class="text-center">
      <a href="#" class="text-decoration-none">
        <i class="bi bi-key"></i> Forgot Password?
      </a>
    </div>
  </form>
</div>

<!-- Bootstrap JS & Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html> --}}


<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Login</title>
      
      <!-- Favicon -->
       <link rel="shortcut icon" href="{{ asset('assets/images/RCH_Logo.png') }}" height="60px" />
      
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="{{ asset('assets/css/core/libs.min.css') }}" />
      
      
      <!-- Hope Ui Design System Css -->
      <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.min.css?v=2.0.0')}}" />
      
      <!-- Custom Css -->
      <link rel="stylesheet" href="{{ asset('assets/css/custom.min.css?v=2.0.0')}}" />
      
      <!-- Dark Css -->
      <link rel="stylesheet" href="{{ asset('assets/css/dark.min.css')}}"/>
      
      <!-- Customizer Css -->
      <link rel="stylesheet" href="{{ asset('assets/css/customizer.min.css')}}" />
      
      <!-- RTL Css -->
      <link rel="stylesheet" href="{{ asset('assets/css/rtl.min.css')}}"/>
      
      
  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body"></div>
      </div>    </div>
    <!-- loader END -->
    
      <div class="wrapper">
      <section class="login-content">
         <div class="row m-0 align-items-center bg-white vh-100">            
            <div class="col-md-6">
               <div class="row justify-content-center">
                  <div class="col-md-10">
                     <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                        <div class="card-body">
                          <img src="{{ asset('assets/images/RCH_Logo.png')}}" alt="" width="150px" height="60px">
                           <h2 class="mb-2 text-center">Sign In</h2>
                           <p class="text-center">Login to stay connected.</p>
                           @if(Session::has('error'))
                              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                 {{ Session::get('error') }}
                                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>
                           @endif
                            <form action="{{ route('login.post')}}" method="post">
                              @csrf
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="email" class="form-label fw-bold text-secondary">Email</label>
                                       <input type="email" class="form-control" placeholder="Email" name="email" required>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="password" class="form-label fw-bold text-secondary">Password</label>
                                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                                    </div>
                                 </div>
                                 <div class="col-lg-12 d-flex justify-content-between">
                                    
                                    <a href="recoverpw.html">Forgot Password?</a>
                                 </div>
                              </div>
                              <div class="d-flex justify-content-center">
                                 <button type="submit" class="btn btn-primary">Sign In</button>
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
    
    <!-- Library Bundle Script -->
    <script src="{{ asset('assets/js/core/libs.min.js')}}"></script>
    
    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/js/core/external.min.js')}}"></script>
    
    <!-- Widgetchart Script -->
    <script src="{{ asset('assets/js/charts/widgetcharts.js')}}"></script>
    
    <!-- mapchart Script -->
    <script src="{{ asset('assets/js/charts/vectore-chart.js')}}"></script>
    <script src="{{ asset('assets/js/charts/dashboard.js')}}" ></script>
    
    <!-- fslightbox Script -->
    <script src="{{ asset('assets/js/plugins/fslightbox.js')}}"></script>
    
    <!-- Settings Script -->
    <script src="{{ asset('assets/js/plugins/setting.js')}}"></script>
    
    <!-- Slider-tab Script -->
    <script src="{{ asset('assets/js/plugins/slider-tabs.js')}}"></script>
    
    <!-- Form Wizard Script -->
    <script src="{{ asset('assets/js/plugins/form-wizard.js')}}"></script>
    
    <!-- AOS Animation Plugin-->
    
    <!-- App Script -->
    <script src="{{ asset('assets/js/hope-ui.js')}}" defer></script>
    
  </body>
</html>