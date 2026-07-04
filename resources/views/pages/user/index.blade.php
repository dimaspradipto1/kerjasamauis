@extends('layouts.dashboard.template')

@section('title', 'Manajemen Pengguna - SIM Kerjasama UIS')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1>Manajemen Pengguna</h1>
    <nav>
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pengguna</li>
      </ol>
    </nav>
  </div>
  <div>
    <a href="{{ route('user.create') }}" class="btn btn-uis shadow-sm d-flex align-items-center gap-2">
      <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
    </a>
  </div>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow-sm border-0">
        <div class="card-body pt-4">
          <div class="table-responsive">
            {{ $dataTable->table(['class' => 'table table-hover table-striped w-100']) }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
  <style>
    .dt-buttons .btn {
      font-size: 13px !important;
      padding: 5px 12px !important;
      border-radius: 6px !important;
      margin-right: 5px;
    }
    .dt-buttons .btn-secondary {
      background-color: #6c757d !important;
      border-color: #6c757d !important;
      color: white !important;
    }
    table.dataTable.no-footer {
      border-bottom: 1px solid #dee2e6 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      padding: 0 !important;
      margin-left: 0 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      border: none !important;
      background: none !important;
    }
  </style>
@endpush

@push('scripts')
  <!-- jQuery & DataTables CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
  
  <!-- DataTables Buttons CDN -->
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

  <!-- Output the JS from Yajra HTML builder -->
  {{ $dataTable->scripts() }}
@endpush
