<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Hospital Admin Dashboard')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    .wrapper {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    /* Sidebar */
    #sidebar {
      width: 250px;
      background-color:rgb(236, 232, 232);
      border-right: 1px solid #ddd;
      padding-top: 1rem;
      flex-shrink: 0;
      overflow-y: auto;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1030;
    }
    #sidebar .nav-link:hover {
        background: #ffffff;
        font-weight: 700;
        color: #000;
        }


    #sidebar .nav-link {
      color: #333;
      font-weight: 500;
    }
    #sidebar .nav-link {
        color: #333;
        font-weight: 500;
        transition: background-color 0.3s ease, font-weight 0.3s ease, color 0.3s ease;
        }

      


    #sidebar .nav-link.active {
      background: #e9ecef;
      font-weight: 700;
    }

    /* Content area */
    .content-wrapper {
      margin-left: 250px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    header {
      height: 56px;
      background-color: #ffffff;
      border-bottom: 1px solid #ddd;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      flex-shrink: 0;
    }

    .search-box {
      display: flex;
      align-items: center;
      border: 1px solid #ddd;
      padding: 5px 10px;
      border-radius: 5px;
      width: 250px;
    }

    .search-box i {
      color: #aaa;
      margin-right: 8px;
    }

    .search-box input {
      border: none;
      outline: none;
      width: 100%;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .notification-icon {
      position: relative;
    }

    .notification-count {
      position: absolute;
      top: -6px;
      right: -10px;
      background-color: limegreen;
      color: white;
      font-size: 10px;
      padding: 2px 5px;
      border-radius: 50%;
    }

    .profile img {
      border-radius: 50%;
      width: 30px;
      height: 30px;
    }

    main {
      flex-grow: 1;
      padding: 20px;
      overflow-y: auto;
      background: #f8f9fa;
    }

    footer {
      height: 40px;
      background-color: #f1f1f1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      color: #555;
      flex-shrink: 0;
    }
    .sidebar-logo img {
        max-height: 50px;
        object-fit: contain;
        }


    @media (max-width: 768px) {
      #sidebar {
        left: -250px;
        transition: left 0.3s ease;
      }

      #sidebar.show {
        left: 0;
      }

      .content-wrapper {
        margin-left: 0;
      }

      .sidebar-toggle-btn {
        display: inline-block;
        margin-right: 10px;
        font-size: 1.25rem;
        cursor: pointer;
      }

      .search-box {
        width: 100%;
      }

      .header-right {
        gap: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    {{-- Sidebar --}}
    <nav id="sidebar">

    <div class="text-center sidebar-logo mb-3 px-3 d-flex align-items-center  mb-4">
        <img src="{{ asset('images/2037187.png') }}" alt="Hospital Logo">
        <h4>Hospital</h4>
    </div>
    <hr>
      @section('sidebar')
        <ul class="nav flex-column px-3">
          <li class="nav-item">
            <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="{{ url('patients') }}" class="nav-link {{ request()->is('patients') ? 'active' : '' }}">Patients</a>
          </li>
          <li class="nav-item">
            <a href="{{ url('appointments') }}" class="nav-link {{ request()->is('appointments') ? 'active' : '' }}">Appointments</a>
          </li>
          <li class="nav-item">
            <a href="{{ url('doctors') }}" class="nav-link {{ request()->is('doctors') ? 'active' : '' }}">Doctors</a>
          </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>


        </ul>
      @show
    </nav>

    {{-- Content Wrapper --}}
    <div class="content-wrapper">
      {{-- Header --}}
      <header>
        <div class="d-flex align-items-center gap-2">
          <button class="sidebar-toggle-btn d-md-none text-dark" id="sidebarToggle">&#9776;</button>
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search ..." />
          </div>
        </div>
        <div class="header-right">
          <i class="fas fa-envelope"></i>
          <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-count">4</span>
          </div>
          <i class="fas fa-layer-group"></i>
          <div class="profile d-flex align-items-center gap-2">
            <img src="https://via.placeholder.com/30" alt="User" />
            <span>Hi, <strong>Hizrian</strong></span>
          </div>
        </div>
      </header>

      {{-- Main Content --}}
      <main>
        @yield('content')
      </main>

      {{-- Footer --}}
      <footer>
        @section('footer')
          &copy; {{ date('Y') }} Hospital Management System. All rights reserved.
        @show
      </footer>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('show');
    });

    window.addEventListener('click', e => {
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
      }
    });
  </script>
</body>
</html>
