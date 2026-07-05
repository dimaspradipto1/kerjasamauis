@extends('layouts.dashboard.template')

@section('title', 'Edit Jenis Dokumen - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Edit Jenis Dokumen</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item">Data Referensi</li>
      <li class="breadcrumb-item"><a href="{{ route('jenis-dokumen.index') }}">Jenis Dokumen</a></li>
      <li class="breadcrumb-item active">Edit</li>
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

      <form action="{{ route('jenis-dokumen.update', $jenisDokumen->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-file-earmark-text"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Jenis Dokumen</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div class="mb-4">
              <label for="nama_jenis_dokumen" class="form-label fw-medium">
                Jenis Dokumen <span class="text-danger">*</span>
              </label>
              <textarea
                name="nama_jenis_dokumen"
                id="nama_jenis_dokumen"
                class="form-control form-control-jd @error('nama_jenis_dokumen') is-invalid @enderror"
                rows="3"
                placeholder="Masukkan Jenis Dokumen"
                required
              >{{ old('nama_jenis_dokumen', $jenisDokumen->nama_jenis_dokumen) }}</textarea>
              @error('nama_jenis_dokumen')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-2">
              <label for="keterangan" class="form-label fw-medium">
                Keterangan <span class="text-muted fw-normal">(Opsional)</span>
              </label>
              <textarea
                name="keterangan"
                id="keterangan"
                class="form-control form-control-jd @error('keterangan') is-invalid @enderror"
                rows="3"
                placeholder="Masukkan keterangan tambahan jika ada"
              >{{ old('keterangan', $jenisDokumen->keterangan) }}</textarea>
              @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
          <a href="{{ route('jenis-dokumen.index') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-success px-4 text-white">
            <i class="bi bi-check-lg me-1"></i> Perbarui
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
  .form-control-jd {
    border: 1.5px solid #dee2e6; border-radius: 8px;
    padding: 0.55rem 0.85rem; font-size: 0.9rem;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .form-control-jd:focus { border-color: #157347; box-shadow: 0 0 0 0.2rem rgba(21,115,71,.12); }
  .form-control-jd::placeholder { color: #adb5bd; font-size: 0.875rem; }
</style>
@endsection
