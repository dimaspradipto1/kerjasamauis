@extends('layouts.dashboard.template')

@section('title', 'Daftar Kerjasama - SIM Kerjasama UIS')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center" style="margin-bottom: 28px !important;">
        <div>
            <h1 class="fw-bold text-dark">Daftar Kerjasama</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kerjasama</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" id="btn-bulk-delete" class="btn btn-danger rounded-3 px-3 py-2 align-items-center gap-1 text-white shadow-sm" style="height: 38px; display: none !important;">
                <i class="bi bi-trash"></i> Hapus Terpilih (<span id="selected-count">0</span>)
            </button>
            <a href="{{ route('kerjasama.export') }}" class="btn btn-outline-success rounded-3 px-3 py-2 d-flex align-items-center gap-2 shadow-sm fw-semibold" style="height: 38px; border: 1.5px solid #198754; font-size: 0.875rem;" title="Export Excel Data Kerjasama">
                <i class="bi bi-file-earmark-excel-fill text-success fs-6"></i>
                <span>Export Excel</span>
            </a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#importModal" class="btn btn-success rounded-3 px-3 py-2 d-flex align-items-center gap-2 text-white shadow-sm fw-semibold" style="height: 38px; background-color: #0b7a61; border-color: #0b7a61; font-size: 0.875rem;" title="Import Data Kerjasama dari Excel">
                <i class="bi bi-file-earmark-arrow-up-fill fs-6"></i>
                <span>Import Excel</span>
            </button>
            <a href="{{ route('kerjasama.create') }}" class="btn btn-primary rounded-3 px-3 py-2 d-flex align-items-center gap-2 text-white shadow-sm fw-semibold" style="height: 38px; font-size: 0.875rem;">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Data</span>
            </a>
        </div>
    </div>

    <form id="form-bulk-delete" action="{{ route('kerjasama.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        <div id="bulk-delete-ids-container"></div>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 25px !important;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 25px !important;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Rekapitulasi Cards: MoA, MoU, IA, Total --}}
    <div class="row g-3" style="margin-top: 10px !important; margin-bottom: 30px !important;">
        {{-- MoA Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #0d6efd !important; background: #ffffff;">
                <div class="card-body p-3" style="padding: 1.25rem !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-primary fw-bold mb-1 d-block" style="font-size: 0.82rem; letter-spacing: 0.3px;">
                                MEMORANDUM OF AGREEMENT
                            </span>
                            <div class="d-flex align-items-baseline gap-2">
                                <h3 class="fw-bold text-dark mb-0">{{ $moaCount ?? 0 }}</h3>
                                <span class="badge bg-primary bg-opacity-10 text-primary">MoA</span>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-file-earmark-text-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MoU Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #198754 !important; background: #ffffff;">
                <div class="card-body p-3" style="padding: 1.25rem !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-success fw-bold mb-1 d-block" style="font-size: 0.82rem; letter-spacing: 0.3px;">
                                MEMORANDUM OF UNDERSTANDING
                            </span>
                            <div class="d-flex align-items-baseline gap-2">
                                <h3 class="fw-bold text-dark mb-0">{{ $mouCount ?? 0 }}</h3>
                                <span class="badge bg-success bg-opacity-10 text-success">MoU</span>
                            </div>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-file-earmark-check-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- IA Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #6f42c1 !important; background: #ffffff;">
                <div class="card-body p-3" style="padding: 1.25rem !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-bold mb-1 d-block" style="color: #6f42c1; font-size: 0.82rem; letter-spacing: 0.3px;">
                                IMPLEMENTATION ARRANGEMENT
                            </span>
                            <div class="d-flex align-items-baseline gap-2">
                                <h3 class="fw-bold text-dark mb-0">{{ $iaCount ?? 0 }}</h3>
                                <span class="badge bg-opacity-10" style="background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">IA</span>
                            </div>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Kerjasama Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #fd7e14 !important; background: #ffffff;">
                <div class="card-body p-3" style="padding: 1.25rem !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-bold mb-1 d-block" style="color: #fd7e14; font-size: 0.82rem; letter-spacing: 0.3px;">
                                TOTAL KERJASAMA
                            </span>
                            <div class="d-flex align-items-baseline gap-2">
                                <h3 class="fw-bold text-dark mb-0">{{ $totalKerjasamaCount ?? 0 }}</h3>
                                <span class="badge bg-warning bg-opacity-10 text-warning">Total</span>
                            </div>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-folder-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3" style="margin-top: 25px !important; margin-bottom: 30px !important;">
        <div class="card-body p-4" style="padding-top: 25px !important;">
            
            {{-- Custom Filters Section matching Mockup --}}
            <div class="row g-2 align-items-center" style="margin-top: 5px !important; margin-bottom: 25px !important;">
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
            <div class="table-responsive mt-3" style="width: 100%;">
                {{ $dataTable->table([
                    'class' => 'table table-hover align-middle border-light w-100',
                    'style' => 'width:100% !important; border-collapse: collapse;',
                ]) }}
            </div>

        </div>
    </div>

    <!-- Modal Import Data Kerjasama -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-bottom py-3 px-4 bg-light">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                             style="width: 36px; height: 36px; background-color: #0b7a61;">
                            <i class="bi bi-file-earmark-arrow-up-fill fs-5"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="importModalLabel">Import Data Kerjasama</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-import-kerjasama" action="{{ route('kerjasama.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        {{-- Petunjuk & Download Template Card --}}
                        <div class="card border-0 shadow-sm mb-4 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0 !important;">
                            <div class="card-body p-3">
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-excel text-success fs-5"></i>
                                            Format Berkas Import Kerjasama
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            Unduh format template resmi untuk memastikan struktur data dan kolom sesuai standar sistem.
                                        </p>
                                    </div>
                                    <a href="{{ route('kerjasama.download-template') }}" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 text-nowrap" style="border-radius: 8px;">
                                        <i class="bi bi-download"></i> Unduh Template Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Panduan Pengisian Data --}}
                        <div class="alert alert-info border-0 py-3 px-3 mb-4 rounded-3" style="background-color: #f0fdf4; color: #166534; font-size: 0.85rem; line-height: 1.5; border-left: 4px solid #16a34a !important;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill fs-5 flex-shrink-0 text-success mt-n1"></i>
                                <div>
                                    <strong class="d-block mb-1">Ketentuan Pengisian Data Kerjasama:</strong>
                                    <ul class="mb-1 ps-3">
                                        <li><strong>Unit Kerja:</strong> Dapat diisi lebih dari 1 unit kerja dengan memisahkannya menggunakan tanda koma (contoh: <code>S1 - Teknik Industri, S1 - Teknik Informatika</code>).</li>
                                        <li><strong>Jenis Dokumen:</strong> MoU, MoA, atau IA (Implementation of Arrangement).</li>
                                        <li><strong>Tanggal:</strong> Format tanggal adalah <code>YYYY-MM-DD</code> (contoh: <code>2025-07-24</code>).</li>
                                        <li><strong>Upload Dokumen Fisik:</strong> <span class="badge bg-success bg-opacity-25 text-success fw-bold">Boleh Dikosongkan</span> Berkas lampiran / scan PDF dokumen kerjasama bersifat opsional saat import dan dapat diunggah kemudian melalui menu detail / edit kerjasama.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Input Berkas --}}
                        <div class="mb-3">
                            <label for="import_file" class="form-label fw-semibold text-dark small">Pilih Berkas Excel / CSV <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="import_file" class="form-control" accept=".xlsx,.xls,.csv" required style="border-radius: 8px; padding: 10px;">
                            <div class="form-text small text-muted">Format yang didukung: <strong>.xlsx, .xls, .csv</strong> (Maksimal 10 MB).</div>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-3 px-4 d-flex justify-content-end gap-2 bg-light">
                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.875rem;">Batal</button>
                        <button type="submit" id="btn-submit-import" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border-radius: 8px; background-color: #0b7a61; border-color: #0b7a61; font-size: 0.875rem;">
                            <i class="bi bi-upload"></i> Mulai Import Data
                        </button>
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
  /* Datatables Pagination & Spacing alignment */
  .dataTables_wrapper {
    padding-top: 0.5rem;
    padding-bottom: 1.25rem;
  }
  .dataTables_paginate {
    float: right !important;
    margin-top: 0.75rem !important;
  }
  .dataTables_paginate .pagination {
    margin-bottom: 0 !important;
    justify-content: flex-end !important;
    gap: 4px;
  }
  .dataTables_info {
    font-size: 0.875rem !important;
    color: #6c757d !important;
    padding-top: 0.75rem !important;
  }
  /* Fullwidth Table Layout & Responsiveness */
  .table-responsive {
    width: 100% !important;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
  }

  #kerjasama-table {
    width: 100% !important;
    border-collapse: collapse !important;
  }

  #kerjasama-table th {
    font-size: 0.785rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    color: #495057;
    background-color: #f8f9fa !important;
    padding: 0.7rem 0.5rem !important;
    vertical-align: middle !important;
    white-space: nowrap !important;
  }

  #kerjasama-table td {
    font-size: 0.825rem !important;
    padding: 0.7rem 0.5rem !important;
    vertical-align: middle !important;
    color: #212529;
  }

  #kerjasama-table th:nth-child(2),
  #kerjasama-table td:nth-child(2) {
    min-width: 280px !important;
  }

  #kerjasama-table a {
    font-size: 0.85rem !important;
  }

  ol.unit-kerja-list {
    font-size: 0.825rem !important;
    line-height: 1.45 !important;
    padding-left: 1.25rem !important;
    margin: 0 !important;
    min-width: 260px !important;
  }

  ol.unit-kerja-list li {
    margin-bottom: 3px !important;
    white-space: nowrap !important;
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

            // Auto readjust datatable on sidebar toggle
            $(document).on('click', '.toggle-sidebar-btn', function() {
                setTimeout(function() {
                    if ($.fn.DataTable.isDataTable('#' + tableId)) {
                        $('#' + tableId).DataTable().columns.adjust();
                    }
                }, 300);
            });

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

            // Function to update bulk delete button count & visibility
            function updateBulkDeleteState() {
                const checkedCount = $('.row-checkbox:checked').length;
                $('#selected-count').text(checkedCount);
                if (checkedCount > 0) {
                    $('#btn-bulk-delete').removeClass('d-none').css('display', 'inline-flex');
                } else {
                    $('#btn-bulk-delete').addClass('d-none').css('display', 'none');
                }
            }

            // Select-all checkbox handling
            $(document).on('change', '#select-all-checkbox', function() {
                $('.row-checkbox').prop('checked', this.checked);
                updateBulkDeleteState();
            });
            $(document).on('change', '.row-checkbox', function() {
                if (!this.checked) {
                    $('#select-all-checkbox').prop('checked', false);
                } else if ($('.row-checkbox:checked').length === $('.row-checkbox').length && $('.row-checkbox').length > 0) {
                    $('#select-all-checkbox').prop('checked', true);
                }
                updateBulkDeleteState();
            });

            // Reset select-all and bulk delete button on Datatable redraw
            $('#' + tableId).on('draw.dt', function() {
                $('#select-all-checkbox').prop('checked', false);
                updateBulkDeleteState();
            });

            // SweetAlert2 for Single Delete
            $(document).on('submit', '.form-delete-single', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data kerjasama ini akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmColor: '#dc3545',
                    cancelColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // SweetAlert2 for Bulk Delete
            $('#btn-bulk-delete').on('click', function() {
                const selectedIds = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Data Terpilih',
                        text: 'Silakan pilih minimal satu data kerjasama yang ingin dihapus.',
                        confirmColor: '#0d6efd'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan menghapus ${selectedIds.length} data kerjasama yang dipilih!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmColor: '#dc3545',
                    cancelColor: '#6c757d',
                    confirmButtonText: `Ya, Hapus (${selectedIds.length}) Data!`,
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const container = $('#bulk-delete-ids-container');
                        container.empty();
                        selectedIds.forEach(id => {
                            container.append(`<input type="hidden" name="ids[]" value="${id}">`);
                        });
                        $('#form-bulk-delete').submit();
                    }
                });
            });
        });
    </script>
@endpush
