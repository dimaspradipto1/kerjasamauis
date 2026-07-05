@extends('layouts.dashboard.template')

@section('title', 'Daftar Kegiatan - SIM Kerjasama UIS')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fw-bold text-dark">Daftar Kegiatan</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kegiatan</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" data-bs-toggle="modal" data-bs-target="#importModal" class="btn btn-success d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; border-radius: 8px; background-color: #0b7a61; border: none;" title="Import Excel">
                <i class="bi bi-upload fs-5"></i>
            </button>
            <a href="{{ route('kegiatan.create') }}" class="btn btn-primary rounded-3 px-3 py-2 d-flex align-items-center gap-1 text-white" style="height: 36px;">
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
                    <select id="filter-kerjasama" class="form-select form-control-filter">
                        <option value="">-- Semua Induk Kerjasama --</option>
                        @foreach($kerjasamas as $k)
                            <option value="{{ $k->id }}">{{ $k->judul_kerjasama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filter-unit-kerja" class="form-select form-control-filter">
                        <option value="">-- Semua Unit Kerja --</option>
                        @foreach($unitKerjas as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_unit_kerja }}</option>
                        @endforeach
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
                    <h6 class="modal-title fw-bold text-dark" id="importModalLabel">Import Data Kegiatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kegiatan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="text-dark mb-2" style="font-size: 0.9rem;">Silahkan Unduh Terlebih dahulu format file import data</p>
                        <a href="{{ route('kegiatan.download-template') }}" class="text-primary fw-semibold d-inline-block mb-4" style="font-size: 0.9rem; text-decoration: underline;">Download</a>
                        
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
@endsection

@push('scripts')
    @if (app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif

    <script>
        $(document).ready(function() {
            const tableId = 'kegiatan-table';
            
            // Initialize Select2 search on filters
            $('#filter-mitra').select2({ placeholder: "-- Semua Mitra Kerjasama --", allowClear: true });
            $('#filter-kerjasama').select2({ placeholder: "-- Semua Induk Kerjasama --", allowClear: true });
            $('#filter-unit-kerja').select2({ placeholder: "-- Semua Unit Kerja --", allowClear: true });

            // Link custom filters to Datatable request
            $('#' + tableId).on('preXhr.dt', function(e, settings, data) {
                data.mitra_id = $('#filter-mitra').val();
                data.kerjasama_id = $('#filter-kerjasama').val();
                data.unit_kerja_id = $('#filter-unit-kerja').val();
            });

            // Trigger reload on change
            $('#filter-mitra, #filter-kerjasama, #filter-unit-kerja').on('change', function() {
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
