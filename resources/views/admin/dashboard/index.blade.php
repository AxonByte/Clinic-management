{{-- @extends('admin.dashboard.layouts.app')

@section('content')
  <!-- Container -->
<div class="container py-4">
  <!-- Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
      <h2 class="fw-bold">Welcome, Hospital Admin!</h2>
      <p class="text-muted">Welcome to the dashboard of Hospital</p>
    </div>
    <div class="d-flex gap-2 mt-3 mt-md-0">
      <button class="btn btn-primary">+ New Invoice</button>
      <button class="btn btn-success">+ Add Patient</button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-4">
    <!-- Total Bill -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title text-muted">Total Bill</h6>
          <h4 class="fw-bold text-primary">$46.06K</h4>
          <small class="text-danger">▼ -27.1%</small>
        </div>
      </div>
    </div>

    <!-- Total Deposit -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title text-muted">Total Deposit</h6>
          <h4 class="fw-bold text-success">$25.72K</h4>
          <small class="text-danger">▼ -79.3%</small>
        </div>
      </div>
    </div>

    <!-- Pending -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title text-muted">Pending</h6>
          <h4 class="fw-bold text-warning">$20.34K</h4>
          <small class="text-success">▲ +100.00%</small>
        </div>
      </div>
    </div>

    <!-- Total Expense -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title text-muted">Total Expense</h6>
          <h4 class="fw-bold text-info">$20.00</h4>
          <small class="text-danger">▼ -69.7%</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row g-4 mt-3">
    <!-- Top Services -->
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">Top Services</h6>
            <button class="btn btn-sm btn-light">Last 30 days</button>
          </div>
          <!-- Replace with actual chart -->
          <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
            <img src="path-to-your-chart.png" class="img-fluid" alt="Top Services Pie Chart">
          </div>
        </div>
      </div>
    </div>

    <!-- Top Diagnoses -->
    <div class="col-lg-3 col-md-3">
      <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0 fw-bold">Top Diagnoses</h6>
          <span class="badge bg-secondary">Last 30 days</span>
        </div>
        <canvas id="diagnosesChart" height="150"></canvas>
      </div>
    </div>

    
    <!-- Bed Occupancy -->
    <div class="col-12 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-2">Bed Occupancy</h6>
          <div class="alert alert-danger p-2 mb-0">
            ❗ Negative values are invalid for a pie chart.
          </div>
        </div>
      </div>
    </div>
    
  </div>

  <div class="row g-4 mt-3">

    <!-- Sales vs Expenses -->
    <div class="col-lg-6 col-md-12">
      <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0 fw-bold">Sales vs Expenses</h6>
          <button class="btn btn-sm btn-secondary">This Year</button>
        </div>
        <canvas id="salesChart" height="100px"></canvas>
      </div>
    </div>

    <!-- Top Treatments -->
    <div class="col-lg-3 col-md-6">
      <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0 fw-bold">Top Treatments</h6>
          <span class="badge bg-secondary">Last 30 days</span>
        </div>
        <canvas id="treatmentsChart" height="150"></canvas>
      </div>
    </div>

    <!-- Disease Outbreak Alerts -->
    <div class="col-lg-3 col-md-6">
      <div class="card p-3">
        <h6 class="fw-bold mb-3">Disease Outbreak Alerts</h6>
        <p class="text-muted mb-2">Last 7 days</p>
        <div class="d-flex justify-content-between mb-1">
          <span>Dengue</span>
          <span class="badge bg-success">Under Control</span>
        </div>
        <div class="d-flex justify-content-between mb-1">
          <span>Fever</span>
          <span class="badge bg-success">Under Control</span>
        </div>
        <div class="d-flex justify-content-between mb-1">
          <span>as</span>
          <span class="badge bg-success">Under Control</span>
        </div>
      </div>
    </div>
  </div>

</div>
<script>
  // Sales vs Expenses Chart
  new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [
        {
          label: 'Sales',
          data: [0, 67000, 70000, 45000, 0],
          fill: true,
          backgroundColor: 'rgba(255, 165, 0, 0.2)',
          borderColor: 'orange',
          tension: 0.4
        },
        {
          label: 'Expenses',
          data: [0, 2000, 1800, 2200, 2300],
          fill: false,
          borderColor: 'orangered',
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'right' } }
    }
  });

  // Top Diagnoses Chart
  new Chart(document.getElementById('diagnosesChart'), {
    type: 'bar',
    data: {
      labels: ['F-', 'G-', 'D-', 'D-', 'O-'],
      datasets: [{
        label: 'Patients',
        data: [7, 3, 3, 2, 1],
        backgroundColor: 'cornflowerblue'
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Top Treatments Chart
  new Chart(document.getElementById('treatmentsChart'), {
    type: 'bar',
    data: {
      labels: ['Medication', 'Taffs'],
      datasets: [{
        label: 'Patients',
        data: [8, 5],
        backgroundColor: 'lightcoral'
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { x: { beginAtZero: true } }
    }
  });
</script>
<style>
    .card {
      border-radius: 1rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    canvas {
      width: 100% !important;
    }
  </style>
@endsection --}}
@extends('layouts.app')

@section('content')

<div class="conatiner-fluid content-inner py-0">
   <div class="d-flex justify-content-end mb-3">
               <div class="header-title">
                 
               </div>
                <a href="#" class="btn btn-outline-primary" style="margin-right: 3px;">+ Add Invoice</a>
                <a href="#" class="btn btn-outline-success">+ Add Patient</a>
   </div>
<div class="row">
   <div class="col-md-12 col-lg-12">
      <div class="row row-cols-1">
         <div class="overflow-hidden d-slider1 ">
            <ul  class="p-0 m-0 mb-2 swiper-wrapper list-inline">
               <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                  <div class="card-body">
                     <div class="progress-widget">
                        <div id="circle-progress-01" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="90" data-type="percent">
                           <svg class="card-slie-arrow icon-24" width="24"  viewBox="0 0 24 24">
                              <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                           </svg>
                        </div>
                        <div class="progress-detail">
                           <p  class="mb-2">Total Sales</p>
                           <h4 class="counter">$560K</h4>
                        </div>
                     </div>
                  </div>
               </li>
               <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
                  <div class="card-body">
                     <div class="progress-widget">
                        <div id="circle-progress-02" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="80" data-type="percent">
                           <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                           </svg>
                        </div>
                        <div class="progress-detail">
                           <p  class="mb-2">Total Profit</p>
                           <h4 class="counter">$185K</h4>
                        </div>
                     </div>
                  </div>
               </li>
               <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
                  <div class="card-body">
                     <div class="progress-widget">
                        <div id="circle-progress-03" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="70" data-type="percent">
                           <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                           </svg>
                        </div>
                        <div class="progress-detail">
                           <p  class="mb-2">Total Cost</p>
                           <h4 class="counter">$375K</h4>
                        </div>
                     </div>
                  </div>
               </li>
               <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
                  <div class="card-body">
                     <div class="progress-widget">
                        <div id="circle-progress-04" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="60" data-type="percent">
                           <svg class="card-slie-arrow icon-24" width="24px"  viewBox="0 0 24 24">
                              <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                           </svg>
                        </div>
                        <div class="progress-detail">
                           <p  class="mb-2">Revenue</p>
                           <h4 class="counter">$742K</h4>
                        </div>
                     </div>
                  </div>
               </li>
               <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1100">
                  <div class="card-body">
                     <div class="progress-widget">
                        <div id="circle-progress-05" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="50" data-type="percent">
                           <svg class="card-slie-arrow icon-24" width="24px"  viewBox="0 0 24 24">
                              <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                           </svg>
                        </div>
                        <div class="progress-detail">
                           <p  class="mb-2">Net Income</p>
                           <h4 class="counter">$150K</h4>
                        </div>
                     </div>
                  </div>
               </li>
               <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1200">
                  <div class="card-body">
                     <div class="progress-widget">
                        <div id="circle-progress-06" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="40" data-type="percent">
                           <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                           </svg>
                        </div>
                        <div class="progress-detail">
                           <p  class="mb-2">Today</p>
                           <h4 class="counter">$4600</h4>
                        </div>
                     </div>
                  </div>
               </li>
               <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1300">
                  <div class="card-body">
                     <div class="progress-widget">
                        <div id="circle-progress-07" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="30" data-type="percent">
                           <svg class="card-slie-arrow icon-24 " width="24" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                           </svg>
                        </div>
                        <div class="progress-detail">
                           <p  class="mb-2">Members</p>
                           <h4 class="counter">11.2M</h4>
                        </div>
                     </div>
                  </div>
               </li>
            </ul>
            <div class="swiper-button swiper-button-next"></div>
            <div class="swiper-button swiper-button-prev"></div>
         </div>
      </div>
   </div>
</div>
      </div>

   
@endsection

