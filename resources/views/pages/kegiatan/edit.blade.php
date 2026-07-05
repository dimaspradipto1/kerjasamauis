@extends('layouts.dashboard.template')

@section('title', 'Edit Kegiatan - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Edit Kegiatan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}">Kegiatan</a></li>
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

      <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ══ Section 1: Informasi Kegiatan ══ --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="section-icon"><i class="bi bi-clipboard-data"></i></div>
              <h6 class="mb-0 fw-semibold text-dark">Informasi Kegiatan</h6>
            </div>
          </div>
          <div class="card-body px-4 py-4">

            <div class="mb-4">
              <label for="kerjasama_id" class="form-label fw-semibold">Induk Kerjasama <span class="text-danger">*</span></label>
              <select name="kerjasama_id" id="kerjasama_id" class="form-select form-control-m" required>
                <option value="" disabled>Pilih Induk Kerjasama</option>
                @foreach($kerjasamas as $k)
                  <option value="{{ $k->id }}" {{ old('kerjasama_id', $kegiatan->kerjasama_id) == $k->id ? 'selected' : '' }}>{{ $k->judul_kerjasama }}</option>
                @endforeach
              </select>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="nomor_dokumen_kegiatan" class="form-label fw-semibold">Nomor Dokumen Kegiatan</label>
                <input type="text" name="nomor_dokumen_kegiatan" id="nomor_dokumen_kegiatan" class="form-control form-control-m" placeholder="Masukkan Nomor Dokumen Kegiatan (opsional)" value="{{ old('nomor_dokumen_kegiatan', $kegiatan->nomor_dokumen_kegiatan) }}">
              </div>
              <div class="col-md-6">
                <label for="nomor_dokumen_mitra" class="form-label fw-semibold">Nomor Dokumen Mitra</label>
                <input type="text" name="nomor_dokumen_mitra" id="nomor_dokumen_mitra" class="form-control form-control-m" placeholder="Masukkan Nomor Dokumen Mitra (opsional)" value="{{ old('nomor_dokumen_mitra', $kegiatan->nomor_dokumen_mitra) }}">
              </div>
            </div>

            <div class="mb-4">
              <label for="unit_kerja_id" class="form-label fw-semibold">Unit Kerja Pengusul <span class="text-danger">*</span></label>
              <select name="unit_kerja_id" id="unit_kerja_id" class="form-select form-control-m" required>
                <option value="" disabled>Pilih Unit Kerja Pengusul</option>
                @foreach($unitKerjas as $u)
                  <option value="{{ $u->id }}" {{ old('unit_kerja_id', $kegiatan->unit_kerja_id) == $u->id ? 'selected' : '' }}>{{ $u->nama_unit_kerja }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4">
              <label for="judul_kegiatan" class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
              <textarea name="judul_kegiatan" id="judul_kegiatan" class="form-control form-control-m" rows="2" placeholder="Masukkan Judul Kegiatan" required>{{ old('judul_kegiatan', $kegiatan->judul_kegiatan) }}</textarea>
            </div>

            <div class="mb-4">
              <label for="mitra_name_display" class="form-label fw-semibold">Mitra</label>
              <input type="text" id="mitra_name_display" class="form-control form-control-m bg-light text-muted" readonly placeholder="Mitra otomatis terpilih setelah Induk Kerjasama dipilih" value="{{ $kegiatan->mitra ? $kegiatan->mitra->nama_mitra : '' }}">
              <input type="hidden" name="mitra_id" id="mitra_id" value="{{ old('mitra_id', $kegiatan->mitra_id) }}">
            </div>

            <div class="mb-4">
              <label for="bentuk_kegiatan_id" class="form-label fw-semibold">Bentuk Kegiatan <span class="text-danger">*</span></label>
              <select name="bentuk_kegiatan_id" id="bentuk_kegiatan_id" class="form-select form-control-m" required>
                <option value="" disabled>Pilih Bentuk Kegiatan</option>
                @foreach($bentukKegiatans as $bk)
                  <option value="{{ $bk->id }}" {{ old('bentuk_kegiatan_id', $kegiatan->bentuk_kegiatan_id) == $bk->id ? 'selected' : '' }}>{{ $bk->nama_bentuk_kegiatan }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4">
              <label for="sasaran_kinerja_id" class="form-label fw-semibold">Sasaran Kinerja <span class="text-danger">*</span></label>
              <select name="sasaran_kinerja_id" id="sasaran_kinerja_id" class="form-select form-control-m" required>
                <option value="" disabled>Pilih Sasaran Kinerja</option>
                @foreach($sasaranKinerjas as $sk)
                  <option value="{{ $sk->id }}" {{ old('sasaran_kinerja_id', $kegiatan->sasaran_kinerja_id) == $sk->id ? 'selected' : '' }}>{{ $sk->sasaran_kinerja }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4">
              <label for="indikator_id" class="form-label fw-semibold">Indikator Sasaran <span class="text-danger">*</span></label>
              <select name="indikator_id" id="indikator_id" class="form-select form-control-m" required>
                <option value="" disabled>Pilih Indikator Sasaran</option>
                @foreach($indikatorSasarans as $is)
                  <option value="{{ $is->id }}" {{ old('indikator_id', $kegiatan->indikator_id) == $is->id ? 'selected' : '' }}>{{ $is->indikator_sasaran }}</option>
                @endforeach
              </select>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="tanggal_awal_kegiatan" class="form-label fw-semibold">Tanggal Awal Kegiatan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_awal_kegiatan" id="tanggal_awal_kegiatan" class="form-control form-control-m" required value="{{ old('tanggal_awal_kegiatan', $kegiatan->tanggal_awal_kegiatan ? $kegiatan->tanggal_awal_kegiatan->format('Y-m-d') : '') }}">
              </div>
              <div class="col-md-6">
                <label for="tanggal_akhir_kegiatan" class="form-label fw-semibold">Tanggal Akhir Kegiatan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_akhir_kegiatan" id="tanggal_akhir_kegiatan" class="form-control form-control-m" required value="{{ old('tanggal_akhir_kegiatan', $kegiatan->tanggal_akhir_kegiatan ? $kegiatan->tanggal_akhir_kegiatan->format('Y-m-d') : '') }}">
              </div>
            </div>

            <div class="mb-4">
              <label for="ruang_lingkup" class="form-label fw-semibold">Ruang Lingkup</label>
              <textarea name="ruang_lingkup" id="ruang_lingkup" class="form-control form-control-m" rows="2" placeholder="Masukkan Ruang Lingkup (opsional)">{{ old('ruang_lingkup', $kegiatan->ruang_lingkup) }}</textarea>
            </div>

            <div class="mb-4">
              <label for="hasil_pelakasanaan" class="form-label fw-semibold">Hasil Pelaksanaan (Output & Outcome)</label>
              <textarea name="hasil_pelakasanaan" id="hasil_pelakasanaan" class="form-control form-control-m" rows="3" placeholder="Masukkan Hasil Pelaksanaan (Output & Outcome) (opsional)">{{ old('hasil_pelakasanaan', $kegiatan->hasil_pelakasanaan) }}</textarea>
            </div>

            <div class="mb-4">
              <label for="nilai_kontrak" class="form-label fw-semibold">Nilai Kontrak (Rp)</label>
              <input type="number" name="nilai_kontrak" id="nilai_kontrak" class="form-control form-control-m" placeholder="Masukkan Nilai Kontrak (Rp) (opsional)" value="{{ old('nilai_kontrak', $kegiatan->nilai_kontrak) }}">
            </div>

            <div class="mb-4">
              <label for="link_dokumen_kegiatan" class="form-label fw-semibold">Tautan / Link Dokumentasi Kegiatan</label>
              <input type="text" name="link_dokumen_kegiatan" id="link_dokumen_kegiatan" class="form-control form-control-m" placeholder="Masukkan Tautan / Link Dokumentasi Kegiatan (opsional)" value="{{ old('link_dokumen_kegiatan', $kegiatan->link_dokumen_kegiatan) }}">
            </div>

            <div class="mb-2">
              <label class="form-label fw-semibold">Dokumen</label>
              <div class="border rounded-3 p-4 text-center bg-light" style="border: 2px dashed #ced4da !important; cursor: pointer;" onclick="document.getElementById('dokumen_file').click();">
                <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 2.5rem;"></i>
                <p class="mb-1 small fw-semibold mt-2">Klik untuk pilih file atau seret file ke sini</p>
                <p class="text-muted small mb-0" style="font-size: 0.75rem;">PDF, DOC atau DOCX (Max. 5 MB)</p>
                <input type="file" name="dokumen_file" id="dokumen_file" class="d-none" accept=".pdf,.doc,.docx" onchange="updateFileNameLabel(this)">
                <div id="file-chosen-label" class="mt-2 text-success small fw-semibold">
                  @if($kegiatan->url_file)
                    File saat ini: [{{ basename($kegiatan->url_file) }}] (Biarkan kosong jika tidak ingin mengubah)
                  @endif
                </div>
              </div>
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
                <input class="form-check-input" type="radio" name="pihak[1][jenis_pihak]" id="pihak1_unit" value="Unit" {{ old('pihak.1.jenis_pihak', $pihak1 ? $pihak1->jenis_pihak : 'Unit') === 'Unit' ? 'checked' : '' }} required>
                <label class="form-check-label" for="pihak1_unit">Unit</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pihak[1][jenis_pihak]" id="pihak1_mitra" value="Mitra" {{ old('pihak.1.jenis_pihak', $pihak1 ? $pihak1->jenis_pihak : 'Unit') === 'Mitra' ? 'checked' : '' }}>
                <label class="form-check-label" for="pihak1_mitra">Mitra</label>
              </div>
            </div>

            <div class="mb-4">
              <label for="pihak1_pj" class="form-label fw-semibold">Pihak Penanggung Jawab <span class="text-danger">*</span></label>
              <input type="text" name="pihak[1][penanggung_jawab]" id="pihak1_pj" class="form-control form-control-m" placeholder="Masukkan Pihak Penanggung Jawab" required value="{{ old('pihak.1.penanggung_jawab', $pihak1 ? $pihak1->penanggung_jawab : '') }}">
            </div>

            {{-- PJ List Pihak 1 --}}
            <h6 class="fw-bold text-success mb-3 mt-4" style="font-size: 0.9rem;">Penanggung Jawab Pihak 1</h6>
            <div id="pj1-wrapper">
              @php $pj1List = $pihak1 ? $pihak1->penanggungJawabs : collect([]); @endphp
              @forelse($pj1List as $i => $pj)
              <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="1" data-index="{{ $i }}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="small fw-semibold text-success pj-title">Penanggung Jawab {{ $i + 1 }}</span>
                  <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj {{ $loop->count <= 1 ? 'd-none' : '' }}" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nama *</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][{{ $i }}][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required value="{{ old('pihak.1.penanggung_jawab_pjs.'.$i.'.nama', $pj->nama) }}">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][{{ $i }}][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)" value="{{ old('pihak.1.penanggung_jawab_pjs.'.$i.'.nip', $pj->nip) }}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Jabatan</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][{{ $i }}][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)" value="{{ old('pihak.1.penanggung_jawab_pjs.'.$i.'.jabatan', $pj->jabatan) }}">
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Email</label>
                    <input type="email" name="pihak[1][penanggung_jawab_pjs][{{ $i }}][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)" value="{{ old('pihak.1.penanggung_jawab_pjs.'.$i.'.email', $pj->email) }}">
                  </div>
                  <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][{{ $i }}][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)" value="{{ old('pihak.1.penanggung_jawab_pjs.'.$i.'.nomor_hp', $pj->nomor_hp) }}">
                  </div>
                </div>
              </div>
              @empty
              <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="1" data-index="0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="small fw-semibold text-success pj-title">Penanggung Jawab 1</span>
                  <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj d-none" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nama *</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][0][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][0][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Jabatan</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][0][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)">
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Email</label>
                    <input type="email" name="pihak[1][penanggung_jawab_pjs][0][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)">
                  </div>
                  <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                    <input type="text" name="pihak[1][penanggung_jawab_pjs][0][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)">
                  </div>
                </div>
              </div>
              @endforelse
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
                <input class="form-check-input" type="radio" name="pihak[2][jenis_pihak]" id="pihak2_unit" value="Unit" {{ old('pihak.2.jenis_pihak', $pihak2 ? $pihak2->jenis_pihak : 'Mitra') === 'Unit' ? 'checked' : '' }} required>
                <label class="form-check-label" for="pihak2_unit">Unit</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pihak[2][jenis_pihak]" id="pihak2_mitra" value="Mitra" {{ old('pihak.2.jenis_pihak', $pihak2 ? $pihak2->jenis_pihak : 'Mitra') === 'Mitra' ? 'checked' : '' }}>
                <label class="form-check-label" for="pihak2_mitra">Mitra</label>
              </div>
            </div>

            <div class="mb-4">
              <label for="pihak2_pj" class="form-label fw-semibold">Pihak Penanggung Jawab <span class="text-danger">*</span></label>
              <input type="text" name="pihak[2][penanggung_jawab]" id="pihak2_pj" class="form-control form-control-m" placeholder="Masukkan Pihak Penanggung Jawab" required value="{{ old('pihak.2.penanggung_jawab', $pihak2 ? $pihak2->penanggung_jawab : '') }}">
            </div>

            {{-- PJ List Pihak 2 --}}
            <h6 class="fw-bold text-success mb-3 mt-4" style="font-size: 0.9rem;">Penanggung Jawab Pihak 2</h6>
            <div id="pj2-wrapper">
              @php $pj2List = $pihak2 ? $pihak2->penanggungJawabs : collect([]); @endphp
              @forelse($pj2List as $i => $pj)
              <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="2" data-index="{{ $i }}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="small fw-semibold text-success pj-title">Penanggung Jawab {{ $i + 1 }}</span>
                  <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj {{ $loop->count <= 1 ? 'd-none' : '' }}" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nama *</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][{{ $i }}][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required value="{{ old('pihak.2.penanggung_jawab_pjs.'.$i.'.nama', $pj->nama) }}">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][{{ $i }}][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)" value="{{ old('pihak.2.penanggung_jawab_pjs.'.$i.'.nip', $pj->nip) }}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Jabatan</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][{{ $i }}][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)" value="{{ old('pihak.2.penanggung_jawab_pjs.'.$i.'.jabatan', $pj->jabatan) }}">
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Email</label>
                    <input type="email" name="pihak[2][penanggung_jawab_pjs][{{ $i }}][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)" value="{{ old('pihak.2.penanggung_jawab_pjs.'.$i.'.email', $pj->email) }}">
                  </div>
                  <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][{{ $i }}][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)" value="{{ old('pihak.2.penanggung_jawab_pjs.'.$i.'.nomor_hp', $pj->nomor_hp) }}">
                  </div>
                </div>
              </div>
              @empty
              <div class="pj-item border rounded-3 p-3 mb-3 position-relative" data-pihak="2" data-index="0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="small fw-semibold text-success pj-title">Penanggung Jawab 1</span>
                  <button type="button" class="btn btn-link text-danger p-0 btn-remove-pj d-none" style="text-decoration: none;"><i class="bi bi-trash3-fill me-1"></i>Hapus</button>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nama *</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][0][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][0][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Jabatan</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][0][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)">
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small text-muted mb-1 d-block">Email</label>
                    <input type="email" name="pihak[2][penanggung_jawab_pjs][0][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)">
                  </div>
                  <div class="col-md-4">
                    <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                    <input type="text" name="pihak[2][penanggung_jawab_pjs][0][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)">
                  </div>
                </div>
              </div>
              @endforelse
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
          <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">
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
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize Select2 search
    $('#kerjasama_id').select2({ placeholder: "Pilih Induk Kerjasama" });
    $('#unit_kerja_id').select2({ placeholder: "Pilih Unit Kerja Pengusul" });
    $('#bentuk_kegiatan_id').select2({ placeholder: "Pilih Bentuk Kegiatan" });
    $('#sasaran_kinerja_id').select2({ placeholder: "Pilih Sasaran Kinerja" });
    $('#indikator_id').select2({ placeholder: "Pilih Indikator Sasaran" });

    // AJAX load Mitra dynamically on Induk Kerjasama change
    $('#kerjasama_id').on('change', function() {
      const kerjasamaId = $(this).val();
      if (kerjasamaId) {
        fetch(`{{ url('kegiatan/ajax/mitra-by-kerjasama') }}/${kerjasamaId}`)
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              $('#mitra_name_display').val(data.nama);
              $('#mitra_id').val(data.id);
            } else {
              $('#mitra_name_display').val('');
              $('#mitra_id').val('');
            }
          })
          .catch(err => console.error('Error fetching Mitra:', err));
      }
    });

    // Dynamic indicators loader on Sasaran Kinerja change
    $('#sasaran_kinerja_id').on('change', function() {
      const sasaranKinerjaId = $(this).val();
      const selectIndikator = $('#indikator_id');
      const currentVal = "{{ $kegiatan->indikator_id }}";
      
      if (sasaranKinerjaId) {
        fetch(`{{ url('kegiatan/ajax/indikator-by-sasaran') }}/${sasaranKinerjaId}`)
          .then(res => res.json())
          .then(data => {
            selectIndikator.html('<option value="" disabled>Pilih Indikator Sasaran</option>');
            data.forEach(ind => {
              const option = document.createElement('option');
              option.value = ind.id;
              option.textContent = ind.indikator_sasaran;
              if (ind.id == currentVal) {
                option.selected = true;
              }
              selectIndikator.append(option);
            });
            selectIndikator.prop('disabled', false);
            selectIndikator.trigger('change');
          })
          .catch(err => console.error('Error loading indicators:', err));
      }
    });
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
  let pj1Count = {{ $pj1List->count() ?: 1 }};
  document.getElementById('btnTambahPj1').addEventListener('click', function() {
    const block = makePjBlock(1, pj1Count);
    document.getElementById('pj1-wrapper').insertAdjacentHTML('beforeend', block);
    pj1Count++;
    renumberPjs(1);
  });

  // ══ Dynamic Penanggung Jawab Pihak 2 ══
  let pj2Count = {{ $pj2List->count() ?: 1 }};
  document.getElementById('btnTambahPj2').addEventListener('click', function() {
    const block = makePjBlock(2, pj2Count);
    document.getElementById('pj2-wrapper').insertAdjacentHTML('beforeend', block);
    pj2Count++;
    renumberPjs(2);
  });

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
            <input type="text" name="pihak[${pihak}][penanggung_jawab_pjs][${index}][nama]" class="form-control form-control-m" placeholder="Masukkan Nama" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="small text-muted mb-1 d-block">Nomor Induk Pegawai (NIP)</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab_pjs][${index}][nip]" class="form-control form-control-m" placeholder="Masukkan Nomor Induk Pegawai (opsional)">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3 mb-md-0">
            <label class="small text-muted mb-1 d-block">Jabatan</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab_pjs][${index}][jabatan]" class="form-control form-control-m" placeholder="Masukkan Jabatan (opsional)">
          </div>
          <div class="col-md-4 mb-3 mb-md-0">
            <label class="small text-muted mb-1 d-block">Email</label>
            <input type="email" name="pihak[${pihak}][penanggung_jawab_pjs][${index}][email]" class="form-control form-control-m" placeholder="Masukkan Email (opsional)">
          </div>
          <div class="col-md-4">
            <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
            <input type="text" name="pihak[${pihak}][penanggung_jawab_pjs][${index}][nomor_hp]" class="form-control form-control-m" placeholder="Masukkan Nomor Telepon (opsional)">
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
