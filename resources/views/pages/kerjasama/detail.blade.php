@extends('layouts.dashboard.template')

@section('title', 'Detail Kerjasama - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Detail Kerjasama</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('kerjasama.index') }}">Kerjasama</a></li>
      <li class="breadcrumb-item active">Detail</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">

      {{-- ══ Card 1: Informasi Utama Kerjasama ══ --}}
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body px-4 py-4">

          {{-- Header info --}}
          <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-4">
            <div class="d-flex align-items-center gap-3">
              <div class="section-icon-lg">
                <i class="bi bi-file-earmark-handshake text-secondary" style="font-size: 1.25rem;"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1 text-dark">{{ $kerjasama->judul_kerjasama }}</h5>
                <p class="text-muted mb-0 small" style="font-size: 0.8rem;">Detail informasi terkait data kerjasama dan penanggung jawab</p>
              </div>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary btn-sm px-3 fw-semibold" style="border-radius: 6px; font-size: 0.8rem;">
                <i class="bi bi-plus-lg me-1"></i>Tambah Data Kegiatan
              </button>
              <a href="{{ route('kerjasama.edit', $kerjasama->id) }}" class="btn btn-light btn-sm border px-3 d-flex align-items-center gap-1 text-dark" style="border-radius: 6px;">
                <i class="bi bi-pencil-square" style="font-size: 0.9rem;"></i>
                <span class="small fw-semibold">Ubah Data</span>
              </a>
            </div>
          </div>

          {{-- Two columns info list --}}
          <div class="row mb-4 text-dark" style="font-size: 0.875rem; line-height: 1.8;">
            <div class="col-md-6">
              <div class="row mb-2">
                <div class="col-5 text-muted">Nomor Dokumen Kerjasama</div>
                <div class="col-7">: {{ $kerjasama->nomor_dokumen_kerjasama }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Nomor Dokumen Mitra</div>
                <div class="col-7">: {{ $kerjasama->nomor_dokumen_mitra ?? '-' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Jenis Dokumen</div>
                <div class="col-7">: {{ $kerjasama->jenisDokumen ? $kerjasama->jenisDokumen->nama_jenis_dokumen : '-' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Unit Kerja</div>
                <div class="col-7">: {{ $kerjasama->unitKerja ? $kerjasama->unitKerja->nama_unit_kerja : '-' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Mitra</div>
                <div class="col-7">: 
                  @if($kerjasama->mitra)
                    <a href="{{ route('mitra.show', $kerjasama->mitra_id) }}" class="text-primary fw-semibold" style="text-decoration: underline;">
                      {{ $kerjasama->mitra->nama_mitra }}
                    </a>
                  @else
                    -
                  @endif
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Judul Kerjasama</div>
                <div class="col-7">: {{ $kerjasama->judul_kerjasama }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Deskripsi Kerjasama</div>
                <div class="col-7">: {{ $kerjasama->deskripsi_kerjasama }}</div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="row mb-2">
                <div class="col-4 text-muted">Sumber Dana</div>
                <div class="col-8">: {{ $kerjasama->sumberDana ? $kerjasama->sumberDana->nama_sumber_dana : '-' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Anggaran</div>
                <div class="col-8">: Rp {{ number_format($kerjasama->anggaran, 0, ',', '.') }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Tanggal Awal Berlaku</div>
                <div class="col-8">: {{ $kerjasama->tanggal_waktu_berlaku ? $kerjasama->tanggal_waktu_berlaku->format('d M Y') : '-' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Tanggal Akhir Berlaku</div>
                <div class="col-8">: {{ $kerjasama->tanggal_akhir_berlaku ? $kerjasama->tanggal_akhir_berlaku->format('d M Y') : '-' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Status Kerjasama</div>
                <div class="col-8">: 
                  @if($kerjasama->status_kerjasama === 'Aktif')
                    <span class="badge bg-success-light text-success border border-success-subtle px-2 py-1" style="font-size: 0.75rem; border-radius: 4px;">
                      <i class="bi bi-check-circle-fill me-1"></i> Aktif
                    </span>
                  @else
                    <span class="badge bg-secondary-light text-secondary border border-secondary-subtle px-2 py-1" style="font-size: 0.75rem; border-radius: 4px;">
                      {{ $kerjasama->status_kerjasama }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Hasil Pelaksanaan (Output & Outcome)</div>
                <div class="col-8">: {{ $kerjasama->hasil_pelaksanaan ?? '-' }}</div>
              </div>
            </div>
          </div>

          {{-- Lampiran Dokumen Kerjasama --}}
          <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4" style="font-size: 0.95rem;">Dokumen Kerjasama</h6>
          <div class="card bg-light border-0 mb-0 shadow-none">
            <div class="card-body p-3">
              <span class="small text-muted fw-semibold d-block mb-2">Lampiran</span>
              @if($kerjasama->url_file)
                <div class="d-flex align-items-center justify-content-between p-3 bg-white border rounded-3">
                  <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 2.2rem;"></i>
                    <div>
                      <span class="fw-bold d-block text-dark small" style="font-size: 0.85rem;">{{ basename($kerjasama->url_file) }}</span>
                      <span class="text-muted small" style="font-size: 0.75rem;">Dokumen Kerjasama Kerja Sama</span>
                    </div>
                  </div>
                  <div>
                    <a href="{{ asset('storage/' . $kerjasama->url_file) }}" target="_blank" class="btn btn-light btn-sm border d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 6px;" title="Lihat/Download Dokumen">
                      <i class="bi bi-cloud-arrow-down text-secondary" style="font-size: 1.1rem;"></i>
                    </a>
                  </div>
                </div>
              @else
                <div class="alert alert-warning mb-0 py-2 small" style="border-radius: 6px;">
                  <i class="bi bi-exclamation-triangle-fill me-1"></i> Tidak ada lampiran dokumen diunggah.
                </div>
              @endif
            </div>
          </div>

        </div>
      </div>

      {{-- ══ Card 2: Pihak Penanggung Jawab ══ --}}
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <div class="section-icon"><i class="bi bi-person-lines-fill"></i></div>
            <h6 class="mb-0 fw-semibold text-dark">Pihak Penanggung Jawab</h6>
          </div>
        </div>
        <div class="card-body px-4 py-4">

          {{-- Pihak ke 1 --}}
          <div class="mb-5">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 0.9rem;">Pihak Penanggung Jawab Ke 1</h6>
            <div class="row mb-3 text-dark small" style="line-height: 1.8;">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-4 text-muted">Pihak Unit Kerja</div>
                  <div class="col-8">: {{ $kerjasama->unitKerja ? $kerjasama->unitKerja->nama_unit_kerja : '-' }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-3 text-muted">Alamat</div>
                  <div class="col-9">: {{ $pihak1 ? $pihak1->alamat : '-' }}</div>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.8rem;">
                <thead class="table-light text-muted">
                  <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Nama</th>
                    <th>Nomor Induk Pegawai</th>
                    <th>Jabatan</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                  </tr>
                </thead>
                <tbody>
                  @if($pihak1 && $pihak1->penanggungJawabs->isNotEmpty())
                    @foreach($pihak1->penanggungJawabs as $i => $pj)
                      <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="fw-bold text-success">{{ $pj->nama }}</td>
                        <td>{{ $pj->nip ?? '-' }}</td>
                        <td>{{ $pj->jabatan ?? '-' }}</td>
                        <td>{{ $pj->email ?? '-' }}</td>
                        <td>{{ $pj->nomor_hp ?? '-' }}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3">Belum ada penanggung jawab untuk Pihak 1</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>

          {{-- Pihak ke 2 --}}
          <div>
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 0.9rem;">Pihak Penanggung Jawab Ke 2</h6>
            <div class="row mb-3 text-dark small" style="line-height: 1.8;">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-4 text-muted">Pihak Mitra</div>
                  <div class="col-8">: {{ $kerjasama->mitra ? $kerjasama->mitra->nama_mitra : '-' }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-3 text-muted">Alamat</div>
                  <div class="col-9">: {{ $pihak2 ? $pihak2->alamat : '-' }}</div>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.8rem;">
                <thead class="table-light text-muted">
                  <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Nama</th>
                    <th>Nomor Induk Pegawai</th>
                    <th>Jabatan</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                  </tr>
                </thead>
                <tbody>
                  @if($pihak2 && $pihak2->penanggungJawabs->isNotEmpty())
                    @foreach($pihak2->penanggungJawabs as $i => $pj)
                      <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="fw-bold text-success">{{ $pj->nama }}</td>
                        <td>{{ $pj->nip ?? '-' }}</td>
                        <td>{{ $pj->jabatan ?? '-' }}</td>
                        <td>{{ $pj->email ?? '-' }}</td>
                        <td>{{ $pj->nomor_hp ?? '-' }}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3">Belum ada penanggung jawab untuk Pihak 2</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>

      {{-- Back button --}}
      <div class="d-flex gap-2 mb-4">
        <a href="{{ route('kerjasama.index') }}" class="btn btn-outline-secondary btn-sm px-4" style="border-radius: 6px;">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
      </div>

    </div>
  </div>
</section>

<style>
  .section-icon-lg {
    width: 40px; height: 40px; min-width: 40px;
    background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
  }
  .section-icon {
    width: 32px; height: 32px; background: #e8f5e9; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; color: #157347; font-size: 1rem;
  }
  .bg-success-light { background-color: #d1e7dd !important; }
  .bg-secondary-light { background-color: #e2e3e5 !important; }
</style>
@endsection
