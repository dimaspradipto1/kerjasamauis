@extends('layouts.dashboard.template')

@section('title', 'Tambah Kerjasama - SIM Kerjasama UIS')

@section('content')
@php
  $staffList = $staffList ?? \App\Models\Staff::where('status', 'Aktif')->orderBy('nama_staff', 'asc')->get();
@endphp
<div class="pagetitle mb-3">
  <h1>Tambah Kerjasama</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('kerjasama.index') }}">Kerjasama</a></li>
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

      <form action="{{ route('kerjasama.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ══ Section 1: Informasi Kerjasama ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-file-earmark-text"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Kerjasama</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="nomor_dokumen_kerjasama" class="form-label fw-semibold">Nomor Dokumen Kerjasama <span class="text-danger">*</span></label>
                <input type="text" name="nomor_dokumen_kerjasama" id="nomor_dokumen_kerjasama" class="form-control form-control-m" placeholder="Masukkan Nomor Dokumen Kerjasama" required value="{{ old('nomor_dokumen_kerjasama') }}">
              </div>
              <div class="col-md-6">
                <label for="nomor_dokumen_mitra" class="form-label fw-semibold">Nomor Dokumen Mitra</label>
                <input type="text" name="nomor_dokumen_mitra" id="nomor_dokumen_mitra" class="form-control form-control-m" placeholder="Masukkan Nomor Dokumen Mitra (opsional)" value="{{ old('nomor_dokumen_mitra') }}">
              </div>
            </div>

            <div class="mb-4">
              <label for="jenis_dokumen_id" class="form-label fw-semibold">Jenis Dokumen <span class="text-danger">*</span></label>
              <select name="jenis_dokumen_id" id="jenis_dokumen_id" class="form-select form-control-m" required>
                <option value="" disabled selected>Pilih Jenis Dokumen</option>
                @foreach($jenisDokumens as $jd)
                  <option value="{{ $jd->id }}" data-nama="{{ strtolower($jd->nama_jenis_dokumen) }}" {{ old('jenis_dokumen_id') == $jd->id ? 'selected' : '' }}>{{ $jd->nama_jenis_dokumen }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4" id="container_bidang_implementasi" style="display: none;">
              <label for="bidang_implementasi" class="form-label fw-semibold">Jenis Kerjasama Implementasi (Bidang) <span class="text-danger">*</span></label>
              <select name="bidang_implementasi" id="bidang_implementasi" class="form-select form-control-m">
                <option value="" disabled {{ !old('bidang_implementasi') ? 'selected' : '' }}>Pilih Jenis Kerjasama Implementasi</option>
                <option value="Pendidikan" {{ old('bidang_implementasi') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                <option value="Penelitian" {{ old('bidang_implementasi') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                <option value="Pengabdian kepada Masyarakat" {{ old('bidang_implementasi') == 'Pengabdian kepada Masyarakat' ? 'selected' : '' }}>Pengabdian kepada Masyarakat</option>
              </select>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label fw-semibold">Unit Kerja <span class="text-danger">*</span></label>
                <div class="border rounded-3 p-3 bg-white" style="max-height: 220px; overflow-y: auto; border: 1.5px solid #dee2e6 !important;">
                  @foreach($unitKerjas as $uk)
                    @php
                      $isHeader = str_contains(strtolower($uk->nama_unit_kerja), 'fakultas') || str_contains(strtolower($uk->nama_unit_kerja), 'universitas');
                    @endphp
                    <div class="form-check mb-2 {{ $isHeader ? 'pt-2 border-top first-no-border' : 'ms-3' }}">
                      <input class="form-check-input" type="checkbox" name="unit_kerja_ids[]" id="uk_{{ $uk->id }}" value="{{ $uk->id }}" {{ is_array(old('unit_kerja_ids')) && in_array($uk->id, old('unit_kerja_ids')) ? 'checked' : '' }}>
                      <label class="form-check-label text-dark small {{ $isHeader ? 'fw-bold text-success' : '' }}" for="uk_{{ $uk->id }}">
                        @if($isHeader)
                          <i class="bi bi-building me-1"></i>
                        @endif
                        {{ $uk->nama_unit_kerja }}
                      </label>
                    </div>
                  @endforeach
                </div>
                <div class="form-text text-muted" style="font-size: 0.78rem;"><i class="bi bi-info-circle me-1"></i>Pilih satu atau lebih fakultas / prodi unit kerja yang terlibat</div>
              </div>
              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <label for="mitra_id" class="form-label fw-semibold mb-0">Mitra <span class="text-danger">*</span></label>
                  <a href="{{ route('mitra.create') }}" class="small text-success fw-semibold" style="text-decoration: none;"><i class="bi bi-plus-lg me-1"></i>Tambah Data Mitra</a>
                </div>
                <select name="mitra_id" id="mitra_id" class="form-select form-control-m" required>
                  <option value="" disabled selected>Pilih Mitra</option>
                  @foreach($mitras as $m)
                    <option value="{{ $m->id }}" {{ old('mitra_id') == $m->id ? 'selected' : '' }}>{{ $m->nama_mitra }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-4">
              <label for="judul_kerjasama" class="form-label fw-semibold">Judul Kerjasama <span class="text-danger">*</span></label>
              <textarea name="judul_kerjasama" id="judul_kerjasama" class="form-control form-control-m" rows="2" placeholder="Masukkan Judul Kerjasama" required>{{ old('judul_kerjasama') }}</textarea>
            </div>

            <div class="mb-4">
              <label for="deskripsi_kerjasama" class="form-label fw-semibold">Deskripsi Kerjasama <span class="text-danger">*</span></label>
              <textarea name="deskripsi_kerjasama" id="deskripsi_kerjasama" class="form-control form-control-m" rows="3" placeholder="Masukkan Deskripsi Kerjasama" required>{{ old('deskripsi_kerjasama') }}</textarea>
            </div>

            {{-- Skala / Tingkat Kerjasama --}}
            <div class="mb-4">
              <label class="form-label fw-semibold d-block">Tingkat / Skala Kerjasama</label>
              @php $skalaOld = old('skala_kerjasama', []); @endphp
              <div class="form-check form-check-inline me-4">
                <input class="form-check-input" type="checkbox" name="skala_kerjasama[]" id="skala_nasional" value="Nasional" {{ is_array($skalaOld) && in_array('Nasional', $skalaOld) ? 'checked' : '' }}>
                <label class="form-check-label fw-medium text-dark" for="skala_nasional">
                  <i class="bi bi-flag me-1 text-primary"></i> Nasional
                </label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="skala_kerjasama[]" id="skala_internasional" value="Internasional" {{ is_array($skalaOld) && in_array('Internasional', $skalaOld) ? 'checked' : '' }}>
                <label class="form-check-label fw-medium text-dark" for="skala_internasional">
                  <i class="bi bi-globe me-1 text-success"></i> Internasional
                </label>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <label for="sumber_dana_id" class="form-label fw-semibold mb-0">Sumber Dana <span class="text-danger">*</span></label>
                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalTambahSumberDana" class="small text-success fw-semibold" style="text-decoration: none;"><i class="bi bi-plus-lg me-1"></i>Tambah Custom Sumber Dana</a>
                </div>
                <select name="sumber_dana_id" id="sumber_dana_id" class="form-select form-control-m" required>
                  <option value="" disabled selected>Pilih atau Ketik Sumber Dana</option>
                  @foreach($sumberDanas as $sd)
                    <option value="{{ $sd->id }}" {{ old('sumber_dana_id') == $sd->id ? 'selected' : '' }}>{{ $sd->nama_sumber_dana }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label for="anggaran" class="form-label fw-semibold">Anggaran</label>
                <input type="number" name="anggaran" id="anggaran" class="form-control form-control-m" placeholder="0" value="{{ old('anggaran', 0) }}" required>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="tanggal_waktu_berlaku" id="label_tanggal_awal" class="form-label fw-semibold">Tanggal Awal Berlaku <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_waktu_berlaku" id="tanggal_waktu_berlaku" class="form-control form-control-m" required value="{{ old('tanggal_waktu_berlaku') }}">
              </div>
              <div class="col-md-6">
                <label for="tanggal_akhir_berlaku" id="label_tanggal_akhir" class="form-label fw-semibold">Tanggal Akhir Berlaku <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_akhir_berlaku" id="tanggal_akhir_berlaku" class="form-control form-control-m" required value="{{ old('tanggal_akhir_berlaku') }}">
              </div>
            </div>

            <div class="mb-4">
              <label for="status_kerjasama" class="form-label fw-semibold">Status Kerjasama <span class="text-danger">*</span></label>
              <select name="status_kerjasama" id="status_kerjasama" class="form-select form-control-m" required>
                <option value="" disabled selected>Pilih Status Kerjasama</option>
                <option value="Aktif" {{ old('status_kerjasama') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ old('status_kerjasama') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                <option value="Selesai" {{ old('status_kerjasama') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">Dokumen</label>
              <div class="border rounded-3 p-4 text-center bg-light" style="border: 2px dashed #ced4da !important; cursor: pointer;" onclick="document.getElementById('dokumen_files').click();">
                <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 2.5rem;"></i>
                <p class="mb-1 small fw-semibold mt-2">Klik untuk pilih file atau seret file ke sini</p>
                <p class="text-muted small mb-0" style="font-size: 0.75rem;">PDF, DOC atau DOCX (Max. 5 MB per file)</p>
                <input type="file" name="dokumen_files[]" id="dokumen_files" class="d-none" accept=".pdf,.doc,.docx" multiple onchange="handleFileSelect(this)">
              </div>
              <div id="file-list-container" class="mt-3"></div>
            </div>

            <div class="mb-2">
              <label for="hasil_pelaksanaan" class="form-label fw-semibold">Hasil Pelaksanaan (Output & Outcome)</label>
              <textarea name="hasil_pelaksanaan" id="hasil_pelaksanaan" class="form-control form-control-m" rows="3" placeholder="Masukkan Hasil Pelaksanaan (Output & Outcome) (opsional)">{{ old('hasil_pelaksanaan') }}</textarea>
            </div>

          </div>
        </div>

        {{-- ══ Section 2: Pihak ke 1 ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-people"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Pihak ke 1</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div class="mb-4">
              <label class="form-label fw-semibold d-block">Pihak ke 1 dari <span class="text-danger">*</span></label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pihak[1][jenis_pihak]" id="pihak1_unit" value="Unit" {{ old('pihak.1.jenis_pihak', 'Unit') === 'Unit' ? 'checked' : '' }} required>
                <label class="form-check-label" for="pihak1_unit">Unit</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pihak[1][jenis_pihak]" id="pihak1_mitra" value="Mitra" {{ old('pihak.1.jenis_pihak') === 'Mitra' ? 'checked' : '' }}>
                <label class="form-check-label" for="pihak1_mitra">Mitra</label>
              </div>
            </div>

            <div class="mb-4">
              <label for="pihak1_alamat" class="form-label fw-semibold">Alamat</label>
              <textarea name="pihak[1][alamat]" id="pihak1_alamat" class="form-control form-control-m" rows="2" placeholder="Masukkan Alamat (opsional)">{{ old('pihak.1.alamat') }}</textarea>
            </div>

            {{-- PJ List Pihak 1 --}}
            <h6 class="fw-bold text-success mb-3 mt-4" style="font-size: 0.9rem;">Penanggung Jawab Pihak 1</h6>
            <div id="pj1-wrapper">
              <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="1" data-index="0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="small fw-semibold text-success pj-title">Penanggung Jawab 1</span>
                  <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj d-none" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nama *</label>
                    <select name="pihak[1][penanggung_jawab][0][nama]" class="form-select form-control-m select-pj-nama" required>
                      <option value=""></option>
                      @foreach($staffList as $st)
                        <option value="{{ $st->nama_staff }}" data-nup="{{ $st->nup }}" data-jabatan="{{ $st->jabatan }}" data-email="{{ $st->email }}" data-hp="{{ $st->nomor_hp }}" data-alamat="{{ $st->alamat }}" {{ old('pihak.1.penanggung_jawab.0.nama') == $st->nama_staff ? 'selected' : '' }}>
                          {{ $st->nama_staff }} @if($st->nup)({{ $st->nup }})@endif
                        </option>
                      @endforeach
                      @if(old('pihak.1.penanggung_jawab.0.nama') && !$staffList->pluck('nama_staff')->contains(old('pihak.1.penanggung_jawab.0.nama')))
                        <option value="{{ old('pihak.1.penanggung_jawab.0.nama') }}" selected>{{ old('pihak.1.penanggung_jawab.0.nama') }}</option>
                      @endif
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">NUP</label>
                    <input type="text" name="pihak[1][penanggung_jawab][0][nip]" class="form-control form-control-m" placeholder="Masukkan NUP (opsional)" value="{{ old('pihak.1.penanggung_jawab.0.nip') }}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Jabatan</label>
                    <input type="text" name="pihak[1][penanggung_jawab][0][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)" value="{{ old('pihak.1.penanggung_jawab.0.jabatan') }}">
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Email</label>
                    <input type="email" name="pihak[1][penanggung_jawab][0][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)" value="{{ old('pihak.1.penanggung_jawab.0.email') }}">
                  </div>
                  <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                    <input type="text" name="pihak[1][penanggung_jawab][0][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)" value="{{ old('pihak.1.penanggung_jawab.0.nomor_hp') }}">
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-2">
              <button type="button" id="btnTambahPj1" class="btn btn-outline-success btn-sm px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Penanggung Jawab Pihak Ke 1
              </button>
            </div>

          </div>
        </div>

        {{-- ══ Section 3: Pihak ke 2 ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-people"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Pihak ke 2</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div class="mb-4">
              <label class="form-label fw-semibold d-block">Pihak ke 2 dari <span class="text-danger">*</span></label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pihak[2][jenis_pihak]" id="pihak2_unit" value="Unit" {{ old('pihak.2.jenis_pihak') === 'Unit' ? 'checked' : '' }} required>
                <label class="form-check-label" for="pihak2_unit">Unit</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pihak[2][jenis_pihak]" id="pihak2_mitra" value="Mitra" {{ old('pihak.2.jenis_pihak', 'Mitra') === 'Mitra' ? 'checked' : '' }}>
                <label class="form-check-label" for="pihak2_mitra">Mitra</label>
              </div>
            </div>

            <div class="mb-4">
              <label for="pihak2_alamat" class="form-label fw-semibold">Alamat</label>
              <textarea name="pihak[2][alamat]" id="pihak2_alamat" class="form-control form-control-m" rows="2" placeholder="Masukkan Alamat (opsional)">{{ old('pihak.2.alamat') }}</textarea>
            </div>

            {{-- PJ List Pihak 2 --}}
            <h6 class="fw-bold text-success mb-3 mt-4" style="font-size: 0.9rem;">Penanggung Jawab Pihak 2</h6>
            <div id="pj2-wrapper">
              <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="2" data-index="0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="small fw-semibold text-success pj-title">Penanggung Jawab 1</span>
                  <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj d-none" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nama *</label>
                    <input type="text" name="pihak[2][penanggung_jawab][0][nama]" class="form-control form-control-m" placeholder="Masukkan Nama Penanggung Jawab" value="{{ old('pihak.2.penanggung_jawab.0.nama') }}" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">NUP</label>
                    <input type="text" name="pihak[2][penanggung_jawab][0][nip]" class="form-control form-control-m" placeholder="Masukkan NUP (opsional)" value="{{ old('pihak.2.penanggung_jawab.0.nip') }}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Jabatan</label>
                    <input type="text" name="pihak[2][penanggung_jawab][0][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)" value="{{ old('pihak.2.penanggung_jawab.0.jabatan') }}">
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Email</label>
                    <input type="email" name="pihak[2][penanggung_jawab][0][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)" value="{{ old('pihak.2.penanggung_jawab.0.email') }}">
                  </div>
                  <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                    <input type="text" name="pihak[2][penanggung_jawab][0][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)" value="{{ old('pihak.2.penanggung_jawab.0.nomor_hp') }}">
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-2">
              <button type="button" id="btnTambahPj2" class="btn btn-outline-success btn-sm px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Penanggung Jawab Pihak Ke 2
              </button>
            </div>

          </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
          <a href="{{ route('kerjasama.index') }}" class="btn btn-outline-secondary px-4">
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
  .pj-item { background: #fafafa; }
  .pj-item:hover { background: #f5f9f6; }

  /* Select2 Styling Overrides */
  .select2-container .select2-selection--single {
    height: 44px !important;
    border: 1.5px solid #dee2e6 !important;
    border-radius: 8px !important;
    padding: 0.55rem 0.85rem !important;
    font-size: 0.9rem !important;
    display: flex !important;
    align-items: center !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #212529 !important;
    padding-left: 0 !important;
    line-height: normal !important;
  }
  .select2-container--default .select2-selection--single:focus,
  .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #157347 !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.2rem rgba(21, 115, 71, 0.12) !important;
  }
  .select2-dropdown {
    border: 1.5px solid #dee2e6 !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
    overflow: hidden !important;
  }
</style>

{{-- ══ Modal Tambah Sumber Dana ══ --}}
<div class="modal fade" id="modalTambahSumberDana" tabindex="-1" aria-labelledby="modalTambahSumberDanaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title fw-bold" id="modalTambahSumberDanaLabel" style="font-size: 1rem;"><i class="bi bi-wallet2 me-2"></i>Tambah Custom Sumber Dana</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formTambahSumberDana">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label for="modal_nama_sumber_dana" class="form-label fw-semibold">Nama Sumber Dana <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-m" id="modal_nama_sumber_dana" required placeholder="Contoh: Dana Hibah Kedaireka / Swasta">
          </div>
          <div class="mb-3">
            <label for="modal_keterangan_sumber_dana" class="form-label fw-semibold">Keterangan</label>
            <textarea class="form-control form-control-m" id="modal_keterangan_sumber_dana" rows="2" placeholder="Masukkan keterangan (opsional)"></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light px-4 py-3">
          <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-sm px-4 text-white" id="btnSimpanSumberDana"><i class="bi bi-check-lg me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize Select2 search
    $('#jenis_dokumen_id').select2({ placeholder: "Pilih Jenis Dokumen" });
    $('#mitra_id').select2({ placeholder: "Pilih Mitra" });
    $('#sumber_dana_id').select2({
      placeholder: "Pilih atau Ketik Sumber Dana",
      tags: true
    });
    $('#status_kerjasama').select2({ placeholder: "Pilih Status" });

    // Handle AJAX Sumber Dana
    $('#formTambahSumberDana').on('submit', function(e) {
      e.preventDefault();
      const nama = $('#modal_nama_sumber_dana').val();
      const ket = $('#modal_keterangan_sumber_dana').val();

      $.ajax({
        url: "{{ route('sumber-dana.ajax-store') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          nama_sumber_dana: nama,
          keterangan: ket
        },
        success: function(res) {
          if (res.status === 'success') {
            const newOption = new Option(res.data.nama_sumber_dana, res.data.id, true, true);
            $('#sumber_dana_id').append(newOption).trigger('change');
            const modalEl = document.getElementById('modalTambahSumberDana');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
            $('#formTambahSumberDana')[0].reset();
          }
        },
        error: function(err) {
          alert('Gagal menambah sumber dana.');
        }
      });
    });
  });

  const selectedFilesContainer = document.getElementById('file-list-container');
  const fileInput = document.getElementById('dokumen_files');
  const dt = new DataTransfer();

  const dropZone = fileInput ? fileInput.closest('.border') : null;
  if (dropZone) {
    ['dragenter', 'dragover'].forEach(eventName => {
      dropZone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.style.borderColor = '#0d6efd';
        dropZone.style.backgroundColor = '#eef5ff';
      }, false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.style.borderColor = '#ced4da';
        dropZone.style.backgroundColor = '#f8f9fa';
      }, false);
    });
    dropZone.addEventListener('drop', (e) => {
      const droppedFiles = e.dataTransfer.files;
      if (droppedFiles && droppedFiles.length > 0) {
        addFiles(droppedFiles);
      }
    });
  }

  function handleFileSelect(input) {
    if (input.files && input.files.length > 0) {
      addFiles(input.files);
    }
  }

  function addFiles(newFiles) {
    for (let i = 0; i < newFiles.length; i++) {
      const file = newFiles[i];
      let exists = false;
      for (let j = 0; j < dt.files.length; j++) {
        if (dt.files[j].name === file.name && dt.files[j].size === file.size) {
          exists = true;
          break;
        }
      }
      if (!exists) {
        dt.items.add(file);
      }
    }
    fileInput.files = dt.files;
    renderFileList();
  }

  function removeSelectedFile(index) {
    dt.items.remove(index);
    fileInput.files = dt.files;
    renderFileList();
  }

  function renderFileList() {
    if (!selectedFilesContainer) return;
    selectedFilesContainer.innerHTML = '';
    if (dt.files.length === 0) return;

    const listGroup = document.createElement('div');
    listGroup.className = 'd-flex flex-column gap-2';

    for (let i = 0; i < dt.files.length; i++) {
      const file = dt.files[i];
      const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
      const isPdf = file.name.toLowerCase().endsWith('.pdf');
      const iconClass = isPdf ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-word text-primary';

      const fileRow = document.createElement('div');
      fileRow.className = 'd-flex align-items-center justify-content-between p-2 px-3 bg-white border rounded-3 shadow-sm';
      fileRow.innerHTML = `
        <div class="d-flex align-items-center gap-3 overflow-hidden me-2">
          <i class="bi ${iconClass}" style="font-size: 1.5rem;"></i>
          <div class="text-truncate">
            <span class="fw-semibold d-block text-dark small text-truncate" style="font-size: 0.85rem;" title="${file.name}">${file.name}</span>
            <span class="text-muted small" style="font-size: 0.75rem;">${fileSizeMB} MB</span>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle d-flex align-items-center justify-content-center p-1" style="width: 28px; height: 28px;" onclick="removeSelectedFile(${i})" title="Hapus File">
          <i class="bi bi-x-lg" style="font-size: 0.8rem;"></i>
        </button>
      `;
      listGroup.appendChild(fileRow);
    }
    selectedFilesContainer.appendChild(listGroup);
  }


  function makeStaffOptionsHtml() {
    let optionsHtml = '<option value=""></option>';
    if (window.staffData && window.staffData.length > 0) {
      window.staffData.forEach(function(st) {
        const nupText = st.nup ? ` (${st.nup})` : '';
        optionsHtml += `<option value="${st.nama_staff}" data-nup="${st.nup || ''}" data-jabatan="${st.jabatan || ''}" data-email="${st.email || ''}" data-hp="${st.nomor_hp || ''}" data-alamat="${st.alamat || ''}">${st.nama_staff}${nupText}</option>`;
      });
    }
    return optionsHtml;
  }

  function initStaffSelect2(element) {
    $(element).select2({
      placeholder: "Cari data staff atau ketik nama...",
      allowClear: true,
      tags: true,
      width: '100%'
    });
  }

  // ══ Dynamic Penanggung Jawab Pihak 1 ══
  let pj1Count = 1;
  document.getElementById('btnTambahPj1').addEventListener('click', function() {
    const block = makePjBlock(1, pj1Count);
    document.getElementById('pj1-wrapper').insertAdjacentHTML('beforeend', block);
    const lastAdded = document.querySelector('#pj1-wrapper .pj-item:last-child .select-pj-nama');
    if (lastAdded) initStaffSelect2(lastAdded);
    pj1Count++;
    renumberPjs(1);
  });

  // ══ Dynamic Penanggung Jawab Pihak 2 ══
  let pj2Count = 1;
  document.getElementById('btnTambahPj2').addEventListener('click', function() {
    const block = makePjBlock(2, pj2Count);
    document.getElementById('pj2-wrapper').insertAdjacentHTML('beforeend', block);
    const lastAdded = document.querySelector('#pj2-wrapper .pj-item:last-child .select-pj-nama');
    if (lastAdded) initStaffSelect2(lastAdded);
    pj2Count++;
    renumberPjs(2);
  });

  // Remove event delegation
  document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-pj')) {
      const item = e.target.closest('.pj-item');
      const pihak = item.getAttribute('data-pihak');
      item.remove();
      renumberPjs(pihak);
    }
  });

  function makePjBlock(pihak, index) {
    const namaFieldHtml = (pihak == 2)
      ? `<input type="text" name="pihak[2][penanggung_jawab][${index}][nama]" class="form-control form-control-m" placeholder="Masukkan Nama Penanggung Jawab" required>`
      : `<select name="pihak[1][penanggung_jawab][${index}][nama]" class="form-select form-control-m select-pj-nama" required>${makeStaffOptionsHtml()}</select>`;

    return `
      <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="${pihak}" data-index="${index}">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="small fw-semibold text-success pj-title">Penanggung Jawab ${index + 1}</span>
          <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="small text-muted mb-1 d-block">Nama *</label>
            ${namaFieldHtml}
          </div>
          <div class="col-md-6 mb-3">
            <label class="small text-muted mb-1 d-block">NUP</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab][${index}][nip]" class="form-control form-control-m" placeholder="Masukkan NUP (opsional)">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3 mb-md-0">
            <label class="small text-muted mb-1 d-block">Jabatan</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab][${index}][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)">
          </div>
          <div class="col-md-4 mb-3 mb-md-0">
            <label class="small text-muted mb-1 d-block">Email</label>
            <input type="email" name="pihak[${pihak}][penanggung_jawab][${index}][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)">
          </div>
          <div class="col-md-4">
            <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab][${index}][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)">
          </div>
        </div>
      </div>`;
  }

  function renumberPjs(pihak) {
    const items = document.querySelectorAll(`#pj${pihak}-wrapper .pj-item`);
    items.forEach((item, i) => {
      item.querySelector('.pj-title').textContent = 'Penanggung Jawab ' + (i + 1);
      item.querySelector('.btn-remove-pj').classList.toggle('d-none', items.length <= 1);
    });
  }

  @php
    $staffList = $staffList ?? \App\Models\Staff::where('status', 'Aktif')->orderBy('nama_staff', 'asc')->get();
  @endphp
  window.staffData = @json($staffList);

  $(document).ready(function() {
    function updateDateLabels() {
      const selectedOption = $('#jenis_dokumen_id option:selected');
      const namaJenis = (selectedOption.data('nama') || selectedOption.text() || '').toLowerCase();
      
      if (namaJenis.includes('ia') || namaJenis.includes('implementation')) {
        $('#label_tanggal_awal').html('Tanggal Awal Pelaksanaan <span class="text-danger">*</span>');
        $('#label_tanggal_akhir').html('Tanggal Akhir Pelaksanaan <span class="text-danger">*</span>');
        $('#container_bidang_implementasi').slideDown();
        $('#bidang_implementasi').prop('required', true);
      } else {
        $('#label_tanggal_awal').html('Tanggal Awal Berlaku <span class="text-danger">*</span>');
        $('#label_tanggal_akhir').html('Tanggal Akhir Berlaku <span class="text-danger">*</span>');
        $('#container_bidang_implementasi').slideUp();
        $('#bidang_implementasi').prop('required', false);
      }
    }

    $('#jenis_dokumen_id').on('change', updateDateLabels);
    updateDateLabels();

    $('.select-pj-nama').each(function() {
      initStaffSelect2(this);
    });
  });

  $(document).on('change select2:select', '.select-pj-nama', function() {
    const selectedVal = $(this).val();
    const item = $(this).closest('.pj-item');
    if (!item.length || !selectedVal) return;

    const selectedOpt = $(this).find('option:selected');
    let nup = selectedOpt.data('nup');
    let jabatan = selectedOpt.data('jabatan');
    let email = selectedOpt.data('email');
    let hp = selectedOpt.data('hp');
    let alamat = selectedOpt.data('alamat');

    if (nup === undefined && jabatan === undefined) {
      const matched = window.staffData.find(s => s.nama_staff.toLowerCase() === selectedVal.trim().toLowerCase());
      if (matched) {
        nup = matched.nup;
        jabatan = matched.jabatan;
        email = matched.email;
        hp = matched.nomor_hp;
        alamat = matched.alamat;
      }
    }

    if (nup !== undefined || jabatan !== undefined || email !== undefined || hp !== undefined) {
      item.find('input[name*="[nip]"]').val(nup || '');
      item.find('input[name*="[jabatan]"]').val(jabatan || '');
      item.find('input[name*="[email]"]').val(email || '');
      item.find('input[name*="[nomor_hp]"]').val(hp || '');
    }
  });
</script>
@endpush
