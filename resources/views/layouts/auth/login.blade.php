<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login - SIM Kerjasama Universitas Ibnu Sina</title>
  <meta content="SIM Kerjasama Universitas Ibnu Sina" name="description">
  <meta content="sim, kerjasama, uis, login" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Outfit:300,400,500,600,700,800" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

  <style>
    :root {
      --uis-green: #157347;
      --uis-green-dark: #0f5433;
      --uis-green-bg: #092e1c;
      --uis-yellow: #ffc107;
      --uis-yellow-glow: rgba(255, 193, 7, 0.4);
      --uis-teal: #2bcbba;
      --card-bg: rgba(255, 255, 255, 0.95);
      --text-main: #0f172a;
      --text-sub: #475569;
      --border-color: #e2e8f0;
    }

    html, body {
      font-family: "Outfit", sans-serif;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      background-color: #f1f5f9;
    }

    /* Lock scrollbar and height on desktop only */
    @media (min-width: 992px) {
      html, body {
        height: 100vh;
        overflow: hidden;
      }
      .main-container {
        height: 100vh;
      }
    }

    /* Left Panel: Triple Helix Network Visual (Dribbble/Pinterest Inspired) */
    .left-panel {
      background: radial-gradient(circle at 30% 30%, #114d30 0%, #072215 100%);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 60px;
      color: white;
    }

    /* Abstract grid overlays */
    .left-panel::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
      background-size: 40px 40px;
      z-index: 1;
      pointer-events: none;
    }

    /* Radial Glowing blobs in background */
    .glow-blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(150px);
      z-index: 0;
      pointer-events: none;
    }

    .glow-blob-1 {
      width: 450px;
      height: 450px;
      background: var(--uis-green);
      top: -100px;
      left: -100px;
      opacity: 0.35;
    }

    .glow-blob-2 {
      width: 350px;
      height: 350px;
      background: var(--uis-yellow);
      bottom: -50px;
      right: -50px;
      opacity: 0.15;
    }

    .glow-blob-3 {
      width: 300px;
      height: 300px;
      background: var(--uis-teal);
      top: 40%;
      left: 30%;
      opacity: 0.12;
    }

    .panel-header {
      position: relative;
      z-index: 5;
    }

    .badge-modern {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      padding: 8px 18px;
      border-radius: 100px;
      font-size: 13px;
      font-weight: 600;
      color: #fff;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      backdrop-filter: blur(5px);
    }

    .badge-modern i {
      color: var(--uis-yellow);
    }

    .panel-body {
      position: relative;
      z-index: 5;
      margin: auto 0;
    }

    .panel-body h1 {
      font-size: 40px;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 16px;
      letter-spacing: -1px;
      background: linear-gradient(to right, #ffffff, #ffeaac);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .panel-body p.subtitle {
      font-size: 16px;
      color: #cbd5e1;
      font-weight: 400;
      line-height: 1.6;
      margin-bottom: 45px;
      max-width: 480px;
    }

    /* Modern Triple Helix network representation with pulsating nodes and lines */
    .network-visualizer {
      position: relative;
      width: 500px;
      height: 260px;
      margin: 0 auto 40px auto;
      border-radius: 20px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(10px);
      box-shadow: inset 0 0 20px rgba(255,255,255,0.05);
    }

    /* Interconnecting network design using CSS */
    .net-node {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
      z-index: 5;
      transition: all 0.3s ease;
      backdrop-filter: blur(5px);
      padding: 10px 14px;
      width: 180px;
      height: 60px;
    }

    .net-node:hover {
      transform: scale(1.05);
      border-color: rgba(255,255,255,0.4);
      background: rgba(255, 255, 255, 0.15);
    }

    .net-node-center {
      left: 210px;
      top: 90px;
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, rgba(21, 115, 71, 0.9) 0%, rgba(13, 67, 41, 0.9) 100%);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 20px;
      z-index: 10;
      box-shadow: 0 15px 35px rgba(21, 115, 71, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
    }

    .net-node-center img {
      max-width: 44px;
      border-radius: 6px;
    }

    .net-node-gov {
      top: 10px;
      left: 10px;
      border-left: 3px solid var(--uis-yellow);
    }

    .net-node-ind {
      top: 190px;
      left: 10px;
      border-left: 3px solid var(--uis-teal);
    }

    .net-node-uni {
      left: 310px;
      top: 100px;
      border-left: 3px solid #38bdf8;
    }

    .net-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      flex-shrink: 0;
    }

    .net-icon-gov { background: rgba(255, 193, 7, 0.2); color: var(--uis-yellow); }
    .net-icon-ind { background: rgba(43, 203, 186, 0.2); color: var(--uis-teal); }
    .net-icon-uni { background: rgba(56, 189, 248, 0.2); color: #38bdf8; }

    .net-text {
      overflow: hidden;
    }

    .net-text h5 {
      font-size: 13px;
      font-weight: 700;
      margin: 0;
      color: #fff;
      white-space: nowrap;
    }

    .net-text span {
      font-size: 10.5px;
      color: #cbd5e1;
      font-weight: 500;
      white-space: nowrap;
      display: block;
    }

    /* Animated connecting line SVG */
    .network-svg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 2;
      pointer-events: none;
    }

    .network-line {
      stroke: rgba(255, 255, 255, 0.22);
      stroke-width: 2;
      stroke-dasharray: 6 4;
      animation: dash 12s linear infinite;
    }

    @keyframes dash {
      to {
        stroke-dashoffset: -100;
      }
    }

    /* Analytics Dashboard Floating Card */
    .stat-badge {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 12px 20px;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-right: 15px;
      backdrop-filter: blur(5px);
    }
    
    .stat-badge h4 {
      font-size: 18px;
      font-weight: 800;
      color: var(--uis-yellow);
      margin: 0;
    }
    
    .stat-badge span {
      font-size: 11.5px;
      color: #cbd5e1;
      font-weight: 500;
    }

    .panel-footer {
      position: relative;
      z-index: 5;
      font-size: 12.5px;
      color: #64748b;
      font-weight: 500;
    }

    /* Right Panel: Beautiful White Clean Canvas */
    .right-panel {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 60px;
      background-color: #ffffff;
      position: relative;
    }

    .login-box {
      width: 100%;
      max-width: 380px;
    }

    /* Modern Soft Logo Box */
    .brand-container {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 35px;
    }

    .brand-logo-frame {
      width: 50px;
      height: 50px;
      background: #f8fafc;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }

    .brand-logo-frame img {
      max-height: 38px;
      object-fit: contain;
      border-radius: 4px;
    }

    .brand-info h2 {
      font-size: 20px;
      font-weight: 800;
      color: var(--uis-green-bg);
      margin: 0;
      letter-spacing: -0.5px;
    }

    .brand-info span {
      font-size: 11.5px;
      color: var(--text-sub);
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .login-title-box h3 {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }

    .login-title-box p {
      font-size: 14.5px;
      color: var(--text-sub);
      margin-bottom: 32px;
      font-weight: 400;
    }

    /* Premium inputs styling */
    .form-group-modern {
      margin-bottom: 22px;
    }

    .form-group-modern label {
      font-size: 12.5px;
      font-weight: 700;
      color: #475569;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
      display: block;
    }

    .input-group-modern {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon-modern {
      position: absolute;
      left: 16px;
      color: #94a3b8;
      font-size: 17px;
      transition: color 0.2s ease;
    }

    .input-field-modern {
      width: 100%;
      padding: 13px 16px 13px 46px;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      font-size: 15px;
      color: var(--text-main);
      font-weight: 500;
      outline: none;
      transition: all 0.2s ease;
      background-color: #f8fafc;
    }

    .input-field-modern::placeholder {
      color: #94a3b8;
    }

    .input-field-modern:focus {
      border-color: var(--uis-green);
      background-color: #ffffff;
      box-shadow: 0 0 0 4px rgba(21, 115, 71, 0.12);
    }

    .input-field-modern:focus ~ .input-icon-modern {
      color: var(--uis-green);
    }

    .pass-toggle-modern {
      position: absolute;
      right: 16px;
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      font-size: 17px;
      padding: 0;
      display: flex;
      align-items: center;
      transition: color 0.2s ease;
    }

    .pass-toggle-modern:hover {
      color: var(--uis-green);
    }

    .input-field-modern-password {
      padding-right: 46px;
    }

    /* Custom Checkbox Design */
    .checkbox-container-modern {
      display: flex;
      align-items: center;
      cursor: pointer;
      user-select: none;
    }

    .checkbox-container-modern input {
      display: none;
    }

    .checkmark-modern {
      width: 18px;
      height: 18px;
      border: 1.5px solid #cbd5e1;
      border-radius: 6px;
      margin-right: 10px;
      display: inline-block;
      position: relative;
      background: white;
      transition: all 0.2s ease;
    }

    .checkbox-container-modern:hover .checkmark-modern {
      border-color: var(--uis-green);
    }

    .checkbox-container-modern input:checked ~ .checkmark-modern {
      background: var(--uis-green);
      border-color: var(--uis-green);
    }

    .checkbox-container-modern input:checked ~ .checkmark-modern::after {
      content: "";
      position: absolute;
      left: 5px;
      top: 2px;
      width: 5px;
      height: 8px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }

    .checkbox-label-modern {
      font-size: 13.5px;
      font-weight: 500;
      color: var(--text-sub);
    }

    /* Submit Action Button */
    .btn-submit-modern {
      width: 100%;
      padding: 13.5px;
      background: linear-gradient(135deg, var(--uis-green) 0%, #0e4c2f 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 15.5px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 6px 15px rgba(21, 115, 71, 0.2);
      transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-submit-modern:hover {
      box-shadow: 0 8px 25px rgba(21, 115, 71, 0.35);
      transform: translateY(-2px);
    }

    .btn-submit-modern:active {
      transform: translateY(0);
    }

    /* Custom Toast Style Alert */
    .alert-modern {
      border: none;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 13.5px;
      font-weight: 500;
      margin-bottom: 22px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .alert-modern ul {
      margin-bottom: 0;
      padding-left: 15px;
    }

    /* Large screen splits */
    @media (max-width: 1199.98px) {
      .left-panel {
        padding: 40px;
      }
      .panel-body h1 {
        font-size: 32px;
      }
    }

    /* Mobile Screens (Redesigned for perfect spacing and alignment) */
    @media (max-width: 991.98px) {
      .left-panel {
        display: none !important;
      }
      .right-panel {
        width: 100% !important;
        min-height: 100vh !important;
        padding: 24px 16px !important; /* Reduced padding from 60px to 16px to let card span properly */
        background: radial-gradient(at 0% 0%, #114d30 0%, #072215 100%);
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .login-box {
        background: #ffffff;
        padding: 35px 24px;
        border-radius: 24px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        max-width: 440px; /* Allow slightly wider fit */
        width: 100%;
        margin: 0;
      }
    }
  </style>
</head>

<body>

  <div class="container-fluid main-container p-0">
    <div class="row g-0 h-100">
      
      <!-- Left Panel: Triple Helix Network Visual -->
      <div class="col-lg-7 left-panel d-none d-lg-flex">
        <div class="glow-blob glow-blob-1"></div>
        <div class="glow-blob glow-blob-2"></div>
        <div class="glow-blob glow-blob-3"></div>
        
        <div class="panel-header">
          <span class="badge-modern">
            <i class="bi bi-cpu-fill"></i> Sistem Informasi Kerjasama Terpadu
          </span>
        </div>

        <div class="panel-body">
          <h1>Sinergi Multi-Pihak<br>untuk Inovasi Berkelanjutan</h1>
          <p class="subtitle">
            Mengintegrasikan potensi perguruan tinggi, regulasi pemerintah, dan kapabilitas industri dalam satu sistem manajemen kerjasama strategis.
          </p>

          <!-- Interactive Connection Graphic (100% Mathematically Precise Coordinates) -->
          <div class="network-visualizer">
            <svg class="network-svg">
              <!-- Connecting Gov to Center (190, 40) to (250, 130) -->
              <path class="network-line" d="M 190 40 L 250 130" />
              <!-- Connecting Ind to Center (190, 220) to (250, 130) -->
              <path class="network-line" d="M 190 220 L 250 130" />
              <!-- Connecting Center to Uni (250, 130) to (310, 130) -->
              <path class="network-line" d="M 250 130 L 310 130" />
            </svg>

            <!-- Gov Node (Government) -->
            <div class="net-node net-node-gov">
              <div class="net-icon net-icon-gov">
                <i class="bi bi-bank2"></i>
              </div>
              <div class="net-text">
                <h5>Pemerintah</h5>
                <span>Regulator & Dana</span>
              </div>
            </div>

            <!-- Central Hub Node (UIS Center Logo) -->
            <div class="net-node-center">
              <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo UIS">
            </div>

            <!-- Industry Node (Private Sector) -->
            <div class="net-node net-node-ind">
              <div class="net-icon net-icon-ind">
                <i class="bi bi-briefcase-fill"></i>
              </div>
              <div class="net-text">
                <h5>Dunia Industri</h5>
                <span>Hilirisasi & Karir</span>
              </div>
            </div>

            <!-- University Node (Academic Sector) -->
            <div class="net-node net-node-uni">
              <div class="net-icon net-icon-uni">
                <i class="bi bi-mortarboard-fill"></i>
              </div>
              <div class="net-text">
                <h5>Akademisi</h5>
                <span>Riset & Pendidikan</span>
              </div>
            </div>
          </div>

          <!-- Active Stats counter -->
          <div class="d-flex align-items-center">
            <div class="stat-badge">
              <h4>120+</h4>
              <span>Mitra Aktif</span>
            </div>
            <div class="stat-badge">
              <h4>450+</h4>
              <span>MoU & MoA</span>
            </div>
            <div class="stat-badge">
              <h4>100%</h4>
              <span>Digitalisasi</span>
            </div>
          </div>
        </div>

        <div class="panel-footer">
          <span>&copy; {{ date('Y') }} Universitas Ibnu Sina &bull; Dikembangkan untuk Optimalisasi Sinergi</span>
        </div>
      </div>

      <!-- Right Panel: Login Box -->
      <div class="col-lg-5 col-12 right-panel">
        <div class="login-box">
          
          <!-- Brand Header -->
          <div class="brand-container">
            <div class="brand-logo-frame">
              <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo UIS">
            </div>
            <div class="brand-info">
              <h2>SIM-KERJASAMA</h2>
              <span>Universitas Ibnu Sina</span>
            </div>
          </div>

          <!-- Title -->
          <div class="login-title-box">
            <h3>Masuk Sistem</h3>
            <p>Silakan masukkan kredensial Anda untuk mengakses dashboard.</p>
          </div>

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-modern" role="alert">
              <i class="bi bi-check-circle-fill me-2 text-success"></i>
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show alert-modern" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
              <span class="d-inline-block align-middle">
                <ul class="mb-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </span>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form action="{{ action([App\Http\Controllers\AuthController::class, 'loginproses']) }}" method="POST">
            @csrf

            <div class="form-group-modern">
              <label for="email">Alamat Email</label>
              <div class="input-group-modern">
                <input type="email" name="email" class="input-field-modern @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" placeholder="Contoh: nama@uis.ac.id" required autofocus>
                <i class="bi bi-envelope input-icon-modern"></i>
              </div>
            </div>

            <div class="form-group-modern">
              <label for="password">Password</label>
              <div class="input-group-modern">
                <input type="password" name="password" class="input-field-modern input-field-custom-password" id="password" placeholder="••••••••" required>
                <i class="bi bi-lock input-icon-modern"></i>
                <button type="button" id="togglePassword" class="pass-toggle-modern" aria-label="Toggle password visibility">
                  <i class="bi bi-eye" id="passwordEyeIcon"></i>
                </button>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <label class="checkbox-container-modern">
                <input type="checkbox" name="remember" value="true" id="rememberMe">
                <span class="checkmark-modern"></span>
                <span class="checkbox-label-modern">Ingat login saya</span>
              </label>
            </div>
            
            <button class="btn-submit-modern" type="submit">
              Masuk Aplikasi
              <i class="bi bi-arrow-right-short" style="font-size: 20px; line-height: 1;"></i>
            </button>
          </form>

        </div>
      </div>

    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Password visibility toggler script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const togglePasswordBtn = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('passwordEyeIcon');

      if (togglePasswordBtn && passwordInput && eyeIcon) {
        togglePasswordBtn.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          
          if (type === 'text') {
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
          } else {
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
          }
        });
      }
    });
  </script>

</body>

</html>