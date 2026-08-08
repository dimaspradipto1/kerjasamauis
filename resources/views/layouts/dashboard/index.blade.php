@extends('layouts.dashboard.template')

@section('content')

    {{-- Header Dashboard with Filter Tahun --}}
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3" style="margin-bottom: 28px !important;">
        <div>
            <h1 class="fw-bold text-dark">Dashboard Eksekutif Kerjasama</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
                <label for="filter-tahun-header" class="small fw-bold text-muted mb-0"><i class="bi bi-calendar-event me-1"></i> Filter Tahun:</label>
                <select name="tahun" id="filter-tahun-header" class="form-select form-select-sm fw-bold border-primary text-primary shadow-sm" style="width: 170px; border-radius: 8px; cursor: pointer;" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($tahunList as $th)
                        <option value="{{ $th }}" {{ $tahunSelected == $th ? 'selected' : '' }}>Tahun {{ $th }}</option>
                    @endforeach
                </select>
                @if($tahunSelected)
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-2" title="Reset Filter">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <section class="section dashboard">

        {{-- ========================================================================= --}}
        {{-- SECTION 1: EXECUTIVE KPI SUMMARY CARDS (STATUS & SKALA) --}}
        {{-- ========================================================================= --}}
        <div class="row g-3 mb-4" style="margin-top: 5px !important; margin-bottom: 25px !important;">
            {{-- MoU Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #198754 !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-success fw-bold d-flex align-items-center gap-1 mb-1" style="font-size: 0.95rem;">
                                    <i class="bi bi-file-earmark-check-fill"></i> MoU
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $mouTotalCount }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-file-earmark-check fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Memorandum of Understanding (Nota Kesepahaman)</p>
                    </div>
                </div>
            </div>

            {{-- MoA Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #0d6efd !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-primary fw-bold d-flex align-items-center gap-1 mb-1" style="font-size: 0.95rem;">
                                    <i class="bi bi-file-earmark-text-fill"></i> MoA
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $moaTotalCount }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-file-earmark-text fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Memorandum of Agreement (Perjanjian Kerjasama)</p>
                    </div>
                </div>
            </div>

            {{-- IA Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #6f42c1 !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-bold d-flex align-items-center gap-1 mb-1" style="color: #6f42c1; font-size: 0.95rem;">
                                    <i class="bi bi-journal-text"></i> IA
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $iaTotalCount }}</h3>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                                <i class="bi bi-journal-check fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Implementation Arrangement (Pelaksanaan Kerjasama)</p>
                    </div>
                </div>
            </div>

            {{-- Total Kerjasama Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #fd7e14 !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-bold d-flex align-items-center gap-1 mb-1" style="color: #fd7e14; font-size: 0.95rem;">
                                    <i class="bi bi-folder-fill"></i> Total Kerjasama
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $totalKerjasamaCount }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-folder-check fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Total keseluruhan dokumen kerjasama.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Kerjasama Cards --}}
        <div class="row g-3 mb-4" style="margin-bottom: 25px !important;">
            {{-- Aktif Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #198754 !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-success fw-bold d-flex align-items-center gap-1 mb-1" style="font-size: 0.95rem;">
                                    <i class="bi bi-check-square-fill"></i> Aktif
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $aktifCount }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Kerjasama sedang berjalan dan masih berlaku.</p>
                    </div>
                </div>
            </div>

            {{-- Perpanjangan Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #0dcaf0 !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-info fw-bold d-flex align-items-center gap-1 mb-1" style="font-size: 0.95rem;">
                                    <i class="bi bi-arrow-repeat"></i> Perpanjangan
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $perpanjanganCount }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-arrow-clockwise fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Kerjasama sedang dalam proses perpanjangan.</p>
                    </div>
                </div>
            </div>

            {{-- Kedaluwarsa Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #ffc107 !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-warning fw-bold d-flex align-items-center gap-1 mb-1" style="font-size: 0.95rem;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Kedaluwarsa
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $kedaluwarsaCount }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Kerjasama melewati masa berlaku & belum diperpanjang.</p>
                    </div>
                </div>
            </div>

            {{-- Tidak Aktif Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #dc3545 !important;">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-danger fw-bold d-flex align-items-center gap-1 mb-1" style="font-size: 0.95rem;">
                                    <i class="bi bi-x-circle-fill"></i> Tidak Aktif
                                </span>
                                <h3 class="fw-bold text-dark mb-0">{{ $tidakAktifCount }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-slash-circle fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Kerjasama sudah tidak berlaku atau dihentikan.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row Skala Kerjasama: Nasional & Internasional Cards --}}
        <div class="row g-3 mb-4" style="margin-bottom: 28px !important;">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #0d6efd !important; background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-primary fw-bold d-flex align-items-center gap-2 mb-1" style="font-size: 0.95rem;">
                                    <i class="bi bi-flag-fill"></i> Kerjasama Nasional
                                </span>
                                <h2 class="fw-bold text-dark mb-0">{{ $nasionalCount }}</h2>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-geo-alt-fill fs-3"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Jumlah total dokumen kerjasama berskala Nasional.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #6f42c1 !important; background: linear-gradient(135deg, #ffffff 0%, #f8f0ff 100%);">
                    <div class="card-body" style="padding: 1.25rem !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-bold d-flex align-items-center gap-2 mb-1" style="color: #6f42c1; font-size: 0.95rem;">
                                    <i class="bi bi-globe2"></i> Kerjasama Internasional
                                </span>
                                <h2 class="fw-bold text-dark mb-0">{{ $internasionalCount }}</h2>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                                <i class="bi bi-globe fs-3"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem; line-height: 1.3;">Jumlah total dokumen kerjasama berskala Internasional.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- SECTION 2: UTAMA - GRAFIK REKAPITULASI DOKUMEN PER UNIT KERJA & TOP 5 PERINGKAT --}}
        {{-- ========================================================================= --}}
        <div class="row g-3 mb-4" style="margin-bottom: 28px !important;">
            {{-- Left (Col-7): Grafik Rekapitulasi Dokumen (MoU, MoA, IA) per Unit Kerja --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i> Grafik Rekapitulasi Dokumen (MoU, MoA, & IA) Per Unit Kerja / Fakultas</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary">Grafik Perbandingan Utama</span>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartDokumenUnitKerja" style="min-height: 380px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Right (Col-5): Top 5 Unit Kerja Peringkat --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-trophy-fill me-2 text-warning"></i> Top 5 Unit Kerja</h6>
                        <div class="btn-group btn-group-sm flex-wrap" role="group">
                            <button type="button" class="btn btn-outline-primary active fw-semibold" id="btn-rank-total" onclick="switchRankCategory('total')">Total</button>
                            <button type="button" class="btn btn-outline-primary fw-semibold" id="btn-rank-mou" onclick="switchRankCategory('mou')">MoU</button>
                            <button type="button" class="btn btn-outline-primary fw-semibold" id="btn-rank-moa" onclick="switchRankCategory('moa')">MoA</button>
                            <button type="button" class="btn btn-outline-primary fw-semibold" id="btn-rank-ia" onclick="switchRankCategory('ia')">IA</button>
                            <button type="button" class="btn btn-outline-primary fw-semibold" id="btn-chart-tahunan" onclick="switchRankCategory('tahunan')"><i class="bi bi-calendar-range me-1"></i> Tahunan</button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        {{-- Chart Unit Kerja Total --}}
                        <div id="chartUnitKerja" style="min-height: 220px; width: 100%;"></div>
                        
                        {{-- Chart Unit Kerja Tahunan (Hidden by default) --}}
                        <div id="chartUnitKerjaTahunan" style="min-height: 280px; width: 100%; display: none;"></div>
                        
                        {{-- Visual Ranking Rincian Container --}}
                        <div class="mt-3 pt-3 border-top" id="rankingListContainer">
                            {{-- 1. TOTAL RANKING LIST --}}
                            <div id="rankListTotal">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-muted small mb-0"><i class="bi bi-list-ol me-1 text-primary"></i> PERINGKAT TOTAL KERJASAMA</h6>
                                    <span class="badge bg-light text-muted border">Semua Jenis</span>
                                </div>
                                @php $maxKerjasama = $top5UnitKerjaList->max('total_kerjasama') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @forelse($top5UnitKerjaList as $idx => $topUk)
                                        @php 
                                            $pct = round(($topUk->total_kerjasama / $maxKerjasama) * 100); 
                                            $barColor = $idx == 0 ? '#fd7e14' : ($idx == 1 ? '#6c757d' : ($idx == 2 ? '#cd7f32' : '#0d6efd'));
                                        @endphp
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5 flex-wrap gap-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($idx == 0)
                                                        <span class="badge bg-warning text-dark fw-bold rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">1</span>
                                                    @elseif($idx == 1)
                                                        <span class="badge bg-secondary text-white fw-bold rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">2</span>
                                                    @elseif($idx == 2)
                                                        <span class="badge text-white fw-bold rounded-circle" style="background-color: #cd7f32; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">3</span>
                                                    @else
                                                        <span class="badge bg-white text-dark border fw-bold rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ $idx + 1 }}</span>
                                                    @endif
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $topUk->nama_unit_kerja }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle px-2 py-0.5 rounded-2 small" style="font-size: 0.72rem;">MoU: {{ $topUk->mou_count }}</span>
                                                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning-subtle px-2 py-0.5 rounded-2 small" style="font-size: 0.72rem;">MoA: {{ $topUk->moa_count }}</span>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-0.5 rounded-2 small" style="font-size: 0.72rem;">IA: {{ $topUk->ia_count }}</span>
                                                    <span class="badge bg-primary px-2.5 py-1 rounded-pill fw-bold ms-1" style="font-size: 0.8rem;">{{ $topUk->total_kerjasama }} Dokumen</span>
                                                </div>
                                            </div>
                                            <div class="progress mt-1.5" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                                <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $pct }}%; background-color: {{ $barColor }};" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0">Belum ada data unit kerja.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 2. MOU RANKING LIST --}}
                            <div id="rankListMou" style="display: none;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-info small mb-0"><i class="bi bi-file-earmark-text me-1"></i> TOP 5 TERBANYAK MoU</h6>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">MoU</span>
                                </div>
                                @php $maxMou = $top5MoUList->max('mou_count') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @forelse($top5MoUList as $idx => $mouUk)
                                        @php $pct = round(($mouUk->mou_count / $maxMou) * 100); @endphp
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-info text-white fw-bold rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ $idx + 1 }}</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $mouUk->nama_unit_kerja }}</span>
                                                </div>
                                                <span class="badge bg-info px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.8rem;">{{ $mouUk->mou_count }} MoU</span>
                                            </div>
                                            <div class="progress mt-1.5" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                                <div class="progress-bar bg-info rounded-pill" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0">Belum ada data MoU.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 3. MOA RANKING LIST --}}
                            <div id="rankListMoa" style="display: none;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-warning small mb-0"><i class="bi bi-file-earmark-check me-1"></i> TOP 5 TERBANYAK MoA</h6>
                                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning">MoA</span>
                                </div>
                                @php $maxMoa = $top5MoAList->max('moa_count') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @forelse($top5MoAList as $idx => $moaUk)
                                        @php $pct = round(($moaUk->moa_count / $maxMoa) * 100); @endphp
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-warning text-dark fw-bold rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ $idx + 1 }}</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $moaUk->nama_unit_kerja }}</span>
                                                </div>
                                                <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.8rem;">{{ $moaUk->moa_count }} MoA</span>
                                            </div>
                                            <div class="progress mt-1.5" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                                <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0">Belum ada data MoA.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 4. IA RANKING LIST --}}
                            <div id="rankListIa" style="display: none;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-success small mb-0"><i class="bi bi-file-earmark-code me-1"></i> TOP 5 TERBANYAK IA</h6>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">IA</span>
                                </div>
                                @php $maxIa = $top5IAList->max('ia_count') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @forelse($top5IAList as $idx => $iaUk)
                                        @php $pct = round(($iaUk->ia_count / $maxIa) * 100); @endphp
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-success text-white fw-bold rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ $idx + 1 }}</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $iaUk->nama_unit_kerja }}</span>
                                                </div>
                                                <span class="badge bg-success px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.8rem;">{{ $iaUk->ia_count }} IA</span>
                                            </div>
                                            <div class="progress mt-1.5" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                                <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0">Belum ada data IA.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- SECTION 3: TREND PERTUMBUHAN KERJASAMA PER TAHUN & PROPORSI DOKUMEN --}}
        {{-- ========================================================================= --}}
        <div class="row g-3 mb-4" style="margin-bottom: 28px !important;">
            {{-- Left (Col-8): Grafik Trend Kerjasama Per Tahun --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-graph-up-arrow me-2 text-success"></i> Trend & Pertumbuhan Kerjasama Per Tahun</h6>
                        <span class="badge bg-success bg-opacity-10 text-success">Statistik Pertumbuhan</span>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartKerjasamaPerTahun" style="min-height: 280px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Right (Col-4): Proporsi Jenis Dokumen (MoU, MoA, IA) --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill me-1 text-info"></i> Proporsi Jenis Dokumen</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartJenisDokumen" style="min-height: 280px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- SECTION 4: DEMOGRAFI MITRA & BENTUK KEGIATAN --}}
        {{-- ========================================================================= --}}
        <div class="row g-3 mb-4" style="margin-bottom: 28px !important;">
            {{-- Bentuk Kegiatan Terbanyak --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-grid-3x3-gap me-1 text-primary"></i> Bentuk Kegiatan Terbanyak</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartBentukKegiatan" style="min-height: 280px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Top 5 Provinsi Mitra --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-map me-1 text-danger"></i> Top 5 Sebaran Provinsi Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartProvinsiMitra" style="min-height: 280px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row Karakteristik Mitra & Implementasi --}}
        <div class="row g-3 mb-4" style="margin-bottom: 28px !important;">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;"><i class="bi bi-globe me-1 text-primary"></i> Ruang Lingkup Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartRuangLingkup" style="min-height: 250px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;"><i class="bi bi-tag me-1" style="color: #6f42c1;"></i> Jenis Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartJenisMitra" style="min-height: 250px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;"><i class="bi bi-bookmark-star me-1 text-warning"></i> Kriteria Klasifikasi Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartKriteriaMitra" style="min-height: 250px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row Implementasi Kegiatan & Implementasi Kerjasama --}}
        <div class="row g-3 mb-4" style="margin-bottom: 28px !important;">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-journal-check me-1 text-success"></i> Implementasi & Hasil Pelaksanaan Kegiatan</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartImplementasiKegiatan" style="min-height: 260px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-layout-text-sidebar me-1 text-primary"></i> Implementasi & Hasil Pelaksanaan Kerjasama</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartImplementasiKerjasama" style="min-height: 260px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- SECTION 5: TABEL REKAPITULASI DETAIL KERJASAMA PER UNIT KERJA / PRODI --}}
        {{-- ========================================================================= --}}
        {{-- ========================================================================= --}}
        {{-- SECTION 5: TABEL REKAPITULASI DETAIL KERJASAMA PER FAKULTAS & PRODI --}}
        {{-- ========================================================================= --}}
        <div class="row g-3 mt-3" style="margin-bottom: 35px !important;">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-diagram-3-fill me-2 text-primary"></i> Rekapitulasi Dokumen (MoU, MoA, & IA) Per Universitas, Fakultas & Prodi</h6>
                            <small class="text-muted">Klik pada baris Fakultas untuk melihat rincian jumlah kerjasama per-Prodi</small>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" style="font-size: 0.8rem;"><i class="bi bi-hand-index-thumb me-1"></i> Interaktif (Klik Fakultas)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3" style="width: 50px;">No</th>
                                        <th class="py-3">Fakultas / Program Studi</th>
                                        <th class="text-center py-3 text-info"><i class="bi bi-file-earmark-text me-1"></i> MoU</th>
                                        <th class="text-center py-3 text-warning"><i class="bi bi-file-earmark-check me-1"></i> MoA</th>
                                        <th class="text-center py-3 text-success"><i class="bi bi-file-earmark-code me-1"></i> IA</th>
                                        <th class="text-center py-3 text-primary"><i class="bi bi-flag me-1"></i> Nasional</th>
                                        <th class="text-center py-3" style="color: #6f42c1;"><i class="bi bi-globe me-1"></i> Internasional</th>
                                        <th class="text-center py-3 text-success"><i class="bi bi-check-circle me-1"></i> Aktif</th>
                                        <th class="text-center py-3 pe-3 fw-bold">Total Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $noIndex = 1; @endphp
                                    @foreach($hierarchicalRekap as $groupKey => $group)
                                        @if($group['type'] === 'universitas')
                                            {{-- Universitas Row --}}
                                            <tr class="bg-light fw-bold">
                                                <td class="ps-3 text-muted">{{ $noIndex++ }}</td>
                                                <td class="text-dark">
                                                    <span class="badge bg-primary me-2">Universitas</span>
                                                    {{ $group['nama'] }}
                                                </td>
                                                <td class="text-center"><span class="badge bg-info bg-opacity-20 text-info fw-bold px-2.5 py-1 rounded-2">{{ $group['mou_count'] }}</span></td>
                                                <td class="text-center"><span class="badge bg-warning bg-opacity-20 text-dark fw-bold px-2.5 py-1 rounded-2">{{ $group['moa_count'] }}</span></td>
                                                <td class="text-center"><span class="badge bg-success bg-opacity-20 text-success fw-bold px-2.5 py-1 rounded-2">{{ $group['ia_count'] }}</span></td>
                                                <td class="text-center text-primary">{{ $group['nasional_count'] }}</td>
                                                <td class="text-center" style="color: #6f42c1;">{{ $group['internasional_count'] }}</td>
                                                <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">{{ $group['aktif_count'] }}</span></td>
                                                <td class="text-center pe-3 text-primary fs-6">{{ $group['total_kerjasama'] }}</td>
                                            </tr>
                                        @else
                                            {{-- Faculty Header Row (Clickable Accordion) --}}
                                            <tr class="fw-bold align-middle cursor-pointer border-top" style="background-color: #f8f9fa; cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $groupKey }}" aria-expanded="false" aria-controls="collapse-{{ $groupKey }}">
                                                <td class="ps-3 text-muted">{{ $noIndex++ }}</td>
                                                <td class="text-dark py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="bi bi-chevron-right toggle-icon text-primary me-1" id="icon-{{ $groupKey }}"></i>
                                                        <span class="badge bg-success me-1">Fakultas</span>
                                                        <span class="fw-bold text-dark">{{ $group['nama'] }}</span>
                                                        <span class="badge bg-white text-muted border ms-1" style="font-size: 0.75rem;">{{ $group['prodis']->count() }} Prodi <i class="bi bi-caret-down-fill ms-0.5"></i></span>
                                                    </div>
                                                </td>
                                                <td class="text-center"><span class="badge bg-info bg-opacity-20 text-info fw-bold px-2.5 py-1.5 rounded-2" style="font-size: 0.88rem;">{{ $group['mou_count'] }}</span></td>
                                                <td class="text-center"><span class="badge bg-warning bg-opacity-20 text-dark fw-bold px-2.5 py-1.5 rounded-2" style="font-size: 0.88rem;">{{ $group['moa_count'] }}</span></td>
                                                <td class="text-center"><span class="badge bg-success bg-opacity-20 text-success fw-bold px-2.5 py-1.5 rounded-2" style="font-size: 0.88rem;">{{ $group['ia_count'] }}</span></td>
                                                <td class="text-center text-primary fw-bold">{{ $group['nasional_count'] }}</td>
                                                <td class="text-center fw-bold" style="color: #6f42c1;">{{ $group['internasional_count'] }}</td>
                                                <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">{{ $group['aktif_count'] }}</span></td>
                                                <td class="text-center pe-3 text-primary fw-bold" style="font-size: 0.95rem;">{{ $group['total_kerjasama'] }}</td>
                                            </tr>

                                            {{-- Child Prodi Rows (Collapsible) --}}
                                            @foreach($group['prodis'] as $pIdx => $prodi)
                                                <tr class="collapse collapse-row-{{ $groupKey }} bg-white align-middle" id="collapse-{{ $groupKey }}">
                                                    <td class="ps-3 text-muted" style="font-size: 0.78rem;">{{ $noIndex - 1 }}.{{ $pIdx + 1 }}</td>
                                                    <td class="ps-5 text-secondary">
                                                        <i class="bi bi-arrow-return-right me-2 text-primary opacity-75"></i>
                                                        <span class="fw-semibold text-dark">{{ $prodi->nama_unit_kerja }}</span>
                                                    </td>
                                                    <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info px-2 py-0.5 rounded-2">{{ $prodi->mou_count }}</span></td>
                                                    <td class="text-center"><span class="badge bg-warning bg-opacity-10 text-dark px-2 py-0.5 rounded-2">{{ $prodi->moa_count }}</span></td>
                                                    <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-2 py-0.5 rounded-2">{{ $prodi->ia_count }}</span></td>
                                                    <td class="text-center small text-muted">{{ $prodi->nasional_count }}</td>
                                                    <td class="text-center small text-muted">{{ $prodi->internasional_count }}</td>
                                                    <td class="text-center small"><span class="badge bg-light text-success border px-2 py-0.5 rounded-pill">{{ $prodi->aktif_count }}</span></td>
                                                    <td class="text-center pe-3 fw-bold text-dark" style="font-size: 0.88rem;">{{ $prodi->total_kerjasama }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="ps-3 py-3 text-dark">TOTAL KESELURUHAN (UNIVERSITAS, FAKULTAS & PRODI)</td>
                                        <td class="text-center py-3 text-info">{{ $rekapUnitKerjaList->sum('mou_count') }}</td>
                                        <td class="text-center py-3 text-warning">{{ $rekapUnitKerjaList->sum('moa_count') }}</td>
                                        <td class="text-center py-3 text-success">{{ $rekapUnitKerjaList->sum('ia_count') }}</td>
                                        <td class="text-center py-3 text-primary">{{ $rekapUnitKerjaList->sum('nasional_count') }}</td>
                                        <td class="text-center py-3" style="color: #6f42c1;">{{ $rekapUnitKerjaList->sum('internasional_count') }}</td>
                                        <td class="text-center py-3 text-success">{{ $rekapUnitKerjaList->sum('aktif_count') }}</td>
                                        <td class="text-center py-3 pe-3 text-primary fs-6">{{ $rekapUnitKerjaList->sum('total_kerjasama') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    {{-- Include ECharts locally loaded from vendor path --}}
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const colors = ['#0d6efd', '#198754', '#0dcaf0', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14'];

            // 1. Grafik Column Rekapitulasi Dokumen (MoU, MoA, IA) per Unit Kerja (Utama)
            const dokUkData = @json($chartDokumenPerUnitKerja);
            const chartDUK = echarts.init(document.getElementById('chartDokumenUnitKerja'));

            chartDUK.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: { type: 'shadow' }
                },
                legend: {
                    top: '0',
                    left: 'center',
                    data: ['MoU', 'MoA', 'IA']
                },
                grid: { left: '3%', right: '4%', bottom: '15%', containLabel: true },
                xAxis: {
                    type: 'category',
                    data: dokUkData.labels,
                    axisLabel: { 
                        fontSize: 11, 
                        fontWeight: 'bold',
                        interval: 0,
                        rotate: 15
                    }
                },
                yAxis: { type: 'value', minInterval: 1 },
                series: [
                    {
                        name: 'MoU',
                        type: 'bar',
                        data: dokUkData.mou,
                        label: { show: true, position: 'top', fontWeight: 'bold', fontSize: 11, color: '#0dcaf0' },
                        itemStyle: { color: '#0dcaf0', borderRadius: [4, 4, 0, 0] },
                        barGap: '15%'
                    },
                    {
                        name: 'MoA',
                        type: 'bar',
                        data: dokUkData.moa,
                        label: { show: true, position: 'top', fontWeight: 'bold', fontSize: 11, color: '#ffc107' },
                        itemStyle: { color: '#ffc107', borderRadius: [4, 4, 0, 0] }
                    },
                    {
                        name: 'IA',
                        type: 'bar',
                        data: dokUkData.ia,
                        label: { show: true, position: 'top', fontWeight: 'bold', fontSize: 11, color: '#198754' },
                        itemStyle: { color: '#198754', borderRadius: [4, 4, 0, 0] }
                    }
                ]
            });

            // 2. Top 5 Unit Kerja (Horizontal Bar - Top Rank at the Top)
            const unitKerjaRaw = @json($unitKerjaData);
            const ukLabels = unitKerjaRaw.map(item => item.label || 'N/A').reverse();
            const ukValues = unitKerjaRaw.map(item => item.value).reverse();

            const chartUK = echarts.init(document.getElementById('chartUnitKerja'));
            chartUK.setOption({
                tooltip: { 
                    trigger: 'axis', 
                    axisPointer: { type: 'shadow' },
                    formatter: function(params) {
                        return '<b>' + params[0].name + '</b><br/>Total Kerjasama: <b>' + params[0].value + ' Dokumen</b>';
                    }
                },
                grid: { left: '3%', right: '22%', bottom: '3%', containLabel: true },
                xAxis: { show: false },
                yAxis: {
                    type: 'category',
                    data: ukLabels,
                    axisLabel: { 
                        fontSize: 11,
                        fontWeight: '500',
                        color: '#212529',
                        formatter: function (value) {
                            if (value.length > 25) {
                                return value.substring(0, 23) + '...';
                            }
                            return value;
                        }
                    }
                },
                series: [{
                    name: 'Total Kerjasama',
                    type: 'bar',
                    data: ukValues,
                    label: {
                        show: true,
                        position: 'right',
                        formatter: '{c} Dokumen',
                        fontWeight: 'bold',
                        fontSize: 11,
                        color: '#0d6efd'
                    },
                    itemStyle: {
                        color: function(params) {
                            if (params.dataIndex === ukValues.length - 1) {
                                return new echarts.graphic.LinearGradient(0, 0, 1, 0, [
                                    { offset: 0, color: '#ffc107' },
                                    { offset: 1, color: '#fd7e14' }
                                ]);
                            }
                            return new echarts.graphic.LinearGradient(0, 0, 1, 0, [
                                { offset: 0, color: '#448cff' },
                                { offset: 1, color: '#0d6efd' }
                            ]);
                        },
                        borderRadius: [0, 6, 6, 0]
                    },
                    barWidth: '45%'
                }]
            });

            // 3. Top 5 Unit Kerja Tahunan (Multi-Series Bar Chart)
            const top5TahunanData = @json($top5TahunanData);
            const chartUKTahunan = echarts.init(document.getElementById('chartUnitKerjaTahunan'));
            
            const paletteColors = ['#fd7e14', '#0d6efd', '#198754', '#6f42c1', '#0dcaf0'];
            const seriesTahunan = top5TahunanData.series.map((item, idx) => ({
                name: item.name,
                type: 'bar',
                data: item.data,
                label: { show: true, position: 'top', fontSize: 10, fontWeight: 'bold', formatter: '{c}' },
                itemStyle: { color: paletteColors[idx % paletteColors.length], borderRadius: [4, 4, 0, 0] }
            }));

            chartUKTahunan.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 10 } },
                grid: { left: '3%', right: '4%', bottom: '18%', containLabel: true },
                xAxis: { type: 'category', data: top5TahunanData.years, axisLabel: { fontSize: 11, fontWeight: 'bold' } },
                yAxis: { type: 'value', minInterval: 1 },
                series: seriesTahunan
            });

            window.switchRankCategory = function(category) {
                const categories = ['total', 'mou', 'moa', 'ia', 'tahunan'];
                
                categories.forEach(cat => {
                    const btn = document.getElementById('btn-rank-' + cat) || document.getElementById('btn-chart-' + cat);
                    if (btn) {
                        if (cat === category) {
                            btn.classList.add('active');
                        } else {
                            btn.classList.remove('active');
                        }
                    }
                });

                const totalChartEl = document.getElementById('chartUnitKerja');
                const tahunanChartEl = document.getElementById('chartUnitKerjaTahunan');
                const rankContainerEl = document.getElementById('rankingListContainer');

                const totalListEl = document.getElementById('rankListTotal');
                const mouListEl = document.getElementById('rankListMou');
                const moaListEl = document.getElementById('rankListMoa');
                const iaListEl = document.getElementById('rankListIa');

                if (category === 'tahunan') {
                    totalChartEl.style.display = 'none';
                    tahunanChartEl.style.display = 'block';
                    rankContainerEl.style.display = 'none';
                    chartUKTahunan.resize();
                } else {
                    totalChartEl.style.display = 'block';
                    tahunanChartEl.style.display = 'none';
                    rankContainerEl.style.display = 'block';

                    totalListEl.style.display = category === 'total' ? 'block' : 'none';
                    mouListEl.style.display = category === 'mou' ? 'block' : 'none';
                    moaListEl.style.display = category === 'moa' ? 'block' : 'none';
                    iaListEl.style.display = category === 'ia' ? 'block' : 'none';

                    chartUK.resize();
                }
            };

            // 4. Trend Kerjasama Per Tahun (Column Chart)
            const thRaw = @json($kerjasamaPerTahunData);
            const thLabels = thRaw.map(item => 'Tahun ' + (item.label || 'N/A'));
            const thValues = thRaw.map(item => item.value);

            const chartTH = echarts.init(document.getElementById('chartKerjasamaPerTahun'));
            chartTH.setOption({
                tooltip: { 
                    trigger: 'axis', 
                    axisPointer: { type: 'shadow' },
                    formatter: '<b>{b}</b><br/>Total Kerjasama: <b>{c} Dokumen</b>'
                },
                grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
                xAxis: {
                    type: 'category',
                    data: thLabels,
                    axisLabel: { fontSize: 11, fontWeight: 'bold', color: '#198754' }
                },
                yAxis: { type: 'value', minInterval: 1 },
                series: [{
                    name: 'Jumlah Kerjasama',
                    type: 'bar',
                    data: thValues,
                    label: { show: true, position: 'top', fontWeight: 'bold', fontSize: 12, color: '#198754', formatter: '{c} Dokumen' },
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: '#198754' },
                            { offset: 1, color: '#20c997' }
                        ]),
                        borderRadius: [6, 6, 0, 0]
                    },
                    barWidth: '35%'
                }]
            });

            // 5. Jenis Dokumen Chart (Donut / Pie)
            const jenisDokRaw = @json($jenisDokumenData);
            const jenisDokChartData = jenisDokRaw.map(item => ({ name: item.label || 'N/A', value: item.value }));

            const chartJD = echarts.init(document.getElementById('chartJenisDokumen'));
            chartJD.setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 11 } },
                series: [{
                    name: 'Jenis Dokumen',
                    type: 'pie',
                    radius: ['45%', '70%'],
                    center: ['50%', '45%'],
                    avoidLabelOverlap: false,
                    data: jenisDokChartData.length ? jenisDokChartData : [{ name: 'Tidak ada data', value: 0 }],
                    color: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'],
                    label: { show: true, fontSize: 11, fontWeight: 'bold', formatter: '{c}' }
                }]
            });

            // 6. Bentuk Kegiatan Terbanyak (Horizontal Bar)
            const bentukKegRaw = @json($bentukKegiatanData);
            const bkLabels = bentukKegRaw.map(item => item.label || 'N/A');
            const bkValues = bentukKegRaw.map(item => item.value);

            const chartBK = echarts.init(document.getElementById('chartBentukKegiatan'));
            chartBK.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                grid: { left: '3%', right: '15%', bottom: '3%', containLabel: true },
                xAxis: { show: false },
                yAxis: {
                    type: 'category',
                    data: bkLabels,
                    axisLabel: { 
                        fontSize: 10,
                        formatter: function (value) {
                            if (value.length > 25) {
                                return value.substring(0, 23) + '...';
                            }
                            return value;
                        }
                    }
                },
                series: [{
                    name: 'Jumlah',
                    type: 'bar',
                    data: bkValues,
                    label: {
                        show: true,
                        position: 'right',
                        fontWeight: 'bold',
                        color: '#0d6efd'
                    },
                    itemStyle: {
                        color: '#0d6efd',
                        borderRadius: [0, 4, 4, 0]
                    },
                    barWidth: '50%'
                }]
            });

            // 7. Top 5 Provinsi Mitra (Vertical Bar)
            const provinsiRaw = @json($provinsiMitraData);
            const provLabels = provinsiRaw.map(item => item.label || 'N/A');
            const provValues = provinsiRaw.map(item => item.value);

            const chartPM = echarts.init(document.getElementById('chartProvinsiMitra'));
            chartPM.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
                xAxis: {
                    type: 'category',
                    data: provLabels,
                    axisLabel: { interval: 0, rotate: 15, fontSize: 10 }
                },
                yAxis: { type: 'value', minInterval: 1 },
                series: [{
                    name: 'Jumlah Mitra',
                    type: 'bar',
                    data: provValues,
                    label: { show: true, position: 'top', fontWeight: 'bold', color: '#dc3545' },
                    itemStyle: {
                        color: '#dc3545',
                        borderRadius: [4, 4, 0, 0]
                    },
                    barWidth: '40%'
                }]
            });

            // 8. Ruang Lingkup Mitra (Pie)
            const lingkupRaw = @json($ruangLingkupData);
            const lingkupChartData = lingkupRaw.map(item => ({ name: item.label || 'Lokal', value: item.value }));
            
            const chartRL = echarts.init(document.getElementById('chartRuangLingkup'));
            chartRL.setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 10 } },
                series: [{
                    type: 'pie',
                    radius: '65%',
                    center: ['50%', '45%'],
                    data: lingkupChartData.length ? lingkupChartData : [{ name: 'Tidak ada data', value: 0 }],
                    color: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
                    label: { show: true, fontSize: 10, formatter: '{c}' }
                }]
            });

            // 9. Jenis Mitra (Pie)
            const jenisMitraRaw = @json($jenisMitraData);
            const jenisMitraChartData = jenisMitraRaw.map(item => ({ name: item.label || 'N/A', value: item.value }));

            const chartJM = echarts.init(document.getElementById('chartJenisMitra'));
            chartJM.setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 10 } },
                series: [{
                    type: 'pie',
                    radius: '65%',
                    center: ['50%', '45%'],
                    data: jenisMitraChartData.length ? jenisMitraChartData : [{ name: 'Tidak ada data', value: 0 }],
                    color: ['#0d6efd', '#6f42c1', '#fd7e14', '#0dcaf0'],
                    label: { show: true, fontSize: 10, formatter: '{c}' }
                }]
            });

            // 10. Top 5 Kriteria Mitra
            const kriteriaRaw = @json($kriteriaMitraData);
            const kritLabels = kriteriaRaw.map(item => item.label || 'N/A');
            const kritValues = kriteriaRaw.map(item => item.value);

            const chartKM = echarts.init(document.getElementById('chartKriteriaMitra'));
            chartKM.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
                xAxis: {
                    type: 'category',
                    data: kritLabels,
                    axisLabel: { interval: 0, rotate: 15, fontSize: 10 }
                },
                yAxis: { type: 'value', minInterval: 1 },
                series: [{
                    name: 'Jumlah Mitra',
                    type: 'bar',
                    data: kritValues,
                    label: { show: true, position: 'top', fontWeight: 'bold', color: '#ffc107' },
                    itemStyle: {
                        color: '#ffc107',
                        borderRadius: [4, 4, 0, 0]
                    },
                    barWidth: '40%'
                }]
            });

            // 11. Implementasi Kegiatan (Donut / Pie)
            const chartIK = echarts.init(document.getElementById('chartImplementasiKegiatan'));
            chartIK.setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 11 } },
                series: [{
                    type: 'pie',
                    radius: ['45%', '70%'],
                    center: ['50%', '45%'],
                    avoidLabelOverlap: false,
                    data: [
                        { name: 'Dengan Hasil Pelaksanaan', value: {{ $denganHasilKegiatan }} },
                        { name: 'Tanpa Hasil Pelaksanaan', value: {{ $tanpaHasilKegiatan }} }
                    ],
                    color: ['#198754', '#6c757d'],
                    label: { show: true, fontSize: 10, formatter: '{c}' }
                }]
            });

            // 12. Implementasi Kerjasama (Donut / Pie)
            const chartICS = echarts.init(document.getElementById('chartImplementasiKerjasama'));
            chartICS.setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 11 } },
                series: [{
                    type: 'pie',
                    radius: ['45%', '70%'],
                    center: ['50%', '45%'],
                    avoidLabelOverlap: false,
                    data: [
                        { name: 'Dengan Hasil Pelaksanaan', value: {{ $denganHasilKerjasama }} },
                        { name: 'Tanpa Hasil Pelaksanaan', value: {{ $tanpaHasilKerjasama }} }
                    ],
                    color: ['#0d6efd', '#6c757d'],
                    label: { show: true, fontSize: 10, formatter: '{c}' }
                }]
            });

            // Handle Chevron Toggle for Collapsible Table Rows
            $('.collapse').on('show.bs.collapse', function() {
                const id = $(this).attr('id').replace('collapse-', '');
                $('#icon-' + id).removeClass('bi-chevron-right').addClass('bi-chevron-down');
            }).on('hide.bs.collapse', function() {
                const id = $(this).attr('id').replace('collapse-', '');
                $('#icon-' + id).removeClass('bi-chevron-down').addClass('bi-chevron-right');
            });

            // Make all charts responsive to window resize
            window.addEventListener('resize', function () {
                chartDUK.resize();
                chartUK.resize();
                chartUKTahunan.resize();
                chartTH.resize();
                chartJD.resize();
                chartBK.resize();
                chartPM.resize();
                chartRL.resize();
                chartJM.resize();
                chartKM.resize();
                chartIK.resize();
                chartICS.resize();
            });
        });
    </script>
@endpush