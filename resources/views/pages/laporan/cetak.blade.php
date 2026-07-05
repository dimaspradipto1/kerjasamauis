<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Kerjasama - UIS</title>
  
  <!-- Bootstrap & Icons -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  
  <style>
    body {
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background-color: #fff;
      color: #333;
      font-size: 10pt;
      line-height: 1.4;
      padding: 0;
      margin: 0;
    }
    
    /* Top Header Bar (No Print) */
    .top-header-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
      background-color: #fff;
      border-bottom: 1px solid #dee2e6;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    .btn-kembali {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      color: #333;
      font-weight: 500;
      font-size: 0.85rem;
      padding: 6px 16px;
      border-radius: 6px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.2s;
    }
    .btn-kembali:hover {
      background-color: #e9ecef;
      color: #000;
    }
    
    .btn-cetak {
      background-color: #0d6efd;
      border: 1px solid #0d6efd;
      color: #fff;
      font-weight: 500;
      font-size: 0.85rem;
      padding: 6px 16px;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .btn-cetak:hover {
      background-color: #0b5ed7;
    }
    
    .header-bar-title {
      font-size: 0.9rem;
      font-weight: 500;
      color: #6c757d;
      margin: 0;
    }

    /* Print Paper Container */
    .print-paper {
      padding: 40px;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    /* Kop Surat Styling */
    .kop-container {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      border-bottom: 3px double #000;
      padding-bottom: 12px;
    }
    .kop-logo {
      width: 75px;
      height: 75px;
      margin-right: 20px;
      object-fit: contain;
    }
    .kop-text {
      text-align: center;
    }
    .kop-text h4 {
      font-size: 12pt;
      font-weight: bold;
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .kop-text h3 {
      font-size: 14pt;
      font-weight: bold;
      margin: 3px 0;
      text-transform: uppercase;
      color: #157347; /* UIS Green */
    }
    .kop-text p {
      font-size: 8.5pt;
      margin: 0;
      color: #555;
    }
    
    /* Report Header */
    .report-title {
      text-align: center;
      margin-top: 15px;
      margin-bottom: 25px;
    }
    .report-title h4 {
      font-size: 15pt;
      font-weight: bold;
      margin: 0;
      color: #111;
    }
    
    /* Metadata Info Table */
    .metadata-table {
      width: 100%;
      max-width: 600px;
      margin-bottom: 25px;
      font-size: 9.5pt;
    }
    .metadata-table td {
      padding: 4px 0;
      vertical-align: top;
    }
    .metadata-table td.label-col {
      width: 220px;
      color: #333;
    }
    .metadata-table td.colon-col {
      width: 20px;
      color: #333;
    }
    .metadata-table td.value-col {
      font-weight: 500;
      color: #000;
    }

    /* Print Table Styling */
    .table-print {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }
    .table-print th, .table-print td {
      border: 1px solid #dee2e6 !important;
      padding: 8px 10px !important;
      vertical-align: middle;
      font-size: 9pt;
    }
    .table-print th {
      background-color: #f8f9fa !important;
      color: #333;
      font-weight: 600;
      text-align: left;
    }
    .table-print th.num-col, .table-print td.num-col {
      text-align: center;
      width: 50px;
    }
    .table-print th.status-col, .table-print td.status-col {
      text-align: center;
      width: 110px;
    }
    .table-print td.mitra-col {
      color: #0d6efd; /* Blue color matching screenshot */
    }

    /* Print settings */
    @media print {
      .no-print {
        display: none !important;
      }
      body {
        background-color: #fff;
        padding: 0;
      }
      .print-paper {
        padding: 0;
        max-width: 100%;
      }
      .table-print th {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      /* Ensure clean page breaks */
      tr {
        page-break-inside: avoid;
      }
    }
  </style>
</head>
<body>

  {{-- Top Header Bar (No Print) --}}
  <div class="top-header-bar no-print">
    <a href="javascript:window.close()" class="btn-kembali">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="header-bar-title">Laporan Kerjasama</h1>
    <button onclick="window.print()" class="btn-cetak">
      <i class="bi bi-printer"></i> Cetak
    </button>
  </div>

  {{-- Print Paper Container --}}
  <div class="print-paper">

    {{-- Kop Surat (Tampilkan jika useKop true) --}}
    @if($useKop)
      <div class="kop-container">
        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo UIS" class="kop-logo">
        <div class="kop-text">
          <h4>Yayasan Pendidikan Ibnu Sina Batam</h4>
          <h3>Universitas Ibnu Sina (UIS)</h3>
          <p>Jl. Teuku Umar, Pelita, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau 29443</p>
          <p>Telp: (0778) 451515, Email: info@uis.ac.id, Web: www.uis.ac.id</p>
        </div>
      </div>
    @endif

    {{-- Judul Laporan --}}
    <div class="report-title">
      <h4>Laporan Kerjasama</h4>
    </div>

    {{-- Metadata Info --}}
    @php
      function tgl_indo($date_str) {
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
    @endphp

    <table class="metadata-table">
      <tr>
        <td class="label-col">Tanggal Kerjasama</td>
        <td class="colon-col">:</td>
        <td class="value-col">{{ tgl_indo($request->tanggal_awal) }} <span class="fw-bold">s.d.</span> {{ tgl_indo($request->tanggal_akhir) }}</td>
      </tr>
      <tr>
        <td class="label-col">Jenis Dokumen Kerjasama</td>
        <td class="colon-col">:</td>
        <td class="value-col">{{ $jenisDokumenNama }}</td>
      </tr>
      <tr>
        <td class="label-col">Unit Kerja</td>
        <td class="colon-col">:</td>
        <td class="value-col">{{ $unitKerjaNama }}</td>
      </tr>
      <tr>
        <td class="label-col">Status Kerjasama</td>
        <td class="colon-col">:</td>
        <td class="value-col">{{ $request->status_kerjasama ?: 'Semua Status Kerjasama' }}</td>
      </tr>
    </table>

    {{-- Table Data --}}
    <table class="table table-print table-bordered align-middle">
      <thead>
        <tr>
          <th class="num-col">No</th>
          <th>Unit Kerja</th>
          <th>Mitra</th>
          <th>Judul Kerjasama</th>
          <th>Durasi Kerjasama</th>
          <th>Sumber Dana</th>
          <th style="text-align: right;">Anggaran (Rp)</th>
          <th class="status-col">Status Kerjasama</th>
        </tr>
      </thead>
      <tbody>
        @forelse($results as $index => $row)
          <tr>
            <td class="num-col">{{ $index + 1 }}</td>
            <td>{{ $row->unitKerja ? $row->unitKerja->nama_unit_kerja : '-' }}</td>
            <td class="mitra-col fw-bold">{{ $row->mitra ? $row->mitra->nama_mitra : '-' }}</td>
            <td>{{ $row->judul_kerjasama }}</td>
            <td>
              <span class="fw-bold">
                {{ $row->tanggal_waktu_berlaku ? tgl_indo($row->tanggal_waktu_berlaku->toDateString()) : '-' }} 
                s.d. 
                {{ $row->tanggal_akhir_berlaku ? tgl_indo($row->tanggal_akhir_berlaku->toDateString()) : '-' }}
              </span>
            </td>
            <td>{{ $row->sumberDana ? $row->sumberDana->nama_sumber_dana : '-' }}</td>
            <td class="text-end">{{ number_format($row->anggaran, 0, ',', '.') }}</td>
            <td class="status-col text-center">{{ $row->status_kerjasama }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">Tidak ada data kerjasama ditemukan untuk kriteria filter ini.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

  </div>

  {{-- Auto Print --}}
  <script>
    window.onload = function() {
      // Auto-open print dialog on load
      window.print();
    }
  </script>
</body>
</html>
