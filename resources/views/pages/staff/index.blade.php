@extends('layouts.dashboard.template')

@section('title', 'Data Staff - SIM Kerjasama UIS')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fw-bold text-dark">Data Staff</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Data Referensi</li>
                    <li class="breadcrumb-item active">Data Staff</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('staff.export') }}" class="btn btn-outline-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 8px; border: 1.5px solid #ced4da; background: #fff;" title="Export Excel">
                <i class="bi bi-printer text-dark fs-5"></i>
            </a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#importModal" class="btn btn-success d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; border-radius: 8px; background-color: #0b7a61; border: none;" title="Import Excel">
                <i class="bi bi-upload fs-5"></i>
            </button>
            <a href="{{ route('staff.create') }}" class="btn btn-primary rounded-3 px-3 py-2 d-flex align-items-center gap-1 text-white" style="height: 36px;">
                <i class="bi bi-plus-lg"></i> Tambah Staff
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            
            {{-- Custom Filters --}}
            <div class="row mb-3 g-2 align-items-center">
                <div class="col-md-4">
                    <div class="position-relative">
                        <input type="text" id="filter-search" class="form-control form-control-filter" placeholder="Cari staff, NUP, email...">
                        <i class="bi bi-search position-absolute text-muted" style="right: 12px; top: 11px;"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="filter-unit-kerja" class="form-select form-control-filter">
                        <option value="">-- Semua Unit Kerja --</option>
                        @foreach($unitKerjas as $uk)
                            <option value="{{ $uk->id }}">{{ $uk->nama_unit_kerja }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="filter-status" class="form-select form-control-filter">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                {{ $dataTable->table([
                    'class' => 'table table-hover align-middle border-light',
                    'style' => 'width:100%; border-collapse: collapse;',
                ]) }}
            </div>
        </div>
    </div>

    {{-- Modal Import Excel --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="importModalLabel" style="font-size: 1rem;"><i class="bi bi-file-earmark-excel me-2"></i>Import Data Staff</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('staff.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="file" class="form-label fw-semibold">Pilih File Excel (.xlsx, .xls, .csv)</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <div class="bg-light p-3 rounded-3 border">
                            <span class="small text-muted d-block mb-1"><i class="bi bi-info-circle me-1 text-primary"></i> Belum punya format Excel?</span>
                            <a href="{{ route('staff.download-template') }}" class="btn btn-outline-success btn-sm font-weight-bold">
                                <i class="bi bi-download me-1"></i> Download Template Import
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm px-4 text-white"><i class="bi bi-upload me-1"></i>Import Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<style>
  .form-control-filter {
    border: 1.5px solid #ced4da;
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
            const tableId = 'staff-table';

            // Select2 on filters
            $('#filter-unit-kerja').select2({ placeholder: "-- Semua Unit Kerja --", allowClear: true });
            $('#filter-status').select2({ placeholder: "-- Semua Status --", allowClear: true });

            // Pass custom filters to DataTables AJAX request
            $('#' + tableId).on('preXhr.dt', function(e, settings, data) {
                data.unit_kerja_id = $('#filter-unit-kerja').val();
                data.status = $('#filter-status').val();
            });

            // Reload DataTables on filter change
            $('#filter-unit-kerja, #filter-status').on('change', function() {
                window.LaravelDataTables[tableId].draw();
            });

            // Live search
            $('#filter-search').on('keyup', function() {
                window.LaravelDataTables[tableId].search(this.value).draw();
            });

            // Checkbox select all logic
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
