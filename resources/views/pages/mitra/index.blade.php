@extends('layouts.dashboard.template')

@section('title', 'Mitra - SIM Kerjasama UIS')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fw-bold text-dark">Mitra</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mitra</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('mitra.export') }}" class="btn btn-dark d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; border-radius: 8px; background-color: #212529; border: none;" title="Export Excel">
                <i class="bi bi-printer text-white fs-5"></i>
            </a>
            <a href="{{ route('mitra.create') }}" class="btn btn-primary rounded-3 px-3 py-2 d-flex align-items-center gap-1 text-white" style="height: 36px;">
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                {{ $dataTable->table([
                    'class' => 'table table-hover align-middle border-light',
                    'style' => 'width:100%; border-collapse: collapse;',
                ]) }}
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
            // Initialize Select2
            $('#users_id').select2({
                placeholder: "Pilih pengguna",
                allowClear: true
            });
        });
    </script>
@endpush
