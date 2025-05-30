<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Rich Care</title>
      
      <!-- Favicon -->
     <link rel="shortcut icon" href="{{ asset('assets/images/RCH_Logo.png') }}" height="60px" />

      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="{{ asset('assets/css/core/libs.min.css')}}" />
      
      <!-- Aos Animation Css -->
      <link rel="stylesheet" href="{{ asset('assets/vendor/aos/dist/aos.css')}}" />
      
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
      
      {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" /> --}}
      <style>
        body { margin: 0; height: 100%; overflow-x: hidden;
    overflow-y: auto;}
        .main { flex: 1; display: flex; }
        /* .main-content { flex: 1; padding: 20px; overflow-y: auto; } */
    </style>
       <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />

  </head>

<body>
<div id="alertBox"
     class="alert d-none position-fixed"
     style="top: 20px; right: 20px; z-index: 9999; min-width: 250px; border-radius: 5px;"
     role="alert">
</div>

   <!-- Loader END -->

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Navbar -->
        @include('partials.header')

        <!-- Page Content -->
        <div class="container-fluid mt-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
         @include('partials.footer')
    </main>
<!-- Order matters -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>


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
    <script src="{{ asset('assets/vendor/aos/dist/aos.js')}}"></script>
    
    <!-- App Script -->
    <script src="{{ asset('assets/js/hope-ui.js')}}" defer></script>

{{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
<script>
function showMessage(type, message) {
    const alertBox = $('#alertBox');
    alertBox.removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert')
            .text(message);
    if (type === 'success') {
        alertBox.css({
            'background-color': '#11af0d', // dark green
            'color': '#fff',
            'border': '1px solid #0f3d2e',
            'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'
        });
    } else if (type === 'danger') {
        alertBox.css({
            'background-color': '#721c24',
            'color': '#fff',
            'border': '1px solid #f5c6cb',
            'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'
        });
    }
    alertBox.removeClass('d-none').fadeIn();
    setTimeout(() => {
        alertBox.fadeOut(400, function () {
            $(this).addClass('d-none');
        });
    }, 5000);
}

</script>
</body>
</html>
