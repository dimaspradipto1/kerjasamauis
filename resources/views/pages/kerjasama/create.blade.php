@extends('layouts.dashboard.template')

@section('title', 'Tambah Kerjasama - SIM Kerjasama UIS')

@section('content')
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
                  <option value="{{ $jd->id }}" {{ old('jenis_dokumen_id') == $jd->id ? 'selected' : '' }}>{{ $jd->nama_jenis_dokumen }}</option>
                @endforeach
              </select>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="unit_kerja_id" class="form-label fw-semibold">Unit Kerja <span class="text-danger">*</span></label>
                <select name="unit_kerja_id" id="unit_kerja_id" class="form-select form-control-m" required>
                  <option value="" disabled selected>Pilih Unit Kerja</option>
                  @foreach($unitKerjas as $uk)
                    <option value="{{ $uk->id }}" {{ old('unit_kerja_id') == $uk->id ? 'selected' : '' }}>{{ $uk->nama_unit_kerja }}</option>
                  @endforeach
                </select>
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

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="sumber_dana_id" class="form-label fw-semibold">Sumber Dana <span class="text-danger">*</span></label>
                <select name="sumber_dana_id" id="sumber_dana_id" class="form-select form-control-m" required>
                  <option value="" disabled selected>Pilih Sumber Dana</option>
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
                <label for="tanggal_waktu_berlaku" class="form-label fw-semibold">Tanggal Awal Berlaku <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_waktu_berlaku" id="tanggal_waktu_berlaku" class="form-control form-control-m" required value="{{ old('tanggal_waktu_berlaku') }}">
              </div>
              <div class="col-md-6">
                <label for="tanggal_akhir_berlaku" class="form-label fw-semibold">Tanggal Akhir Berlaku <span class="text-danger">*</span></label>
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
              <div class="border rounded-3 p-4 text-center bg-light" style="border: 2px dashed #ced4da !important; cursor: pointer;" onclick="document.getElementById('dokumen_file').click();">
                <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 2.5rem;"></i>
                <p class="mb-1 small fw-semibold mt-2">Klik untuk pilih file atau seret file ke sini</p>
                <p class="text-muted small mb-0" style="font-size: 0.75rem;">PDF, DOC atau DOCX (Max. 5 MB)</p>
                <input type="file" name="dokumen_file" id="dokumen_file" class="d-none" accept=".pdf,.doc,.docx" onchange="updateFileNameLabel(this)">
                <div id="file-chosen-label" class="mt-2 text-success small fw-semibold"></div>
              </div>
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
                    <input type="text" name="pihak[1][penanggung_jawab][0][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required value="{{ old('pihak.1.penanggung_jawab.0.nama') }}">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="pihak[1][penanggung_jawab][0][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)" value="{{ old('pihak.1.penanggung_jawab.0.nip') }}">
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
                    <input type="text" name="pihak[2][penanggung_jawab][0][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required value="{{ old('pihak.2.penanggung_jawab.0.nama') }}">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="pihak[2][penanggung_jawab][0][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)" value="{{ old('pihak.2.penanggung_jawab.0.nip') }}">
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
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize Select2 search
    $('#jenis_dokumen_id').select2({ placeholder: "Pilih Jenis Dokumen" });
    $('#unit_kerja_id').select2({ placeholder: "Pilih Unit Kerja" });
    $('#mitra_id').select2({ placeholder: "Pilih Mitra" });
    $('#sumber_dana_id').select2({ placeholder: "Pilih Sumber Dana" });
    $('#status_kerjasama').select2({ placeholder: "Pilih Status" });
  });

  function updateFileNameLabel(input) {
    const label = document.getElementById('file-chosen-label');
    if (input.files && input.files[0]) {
      label.textContent = 'Terpilih: ' + input.files[0].name + ' (' + (input.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
    } else {
      label.textContent = '';
    }
  }

  // ══ Dynamic Penanggung Jawab Pihak 1 ══
  let pj1Count = 1;
  document.getElementById('btnTambahPj1').addEventListener('click', function() {
    const block = makePjBlock(1, pj1Count);
    document.getElementById('pj1-wrapper').insertAdjacentHTML('beforeend', block);
    pj1Count++;
    renumberPjs(1);
  });

  // ══ Dynamic Penanggung Jawab Pihak 2 ══
  let pj2Count = 1;
  document.getElementById('btnTambahPj2').addEventListener('click', function() {
    const block = makePjBlock(2, pj2Count);
    document.getElementById('pj2-wrapper').insertAdjacentHTML('beforeend', block);
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
    return `
      <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="${pihak}" data-index="${index}">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="small fw-semibold text-success pj-title">Penanggung Jawab ${index + 1}</span>
          <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="small text-muted mb-1 d-block">Nama *</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab][${index}][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab][${index}][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)">
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
</script>
@endpush
