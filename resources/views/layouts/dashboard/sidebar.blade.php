  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    @php
      $authUser = auth()->user();
    @endphp

    <ul class="sidebar-nav" id="sidebar-nav">

      {{-- Dashboard (selalu tampil) --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

      {{-- Kerjasama --}}
      @if($authUser->hasPermission('kerjasama', 'can_read'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kerjasama.*') ? '' : 'collapsed' }}" href="{{ route('kerjasama.index') }}">
          <i class="bi bi-file-earmark-text"></i>
          <span>Kerjasama</span>
        </a>
      </li>
      @endif

      {{-- Kegiatan --}}
      @if($authUser->hasPermission('kegiatan', 'can_read'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kegiatan.*') ? '' : 'collapsed' }}" href="{{ route('kegiatan.index') }}">
          <i class="bi bi-calendar-event"></i>
          <span>Kegiatan</span>
        </a>
      </li>
      @endif

      {{-- Mitra --}}
      @if($authUser->hasPermission('mitra', 'can_read'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('mitra.*') ? '' : 'collapsed' }}" href="{{ route('mitra.index') }}">
          <i class="bi bi-building"></i>
          <span>Mitra</span>
        </a>
      </li>
      @endif

      {{-- Data Referensi (tampil jika ada minimal 1 sub-modul yang bisa diakses) --}}
      @php
        $canReadBentukKegiatan = $authUser->hasPermission('bentuk_kegiatan', 'can_read');
        $canReadSasaranKinerja = $authUser->hasPermission('sasaran_kinerja', 'can_read');
        $canReadKriteriaMitra  = $authUser->hasPermission('kriteria_mitra', 'can_read');
        $canReadSumberDana     = $authUser->hasPermission('sumber_dana', 'can_read');
        $canReadJenisDokumen   = $authUser->hasPermission('jenis_dokumen', 'can_read');
        $canReadUnitKerja      = $authUser->hasPermission('unit_kerja', 'can_read');
        $hasAnyRef = $canReadBentukKegiatan || $canReadSasaranKinerja || $canReadKriteriaMitra
                  || $canReadSumberDana || $canReadJenisDokumen || $canReadUnitKerja;
      @endphp

      @if($hasAnyRef)
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('bentuk-kegiatan.*','sasaran-kinerja.*','kriteria-mitra.*','sumber-dana.*','jenis-dokumen.*','unit-kerja.*') ? '' : 'collapsed' }}"
           data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Data Referensi</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav"
            class="nav-content collapse {{ request()->routeIs('bentuk-kegiatan.*','sasaran-kinerja.*','kriteria-mitra.*','sumber-dana.*','jenis-dokumen.*','unit-kerja.*') ? 'show' : '' }}"
            data-bs-parent="#sidebar-nav">
          @if($canReadBentukKegiatan)
          <li>
            <a href="{{ route('bentuk-kegiatan.index') }}" class="{{ request()->routeIs('bentuk-kegiatan.*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Bentuk Kegiatan</span>
            </a>
          </li>
          @endif
          @if($canReadSasaranKinerja)
          <li>
            <a href="{{ route('sasaran-kinerja.index') }}" class="{{ request()->routeIs('sasaran-kinerja.*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Sasaran Kinerja</span>
            </a>
          </li>
          @endif
          @if($canReadKriteriaMitra)
          <li>
            <a href="{{ route('kriteria-mitra.index') }}" class="{{ request()->routeIs('kriteria-mitra.*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Kriteria Mitra</span>
            </a>
          </li>
          @endif
          @if($canReadSumberDana)
          <li>
            <a href="{{ route('sumber-dana.index') }}" class="{{ request()->routeIs('sumber-dana.*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Sumber Dana</span>
            </a>
          </li>
          @endif
          @if($canReadJenisDokumen)
          <li>
            <a href="{{ route('jenis-dokumen.index') }}" class="{{ request()->routeIs('jenis-dokumen.*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Jenis Dokumen</span>
            </a>
          </li>
          @endif
          @if($canReadUnitKerja)
          <li>
            <a href="{{ route('unit-kerja.index') }}" class="{{ request()->routeIs('unit-kerja.*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Unit Kerja</span>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      {{-- Laporan --}}
      @if($authUser->hasPermission('laporan', 'can_read'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('laporan.*') ? '' : 'collapsed' }}" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.index') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Laporan Kerjasama</span>
            </a>
          </li>
        </ul>
      </li>
      @endif

      {{-- Separator Pages --}}
      @if($authUser->hasPermission('user', 'can_read') || $authUser->roles === 'superadmin')
      <li class="nav-heading">Pages</li>
      @endif

      {{-- Pengguna (hanya jika punya izin read modul user) --}}
      @if($authUser->hasPermission('user', 'can_read'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('user.*') ? '' : 'collapsed' }}" href="{{ route('user.index') }}">
          <i class="bi bi-people"></i>
          <span>Pengguna</span>
        </a>
      </li>
      @endif

      {{-- Hak Akses (hanya superadmin) --}}
      @if($authUser->roles === 'superadmin')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('role-permission.*') ? '' : 'collapsed' }}" href="{{ route('user.index') }}" title="Kelola hak akses via tabel pengguna">
          <i class="bi bi-shield-lock-fill" style="color: #6f42c1;"></i>
          <span style="color: #6f42c1; font-weight: 600;">Hak Akses</span>
        </a>
      </li>
      @endif

    </ul>

  </aside>