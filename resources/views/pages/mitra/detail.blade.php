@extends('layouts.dashboard.template')

@section('title', 'Detail Mitra - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Detail Mitra</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('mitra.index') }}">Mitra</a></li>
      <li class="breadcrumb-item active">Detail</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">

      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body px-4 py-4">

          {{-- Header info --}}
          <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-4">
            <div class="d-flex align-items-center gap-3">
              <div class="section-icon-lg">
                <i class="bi bi-building text-secondary" style="font-size: 1.25rem;"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1 text-dark">{{ $mitra->nama_mitra }}</h5>
                <p class="text-muted mb-0 small" style="font-size: 0.8rem;">Detail informasi terkait data mitra dan kontak</p>
              </div>
            </div>
            <div>
              <a href="{{ route('mitra.edit', $mitra->id) }}" class="btn btn-light btn-sm border px-3 d-flex align-items-center gap-1 text-dark" style="border-radius: 6px;">
                <i class="bi bi-pencil-square" style="font-size: 0.9rem;"></i>
                <span class="small fw-semibold">Ubah Data</span>
              </a>
            </div>
          </div>

          {{-- Two columns info list --}}
          <div class="row mb-5 text-dark" style="font-size: 0.875rem; line-height: 1.8;">
            <div class="col-md-6">
              <div class="row mb-2">
                <div class="col-5 text-muted">Jenis Mitra</div>
                <div class="col-7">: {{ $mitra->jenis_mitra }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Nama Mitra</div>
                <div class="col-7">: {{ $mitra->nama_mitra }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Nomor Surat Izin Usaha / Perguruan Tinggi</div>
                <div class="col-7">: {{ $mitra->nomor_izin_usaha ?? '' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">NPWP Mitra</div>
                <div class="col-7">: {{ $mitra->npwp ?? '' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Kriteria Mitra</div>
                <div class="col-7">: {{ $mitra->kriteriaMitra ? $mitra->kriteriaMitra->kriteria_mitra : '' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Lingkup Mitra</div>
                <div class="col-7">: {{ $mitra->lingkup_mitra }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-5 text-muted">Negara</div>
                <div class="col-7">: Indonesia</div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="row mb-2">
                <div class="col-4 text-muted">Kode POS</div>
                <div class="col-8">: {{ $mitra->kodepos ?? '' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Alamat</div>
                <div class="col-8">: 
                  @if($mitra->alamat)
                    {{ $mitra->alamat }}, Kecamatan {{ ucwords(strtolower($mitra->kecamatan)) }}, {{ ucwords(strtolower($mitra->kabupaten_kota)) }}, {{ ucwords(strtolower($mitra->provinsi)) }} {{ $mitra->kodepos }}
                  @else
                    -
                  @endif
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Nomor Telepon</div>
                <div class="col-8">: {{ $mitra->no_telp ?? '' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Email</div>
                <div class="col-8">: {{ $mitra->email ?? '' }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-muted">Link Website</div>
                <div class="col-8">: {{ $mitra->website ?? '' }}</div>
              </div>
            </div>
          </div>

          {{-- Kontak person --}}
          <div class="table-responsive border rounded-3 mb-4">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
              <thead class="table-light text-muted">
                <tr>
                  <th style="width: 60px;" class="ps-3">No</th>
                  <th>Nama Kontak</th>
                  <th>Jabatan</th>
                  <th>Email</th>
                  <th>Nomor Handphone</th>
                </tr>
              </thead>
              <tbody>
                @forelse($mitra->kontakMitras as $index => $kontak)
                  <tr>
                    <td class="ps-3">{{ $index + 1 }}</td>
                    <td class="fw-semibold">{{ $kontak->nama_kontak }}</td>
                    <td>{{ $kontak->jabatan }}</td>
                    <td>{{ $kontak->email }}</td>
                    <td>{{ $kontak->nomor_handphone }}</td>
                  </tr>
                @empty
                  {{-- Empty state row will be shown as a full-span custom layout below or here --}}
                @endforelse
              </tbody>
            </table>
          </div>

          @if($mitra->kontakMitras->isEmpty())
            {{-- Empty state card --}}
            <div class="text-center py-5">
              <div class="mb-3 d-flex justify-content-center">
                {{-- Dynamic SVG empty note illustration --}}
                <svg width="150" height="150" viewBox="0 0 150 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <!-- Clipboard Body -->
                  <rect x="40" y="30" width="70" height="90" rx="8" fill="#F1F3F5" stroke="#CED4DA" stroke-width="2"/>
                  <!-- Clip -->
                  <rect x="63" y="20" width="24" height="14" rx="3" fill="#ADB5BD" stroke="#868E96" stroke-width="2"/>
                  <!-- Line 1 -->
                  <line x1="55" y1="55" x2="95" y2="55" stroke="#DEE2E6" stroke-width="2" stroke-linecap="round"/>
                  <!-- Line 2 -->
                  <line x1="55" y1="70" x2="85" y2="70" stroke="#DEE2E6" stroke-width="2" stroke-linecap="round"/>
                  <!-- Smile Expression -->
                  <path d="M65 92C65 92 68 87 75 87C82 87 85 92 85 92" stroke="#868E96" stroke-width="2" stroke-linecap="round"/>
                  <circle cx="67" cy="81" r="1.5" fill="#868E96"/>
                  <circle cx="83" cy="81" r="1.5" fill="#868E96"/>
                  <!-- Sparkles -->
                  <path d="M120 40L122 45L127 47L122 49L120 54L118 49L113 47L118 45L120 40Z" fill="#FAB005"/>
                  <path d="M25 80L26 83L29 84L26 85L25 88L24 85L21 84L24 83L25 80Z" fill="#FAB005"/>
                </svg>
              </div>
              <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Belum Ada Data kontak Mitra</h6>
              <p class="text-muted small mb-0">Silakan menambah informasi kontak mitra melalui button 'Ubah Data'</p>
            </div>
          @endif

          {{-- Footer navigation --}}
          <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('mitra.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;">
              <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
          </div>

        </div>
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
</style>
@endsection
