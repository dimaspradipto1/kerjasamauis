@extends('layouts.dashboard.template')

@section('title', 'Laporan Kerjasama - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle mb-3">
  <h1>Laporan Kerjasama</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Laporan Kerjasama</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">

      {{-- Card Form Laporan --}}
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body px-4 py-4">

          {{-- Header info --}}
          <div class="d-flex align-items-center gap-3 pb-3 border-bottom mb-4">
            <div class="section-icon-lg d-flex align-items-center justify-content-center bg-light border" style="width: 44px; height: 44px; border-radius: 50%;">
              <i class="bi bi-printer text-dark" style="font-size: 1.15rem;"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-1 text-dark">Cetak Laporan Kerjasama</h5>
              <p class="text-muted mb-0 small" style="font-size: 0.8rem;">Anda dapat mencetak laporan kerjasama sesuai dengan filter</p>
            </div>
          </div>

          <form action="{{ route('laporan.index') }}" method="GET" id="reportForm">
            <input type="hidden" name="tampilkan" value="1">

            {{-- Row 1: Tanggal Awal & Akhir --}}
            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="tanggal_awal" class="form-label fw-semibold">Tanggal Awal Kerjasama <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control form-control-m" required value="{{ request('tanggal_awal') }}">
              </div>
              <div class="col-md-6">
                <label for="tanggal_akhir" class="form-label fw-semibold">Tanggal Akhir Kerjasama <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control form-control-m" required value="{{ request('tanggal_akhir') }}">
              </div>
            </div>

            {{-- Row 2: Jenis Dokumen --}}
            <div class="mb-4">
              <label for="jenis_dokumen_id" class="form-label fw-semibold">Jenis Dokumen Kerjasama <span class="text-danger">*</span></label>
              <select name="jenis_dokumen_id" id="jenis_dokumen_id" class="form-select form-control-m">
                <option value="">Semua Jenis Dokumen Kerjasama</option>
                @foreach($jenisDokumens as $jd)
                  <option value="{{ $jd->id }}" {{ request('jenis_dokumen_id') == $jd->id ? 'selected' : '' }}>{{ $jd->nama_jenis_dokumen }}</option>
                @endforeach
              </select>
            </div>

            {{-- Row 3: Unit Kerja --}}
            <div class="mb-4">
              <label for="unit_kerja_id" class="form-label fw-semibold">Unit Kerja</label>
              <select name="unit_kerja_id" id="unit_kerja_id" class="form-select form-control-m">
                <option value="">Semua Unit Kerja</option>
                @foreach($unitKerjas as $uk)
                  <option value="{{ $uk->id }}" {{ request('unit_kerja_id') == $uk->id ? 'selected' : '' }}>{{ $uk->nama_unit_kerja }}</option>
                @endforeach
              </select>
            </div>

            {{-- Row 4: Status Kerjasama --}}
            <div class="mb-4">
              <label for="status_kerjasama" class="form-label fw-semibold">Status Kerjasama</label>
              <select name="status_kerjasama" id="status_kerjasama" class="form-select form-control-m">
                <option value="">Semua Status Kerjasama</option>
                @foreach($statuses as $st)
                  <option value="{{ $st }}" {{ request('status_kerjasama') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
              </select>
            </div>

            {{-- Row 5: Gunakan Kop --}}
            <div class="mb-4">
              <label class="form-label fw-semibold d-block">Gunakan Kop</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="gunakan_kop" id="gunakan_kop" value="1" {{ request('gunakan_kop') ? 'checked' : '' }}>
                <label class="form-check-label small text-muted" for="gunakan_kop">
                  Menggunakan KOP Laporan
                </label>
              </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-end gap-2">
              <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 text-white px-4">
                <i class="bi bi-eye"></i> Tampilkan
              </button>
              <button type="button" id="btnCetakTab" class="btn btn-outline-primary d-flex align-items-center gap-2 px-4">
                <i class="bi bi-box-arrow-up-right"></i> Lihat di Tab Baru
              </button>
            </div>

          </form>
        </div>
      </div>

      {{-- Preview Hasil Laporan (Jika Ditemukan) --}}
      @if(request()->has('tampilkan') && isset($results))
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <h6 class="mb-0 fw-bold text-dark">Hasil Preview Laporan ({{ $results->count() }} data ditemukan)</h6>
          </div>
          <div class="card-body p-4 bg-white">
            @if($results->isEmpty())
              <div class="alert alert-info mb-0 text-center py-4">
                <i class="bi bi-info-circle-fill me-2 fs-5"></i> Tidak ada data kerjasama ditemukan pada rentang filter ini.
              </div>
            @else
              @php
                if (!function_exists('tgl_indo_preview')) {
                    function tgl_indo_preview($date_str) {
                        if (!$date_str) return '-';
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        $time = strtotime($date_str);
                        $d = date('d', $time);
                        $m = (int)date('m', $time);
                        $y = date('Y', $time);
                        return "$d {$months[$m]} $y";
                    }
                }
              @endphp

              {{-- Centered Title --}}
              <div class="text-center my-4">
                <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.3rem;">Laporan Kerjasama</h4>
              </div>

              {{-- Metadata Info --}}
              <div class="mb-4">
                <table class="table table-borderless w-auto mb-0" style="font-size: 0.85rem; line-height: 1.8;">
                  <tr>
                    <td class="ps-0 py-1" style="width: 200px; color: #555;">Tanggal Kerjasama</td>
                    <td class="py-1" style="width: 20px;">:</td>
                    <td class="py-1 fw-bold text-dark">{{ tgl_indo_preview(request('tanggal_awal')) }} s.d. {{ tgl_indo_preview(request('tanggal_akhir')) }}</td>
                  </tr>
                  <tr>
                    <td class="ps-0 py-1" style="color: #555;">Jenis Dokumen Kerjasama</td>
                    <td class="py-1">:</td>
                    <td class="py-1 fw-medium text-dark">{{ $jenisDokumenNama }}</td>
                  </tr>
                  <tr>
                    <td class="ps-0 py-1" style="color: #555;">Unit Kerja</td>
                    <td class="py-1">:</td>
                    <td class="py-1 fw-medium text-dark">{{ $unitKerjaNama }}</td>
                  </tr>
                  <tr>
                    <td class="ps-0 py-1" style="color: #555;">Status Kerjasama</td>
                    <td class="py-1">:</td>
                    <td class="py-1 fw-medium text-dark">{{ request('status_kerjasama') ?: 'Semua Status Kerjasama' }}</td>
                  </tr>
                </table>
              </div>

              {{-- Responsive Table --}}
              <div class="table-responsive">
                <table class="table table-bordered align-middle" style="font-size: 0.8rem;">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center" style="width: 50px;">No</th>
                      <th>Unit Kerja</th>
                      <th>Mitra</th>
                      <th>Judul Kerjasama</th>
                      <th>Durasi Kerjasama</th>
                      <th>Sumber Dana</th>
                      <th style="text-align: right;">Anggaran (Rp)</th>
                      <th class="text-center">Status Kerjasama</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($results as $i => $row)
                      <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $row->unitKerja ? $row->unitKerja->nama_unit_kerja : '-' }}</td>
                        <td class="fw-bold text-primary">{{ $row->mitra ? $row->mitra->nama_mitra : '-' }}</td>
                        <td>{{ $row->judul_kerjasama }}</td>
                        <td>
                          <span class="fw-bold">
                            {{ $row->tanggal_waktu_berlaku ? tgl_indo_preview($row->tanggal_waktu_berlaku->toDateString()) : '-' }} 
                            s.d. 
                            {{ $row->tanggal_akhir_berlaku ? tgl_indo_preview($row->tanggal_akhir_berlaku->toDateString()) : '-' }}
                          </span>
                        </td>
                        <td>{{ $row->sumberDana ? $row->sumberDana->nama_sumber_dana : '-' }}</td>
                        <td class="text-end">{{ number_format($row->anggaran, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $row->status_kerjasama }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      @endif

    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize select2
    $('#jenis_dokumen_id').select2({ placeholder: "Semua Jenis Dokumen Kerjasama", allowClear: true });
    $('#unit_kerja_id').select2({ placeholder: "Semua Unit Kerja", allowClear: true });
    $('#status_kerjasama').select2({ placeholder: "Semua Status Kerjasama", allowClear: true });

    // Open Report in New Tab on click "Lihat di Tab Baru"
    document.getElementById('btnCetakTab').addEventListener('click', function(e) {
      e.preventDefault();
      const form = document.getElementById('reportForm');
      
      const tgAwal = form.querySelector('[name="tanggal_awal"]').value;
      const tgAkhir = form.querySelector('[name="tanggal_akhir"]').value;
      if (!tgAwal || !tgAkhir) {
        alert('Tanggal Awal dan Tanggal Akhir wajib diisi!');
        return;
      }

      // Serialize form and open in new window
      const params = new URLSearchParams(new FormData(form)).toString();
      window.open(`{{ route('laporan.cetak') }}?${params}`, '_blank');
    });
  });
</script>
@endpush
