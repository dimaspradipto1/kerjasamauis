@extends('layouts.dashboard.template')

@section('title', 'Detail Sasaran Kinerja - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Detail Sasaran Kinerja</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item">Data Referensi</li>
      <li class="breadcrumb-item"><a href="{{ route('sasaran-kinerja.index') }}">Sasaran Kinerja</a></li>
      <li class="breadcrumb-item active">Detail Detail Sasaran Kinerja</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="col-lg-12">

    {{-- Header card --}}
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body px-4 py-4">

        {{-- Header --}}
        <div class="d-flex align-items-start gap-3 pb-3 border-bottom mb-4">
          <div class="section-icon-lg">
            <i class="bi bi-bar-chart-line-fill"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-1 text-dark">{{ $sasaran->sasaran_kinerja }}</h5>
            <p class="text-muted mb-0 small">Detail informasi terkait data sasaran kinerja dan indikator sasaran</p>
          </div>
        </div>

        {{-- Detail rows --}}
        <table class="table table-borderless mb-4" style="max-width: 700px;">
          <tbody>
            <tr>
              <td class="text-muted fw-medium" style="width: 160px;">Sasaran Kinerja</td>
              <td class="text-muted">:</td>
              <td>{{ $sasaran->sasaran_kinerja }}</td>
            </tr>
            <tr>
              <td class="text-muted fw-medium">Keterangan</td>
              <td class="text-muted">:</td>
              <td>{{ $sasaran->keterangan ?? '-' }}</td>
            </tr>
            <tr>
              <td class="text-muted fw-medium">Level</td>
              <td class="text-muted">:</td>
              <td>
                @if($sasaran->level)
                  <span class="text-success fw-semibold">{{ $sasaran->level }}</span>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
            </tr>
          </tbody>
        </table>

        {{-- Indikator table --}}
        <h6 class="fw-bold mb-3">Daftar Indikator Sasaran</h6>
        @if($sasaran->indikatorSasarans->isEmpty())
          <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i> Belum ada indikator sasaran.
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width: 50px;">No</th>
                  <th style="width: 200px;">Indikator</th>
                  <th>Keterangan</th>
                  <th style="width: 90px;" class="text-center">Volume</th>
                  <th style="width: 120px;">Satuan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($sasaran->indikatorSasarans as $i => $ind)
                <tr>
                  <td class="text-center">{{ $i + 1 }}</td>
                  <td class="fw-medium text-primary">{{ $ind->indikator_sasaran }}</td>
                  <td class="text-muted small">{{ $ind->keterangan ?? '-' }}</td>
                  <td class="text-center">{{ $ind->volume ?? 0 }}</td>
                  <td>{{ $ind->satuan ?? '-' }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif

        {{-- Actions --}}
        <div class="d-flex gap-2 mt-3">
          <a href="{{ route('sasaran-kinerja.index') }}" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <a href="{{ route('sasaran-kinerja.edit', $sasaran->id) }}" class="btn btn-warning btn-sm px-3 text-white">
            <i class="bi bi-pencil-square me-1"></i> Edit
          </a>
        </div>

      </div>
    </div>

  </div>
</section>

<style>
  .section-icon-lg {
    width: 44px; height: 44px; min-width: 44px;
    background: #e8f5e9; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #157347; font-size: 1.3rem;
  }
</style>
@endsection
