@extends('layouts.dashboard.template')

@section('title', 'Daftar Kerjasama - SIM Kerjasama UIS')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fw-bold text-dark">Daftar Kerjasama</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kerjasama</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('kerjasama.export') }}" class="btn btn-outline-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 8px; border: 1.5px solid #ced4da; background: #fff;" title="Export Excel">
                <i class="bi bi-printer text-dark fs-5"></i>
            </a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#importModal" class="btn btn-success d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; border-radius: 8px; background-color: #0b7a61; border: none;" title="Import Excel">
                <i class="bi bi-upload fs-5"></i>
            </button>
            <a href="{{ route('kerjasama.create') }}" class="btn btn-primary rounded-3 px-3 py-2 d-flex align-items-center gap-1 text-white" style="height: 36px;">
                <i class="bi bi-plus-lg"></i> Tambah Data
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            
            {{-- Custom Filters Section matching Mockup --}}
            <div class="row mb-3 g-2 align-items-center">
                <div class="col-md-3">
                    <div class="position-relative">
                        <input type="text" id="filter-search" class="form-control form-control-filter" placeholder="Cari data ...">
                        <i class="bi bi-search position-absolute text-muted" style="right: 12px; top: 11px;"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filter-mitra" class="form-select form-control-filter">
                        <option value="">-- Semua Mitra Kerjasama --</option>
                        @foreach($mitras as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_mitra }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filter-status" class="form-select form-control-filter">
                        <option value="">-- Semua Status Kerjasama --</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Draft">Draft</option>
                        <option value="Kedaluwarsa">Kedaluwarsa</option>
                        <option value="Perpanjangan">Perpanjangan</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filter-kadaluwarsa" class="form-select form-control-filter">
                        <option value="">-- Semua Filter Kadaluwarsa --</option>
                        <option value="1_week">Kedaluwarsa dalam 1 minggu</option>
                        <option value="1_month">Kedaluwarsa dalam 1 bulan</option>
                        <option value="3_months">Kedaluwarsa dalam 3 bulan</option>
                        <option value="6_months">Kedaluwarsa dalam 6 bulan</option>
                        <option value="1_year">Kedaluwarsa dalam 1 tahun</option>
                    </select>
                </div>
            </div>

            {{-- Table Grid --}}
            <div class="table-responsive mt-3">
                {{ $dataTable->table([
                    'class' => 'table table-hover align-middle border-light',
                    'style' => 'width:100%; border-collapse: collapse;',
                ]) }}
            </div>

        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-bottom py-3 px-4" style="background-color: #fff;">
                    <h6 class="modal-title fw-bold text-dark" id="importModalLabel">Import Data Data-kerjasama</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kerjasama.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="text-dark mb-2" style="font-size: 0.9rem;">Silahkan Unduh Terlebih dahulu format file import data</p>
                        <a href="{{ route('kerjasama.download-template') }}" class="text-primary fw-semibold d-inline-block mb-4" style="font-size: 0.9rem; text-decoration: underline;">Download</a>
                        
                        <div class="mb-3">
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required style="border-radius: 8px; padding: 10px;">
                        </div>
                    </div>
                    <div class="modal-footer border-top py-3 px-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; background-color: #6c757d; border: none; font-size: 0.875rem;">Tutup</button>
                        <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 8px; background-color: #0d6efd; border: none; font-size: 0.875rem;">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<style>
  /* Premium layout look */
  .form-control-filter {
    border: 1.5px solid #dee2e6;
    border-radius: 8px;
    height: 40px;
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
  }
  .form-control-filter:focus {
    border-color: #157347;
    box-shadow: 0 0 0 0.2rem rgba(21, 115, 71, 0.12);
  }
  .bg-success-light {
    background-color: #d1e7dd !important;
  }
  .bg-secondary-light {
    background-color: #e2e3e5 !important;
  }
</style>
@endsection

@push('scripts')
    @if (app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif

    <script>
        $(document).ready(function() {
            const tableId = 'kerjasama-table';

            // Initialize Select2 search on filters
            $('#filter-mitra').select2({ placeholder: "-- Semua Mitra Kerjasama --", allowClear: true });
            $('#filter-status').select2({ placeholder: "-- Semua Status Kerjasama --", allowClear: true });
            $('#filter-kadaluwarsa').select2({ placeholder: "-- Semua Filter Kadaluwarsa --", allowClear: true });
            
            // Link custom filters to Datatable request
            $('#' + tableId).on('preXhr.dt', function(e, settings, data) {
                data.mitra_id = $('#filter-mitra').val();
                data.status_kerjasama = $('#filter-status').val();
                data.filter_kadaluwarsa = $('#filter-kadaluwarsa').val();
            });

            // Trigger reload on change
            $('#filter-mitra, #filter-status, #filter-kadaluwarsa').on('change', function() {
                window.LaravelDataTables[tableId].draw();
            });

            // Search event
            $('#filter-search').on('keyup', function() {
                window.LaravelDataTables[tableId].search(this.value).draw();
            });

            // Select-all checkbox handling
            $(document).on('change', '#select-all-checkbox', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });
            $(document).on('change', '.row-checkbox', function() {
                if (!this.checked) {
                    $('#select-all-checkbox').prop('checked', false);
                } else if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                    $('#select-all-checkbox').prop('checked', true);
                }
            });
        });
    </script>
@endpush
