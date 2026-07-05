 <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center gap-2">
        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="rounded-3" style="max-height: 40px; background: white; padding: 2px; border: 1px solid #dee2e6;">
        <div class="logo-text d-none d-lg-flex flex-column text-start" style="line-height: 1.2;">
          <span class="fw-bold m-0" style="font-size: 0.95rem; color: #157347 !important;">SIM Kerjasama</span>
          <span class="text-secondary m-0" style="font-size: 0.75rem; color: #012970 !important; font-weight: 600;">Universitas Ibnu Sina</span>
        </div>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            @php
              $profileName    = Auth::user()->name ?? 'User';
              $initials       = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $profileName), 0, 2))));
              $avatarColors   = ['#0d6efd','#198754','#6f42c1','#fd7e14','#0dcaf0','#d63384','#20c997'];
              $avatarBg       = $avatarColors[crc32($profileName) % count($avatarColors)];
            @endphp
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:36px;height:36px;background:{{ $avatarBg }};color:#fff;font-weight:700;font-size:0.8rem;letter-spacing:0.5px;user-select:none;">
              {{ $initials }}
            </div>
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
          </a><!-- End Profile Image Icon -->
 
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ Auth::user()->name }}</h6>
              <span>{{ ucfirst(Auth::user()->role) }}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
 
            {{-- <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li> --}}
            {{-- <li>
              <hr class="dropdown-divider">
            </li> --}}
 
            {{-- <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li> --}}
            {{-- <li>
              <hr class="dropdown-divider">
            </li> --}}
 
            {{-- <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li> --}}
            <li>
              <hr class="dropdown-divider">
            </li>
 
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->