@extends('layouts.dashboard.template')

@section('title', 'Edit Pengguna - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Edit Pengguna</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Pengguna</a></li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-9">

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <strong>Terdapat kesalahan:</strong>
          <ul class="mb-0 ps-3 mt-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Section: Informasi Akun --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon">
                <i class="bi bi-person-fill"></i>
              </div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Akun</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">
            <div class="mb-4">
              <label for="name" class="form-label fw-medium">
                Nama Lengkap <span class="text-danger">*</span>
              </label>
              <input
                type="text"
                name="name"
                id="name"
                class="form-control form-control-user @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}"
                placeholder="Masukkan nama lengkap"
                required
              >
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="email" class="form-label fw-medium">
                Alamat Email <span class="text-danger">*</span>
              </label>
              <input
                type="email"
                name="email"
                id="email"
                class="form-control form-control-user @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                placeholder="nama@gmail.com"
                required
              >
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="role" class="form-label fw-medium">
                  Hak Akses (Role) <span class="text-danger">*</span>
                </label>
                <select name="role" id="role" class="form-select form-select-user @error('role') is-invalid @enderror" required>
                  <option value="superadmin" {{ old('role', $user->roles) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                  <option value="admin" {{ old('role', $user->roles) == 'admin' ? 'selected' : '' }}>Admin</option>
                  <option value="pimpinan" {{ old('role', $user->roles) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                  <option value="user" {{ old('role', $user->roles) == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3 d-flex align-items-end pb-1">
                <div>
                  <label class="form-label fw-medium d-block">Status Pengguna</label>
                  <div class="form-check form-switch mt-1">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="is_active"
                      id="is_active"
                      value="1"
                      {{ old('is_active', $user->is_active) == '1' ? 'checked' : '' }}
                      style="width: 2.5em; height: 1.3em;"
                    >
                    <label class="form-check-label fw-semibold ms-2" for="is_active">Aktif</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Section: Ubah Password (Opsional) --}}
        <div class="card shadow-sm border-0 mb-4" id="password-section">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <div class="section-icon">
                  <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h6 class="mb-0 fw-semibold text-dark">Ubah Password</h6>
              </div>
              <span class="badge bg-secondary-subtle text-secondary fw-normal fs-xs px-2 py-1">Opsional</span>
            </div>
          </div>
          <div class="card-body px-4 py-4">
            <div class="alert alert-info border-0 py-2 px-3 mb-4" style="background:#e8f4fd; font-size:0.85rem;">
              <i class="bi bi-info-circle me-1"></i>
              Kosongkan kolom password jika tidak ingin mengubah password. Password lama akan tetap digunakan.
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label fw-medium">Password Baru</label>
                <div class="input-group">
                  <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control form-control-user @error('password') is-invalid @enderror"
                    placeholder="Kosongkan jika tidak diubah"
                  >
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                  </button>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password Baru</label>
                <div class="input-group">
                  <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-control form-control-user"
                    placeholder="Ulangi password baru"
                  >
                  <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" tabindex="-1">
                    <i class="bi bi-eye" id="eyeIconConfirm"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
          <a href="{{ route('user.index') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-success px-4 text-white">
            <i class="bi bi-check-lg me-1"></i> Perbarui Pengguna
          </button>
        </div>

      </form>
    </div>
  </div>
</section>

<style>
  .section-icon {
    width: 32px;
    height: 32px;
    background: #e8f5e9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #157347;
    font-size: 1rem;
  }

  .form-control-user,
  .form-select-user {
    border: 1.5px solid #dee2e6;
    border-radius: 8px;
    padding: 0.55rem 0.85rem;
    font-size: 0.9rem;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .form-control-user:focus,
  .form-select-user:focus {
    border-color: #157347;
    box-shadow: 0 0 0 0.2rem rgba(21, 115, 71, 0.12);
  }

  .form-control-user::placeholder {
    color: #adb5bd;
    font-size: 0.875rem;
  }

  .input-group .btn-outline-secondary {
    border-color: #dee2e6;
    border-left: none;
    color: #6c757d;
  }

  .input-group .btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #157347;
    border-color: #157347;
  }

  .fs-xs { font-size: 0.78rem; }
</style>

@endsection

@push('scripts')
<script>
  function toggleVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  }

  document.getElementById('togglePassword')?.addEventListener('click', () => toggleVisibility('password', 'eyeIcon'));
  document.getElementById('togglePasswordConfirm')?.addEventListener('click', () => toggleVisibility('password_confirmation', 'eyeIconConfirm'));
</script>
@endpush
