@extends('layouts.dashboard.template')

@section('title', 'Tambah Staff - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Tambah Staff</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item">Data Referensi</li>
      <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Data Staff</a></li>
      <li class="breadcrumb-item active">Tambah</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <strong>Terdapat kesalahan:</strong>
          <ul class="mb-0 ps-3 mt-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form action="{{ route('staff.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-person-plus"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Staff</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            {{-- Row 1: Nama & NUP --}}
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="nama_staff" class="small text-muted mb-1 d-block">Nama *</label>
                <input type="text" name="nama_staff" id="nama_staff" class="form-control form-control-m" placeholder="Masukkan Nama" required value="{{ old('nama_staff') }}">
              </div>
              <div class="col-md-6">
                <label for="nup" class="small text-muted mb-1 d-block">NUP</label>
                <input type="text" name="nup" id="nup" class="form-control form-control-m" placeholder="Masukkan NUP (opsional)" value="{{ old('nup') }}">
              </div>
            </div>

            {{-- Row 2: Jabatan, Email, Nomor Telepon --}}
            <div class="row mb-3">
              <div class="col-md-4 mb-3 mb-md-0">
                <label for="jabatan" class="small text-muted mb-1 d-block">Jabatan</label>
                <input type="text" name="jabatan" id="jabatan" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)" value="{{ old('jabatan') }}">
              </div>
              <div class="col-md-4 mb-3 mb-md-0">
                <label for="email" class="small text-muted mb-1 d-block">Email</label>
                <input type="email" name="email" id="email" class="form-control form-control-m" placeholder="Masukkan Email (opsional)" value="{{ old('email') }}">
              </div>
              <div class="col-md-4">
                <label for="nomor_hp" class="small text-muted mb-1 d-block">Nomor Telepon</label>
                <input type="text" name="nomor_hp" id="nomor_hp" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)" value="{{ old('nomor_hp') }}">
              </div>
            </div>

            {{-- Row 3: Unit Kerja & Status --}}
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="unit_kerja_id" class="small text-muted mb-1 d-block">Unit Kerja</label>
                <select name="unit_kerja_id" id="unit_kerja_id" class="form-select form-control-m">
                  <option value="" selected>Pilih Unit Kerja (opsional)</option>
                  @foreach($unitKerjas as $uk)
                    <option value="{{ $uk->id }}" {{ old('unit_kerja_id') == $uk->id ? 'selected' : '' }}>{{ $uk->nama_unit_kerja }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label for="status" class="small text-muted mb-1 d-block">Status *</label>
                <select name="status" id="status" class="form-select form-control-m" required>
                  <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                  <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
              </div>
            </div>

            {{-- Row 4: Alamat --}}
            <div class="mb-2">
              <label for="alamat" class="small text-muted mb-1 d-block">Alamat</label>
              <textarea name="alamat" id="alamat" class="form-control form-control-m" rows="2" placeholder="Masukkan Alamat (opsional)">{{ old('alamat') }}</textarea>
            </div>

          </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
          <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-success px-4 text-white">
            <i class="bi bi-check-lg me-1"></i> Simpan
          </button>
        </div>

      </form>
    </div>
  </div>
</section>

<style>
  .section-icon {
    width: 32px; height: 32px; background: #e8f5e9; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; color: #157347; font-size: 1rem;
  }
  .form-control-m {
    border: 1.5px solid #dee2e6; border-radius: 8px;
    padding: 0.55rem 0.85rem; font-size: 0.9rem;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .form-control-m:focus { border-color: #157347; box-shadow: 0 0 0 0.2rem rgba(21,115,71,.12); }
  .form-control-m::placeholder { color: #adb5bd; font-size: 0.875rem; }
</style>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('#unit_kerja_id').select2({ placeholder: "Pilih Unit Kerja (opsional)", allowClear: true });
    $('#status').select2({ placeholder: "Pilih Status" });
  });
</script>
@endpush
