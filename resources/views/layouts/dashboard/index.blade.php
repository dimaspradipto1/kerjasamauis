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
                <div class="card shadow-sm border-0 rounded-3 h-100 card-kpi-clickable" style="border-left: 4px solid #198754 !important; cursor: pointer; transition: all 0.2s ease-in-out;" onclick="openDrilldownModal('mou')" title="Klik untuk melihat rincian per-Prodi & berkas MoU">
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
                        <p class="text-muted small mt-2 mb-2" style="font-size: 0.75rem; line-height: 1.3;">Memorandum of Understanding (Nota Kesepahaman)</p>
                        <div class="d-flex align-items-center justify-content-between pt-1 border-top" style="border-color: #f1f5f9 !important;">
                            <span class="text-success fw-semibold" style="font-size: 0.72rem;"><i class="bi bi-cursor-fill me-1"></i> Klik rincian per-Prodi</span>
                            <i class="bi bi-arrow-right-circle text-success" style="font-size: 0.85rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MoA Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100 card-kpi-clickable" style="border-left: 4px solid #0d6efd !important; cursor: pointer; transition: all 0.2s ease-in-out;" onclick="openDrilldownModal('moa')" title="Klik untuk melihat rincian per-Prodi & berkas MoA">
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
                        <p class="text-muted small mt-2 mb-2" style="font-size: 0.75rem; line-height: 1.3;">Memorandum of Agreement (Perjanjian Kerjasama)</p>
                        <div class="d-flex align-items-center justify-content-between pt-1 border-top" style="border-color: #f1f5f9 !important;">
                            <span class="text-primary fw-semibold" style="font-size: 0.72rem;"><i class="bi bi-cursor-fill me-1"></i> Klik rincian per-Prodi</span>
                            <i class="bi bi-arrow-right-circle text-primary" style="font-size: 0.85rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- IA Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100 card-kpi-clickable" style="border-left: 4px solid #6f42c1 !important; cursor: pointer; transition: all 0.2s ease-in-out;" onclick="openDrilldownModal('ia')" title="Klik untuk melihat rincian per-Prodi & berkas IA">
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
                        <p class="text-muted small mt-2 mb-2" style="font-size: 0.75rem; line-height: 1.3;">Implementation Arrangement (Pelaksanaan Kerjasama)</p>
                        <div class="d-flex align-items-center justify-content-between pt-1 border-top" style="border-color: #f1f5f9 !important;">
                            <span class="fw-semibold" style="color: #6f42c1; font-size: 0.72rem;"><i class="bi bi-cursor-fill me-1"></i> Klik rincian per-Prodi & berkas</span>
                            <i class="bi bi-arrow-right-circle" style="color: #6f42c1; font-size: 0.85rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Kerjasama Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100 card-kpi-clickable" style="border-left: 4px solid #fd7e14 !important; cursor: pointer; transition: all 0.2s ease-in-out;" onclick="openDrilldownModal('all')" title="Klik untuk melihat semua rincian per-Prodi & berkas">
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
                        <p class="text-muted small mt-2 mb-2" style="font-size: 0.75rem; line-height: 1.3;">Total keseluruhan dokumen kerjasama.</p>
                        <div class="d-flex align-items-center justify-content-between pt-1 border-top" style="border-color: #f1f5f9 !important;">
                            <span class="fw-semibold" style="color: #fd7e14; font-size: 0.72rem;"><i class="bi bi-cursor-fill me-1"></i> Klik semua berkas & prodi</span>
                            <i class="bi bi-arrow-right-circle" style="color: #fd7e14; font-size: 0.85rem;"></i>
                        </div>
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
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0 item-rank-clickable" style="cursor: pointer; transition: all 0.15s ease-in-out;" onclick="openDrilldownModal('all', {{ $topUk->id }}, '{{ addslashes($topUk->nama_unit_kerja) }}')" title="Klik untuk melihat berkas & dokumen {{ $topUk->nama_unit_kerja }}">
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
                                                    <span class="badge px-2 py-0.5 rounded-2 small fw-bold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 0.72rem;">MoU: {{ $topUk->mou_count }}</span>
                                                    <span class="badge px-2 py-0.5 rounded-2 small fw-bold" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.72rem;">MoA: {{ $topUk->moa_count }}</span>
                                                    <span class="badge px-2 py-0.5 rounded-2 small fw-bold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.72rem;">IA: {{ $topUk->ia_count }}</span>
                                                    <span class="badge px-2.5 py-1 rounded-pill fw-bold ms-1" style="background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 0.8rem;">{{ $topUk->total_kerjasama }} Dokumen <i class="bi bi-chevron-right ms-0.5"></i></span>
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
                                    <h6 class="fw-bold small mb-0" style="color: #0284c7;"><i class="bi bi-file-earmark-text me-1"></i> TOP 5 TERBANYAK MoU</h6>
                                    <span class="badge px-2 py-0.5 rounded-2 fw-bold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">MoU</span>
                                </div>
                                @php $maxMou = $top5MoUList->max('mou_count') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @forelse($top5MoUList as $idx => $mouUk)
                                        @php $pct = round(($mouUk->mou_count / $maxMou) * 100); @endphp
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0 item-rank-clickable" style="cursor: pointer; transition: all 0.15s ease-in-out;" onclick="openDrilldownModal('mou', {{ $mouUk->id }}, '{{ addslashes($mouUk->nama_unit_kerja) }}')" title="Klik untuk melihat berkas & dokumen MoU {{ $mouUk->nama_unit_kerja }}">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge fw-bold rounded-circle" style="background-color: #0284c7; color: #ffffff; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ $idx + 1 }}</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $mouUk->nama_unit_kerja }}</span>
                                                </div>
                                                <span class="badge px-2.5 py-1 rounded-pill fw-bold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 0.8rem;">{{ $mouUk->mou_count }} MoU <i class="bi bi-chevron-right ms-0.5"></i></span>
                                            </div>
                                            <div class="progress mt-1.5" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                                <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $pct }}%; background-color: #0284c7;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                    <h6 class="fw-bold small mb-0" style="color: #b45309;"><i class="bi bi-file-earmark-check me-1"></i> TOP 5 TERBANYAK MoA</h6>
                                    <span class="badge px-2 py-0.5 rounded-2 fw-bold" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">MoA</span>
                                </div>
                                @php $maxMoa = $top5MoAList->max('moa_count') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @forelse($top5MoAList as $idx => $moaUk)
                                        @php $pct = round(($moaUk->moa_count / $maxMoa) * 100); @endphp
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0 item-rank-clickable" style="cursor: pointer; transition: all 0.15s ease-in-out;" onclick="openDrilldownModal('moa', {{ $moaUk->id }}, '{{ addslashes($moaUk->nama_unit_kerja) }}')" title="Klik untuk melihat berkas & dokumen MoA {{ $moaUk->nama_unit_kerja }}">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge fw-bold rounded-circle" style="background-color: #d97706; color: #ffffff; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ $idx + 1 }}</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $moaUk->nama_unit_kerja }}</span>
                                                </div>
                                                <span class="badge px-2.5 py-1 rounded-pill fw-bold" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.8rem;">{{ $moaUk->moa_count }} MoA <i class="bi bi-chevron-right ms-0.5"></i></span>
                                            </div>
                                            <div class="progress mt-1.5" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                                <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $pct }}%; background-color: #d97706;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                    <h6 class="fw-bold small mb-0" style="color: #15803d;"><i class="bi bi-file-earmark-code me-1"></i> TOP 5 TERBANYAK IA</h6>
                                    <span class="badge px-2 py-0.5 rounded-2 fw-bold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">IA</span>
                                </div>
                                @php $maxIa = $top5IAList->max('ia_count') ?: 1; @endphp
                                <div class="d-flex flex-column gap-2">
                                    @forelse($top5IAList as $idx => $iaUk)
                                        @php $pct = round(($iaUk->ia_count / $maxIa) * 100); @endphp
                                        <div class="p-2.5 px-3 rounded-3 bg-light border-0 item-rank-clickable" style="cursor: pointer; transition: all 0.15s ease-in-out;" onclick="openDrilldownModal('ia', {{ $iaUk->id }}, '{{ addslashes($iaUk->nama_unit_kerja) }}')" title="Klik untuk melihat berkas & dokumen IA {{ $iaUk->nama_unit_kerja }}">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge fw-bold rounded-circle" style="background-color: #16a34a; color: #ffffff; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ $idx + 1 }}</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $iaUk->nama_unit_kerja }}</span>
                                                </div>
                                                <span class="badge px-2.5 py-1 rounded-pill fw-bold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.8rem;">{{ $iaUk->ia_count }} IA <i class="bi bi-chevron-right ms-0.5"></i></span>
                                            </div>
                                            <div class="progress mt-1.5" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                                <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $pct }}%; background-color: #16a34a;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                        <th class="py-3 text-dark fw-bold">Fakultas / Program Studi</th>
                                        <th class="text-center py-3" style="color: #0284c7; font-weight: 700;"><i class="bi bi-file-earmark-text me-1"></i> MoU</th>
                                        <th class="text-center py-3" style="color: #b45309; font-weight: 700;"><i class="bi bi-file-earmark-check me-1"></i> MoA</th>
                                        <th class="text-center py-3" style="color: #15803d; font-weight: 700;"><i class="bi bi-file-earmark-code me-1"></i> IA</th>
                                        <th class="text-center py-3" style="color: #0d6efd; font-weight: 700;"><i class="bi bi-flag me-1"></i> Nasional</th>
                                        <th class="text-center py-3" style="color: #6f42c1; font-weight: 700;"><i class="bi bi-globe me-1"></i> Internasional</th>
                                        <th class="text-center py-3" style="color: #15803d; font-weight: 700;"><i class="bi bi-check-circle me-1"></i> Aktif</th>
                                        <th class="text-center py-3 pe-3 text-dark fw-bold">Total Dokumen</th>
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
                                                    <span class="fw-bold text-dark">{{ $group['nama'] }}</span>
                                                </td>
                                                <td class="text-center"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 0.85rem;">{{ $group['mou_count'] }}</span></td>
                                                <td class="text-center"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.85rem;">{{ $group['moa_count'] }}</span></td>
                                                <td class="text-center"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.85rem;">{{ $group['ia_count'] }}</span></td>
                                                <td class="text-center fw-bold" style="color: #0d6efd;">{{ $group['nasional_count'] }}</td>
                                                <td class="text-center fw-bold" style="color: #6f42c1;">{{ $group['internasional_count'] }}</td>
                                                <td class="text-center"><span class="badge px-3 py-1 rounded-pill fw-semibold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.82rem;">{{ $group['aktif_count'] }}</span></td>
                                                <td class="text-center pe-3"><span class="badge px-2.5 py-1 rounded-2 fw-bold" style="background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 0.9rem;">{{ $group['total_kerjasama'] }}</span></td>
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
                                                <td class="text-center"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 0.88rem;">{{ $group['mou_count'] }}</span></td>
                                                <td class="text-center"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.88rem;">{{ $group['moa_count'] }}</span></td>
                                                <td class="text-center"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.88rem;">{{ $group['ia_count'] }}</span></td>
                                                <td class="text-center fw-bold" style="color: #0d6efd;">{{ $group['nasional_count'] }}</td>
                                                <td class="text-center fw-bold" style="color: #6f42c1;">{{ $group['internasional_count'] }}</td>
                                                <td class="text-center"><span class="badge px-3 py-1 rounded-pill fw-semibold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.85rem;">{{ $group['aktif_count'] }}</span></td>
                                                <td class="text-center pe-3"><span class="badge px-2.5 py-1 rounded-2 fw-bold" style="background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 0.95rem;">{{ $group['total_kerjasama'] }}</span></td>
                                            </tr>

                                            {{-- Child Prodi Rows (Collapsible) --}}
                                            @foreach($group['prodis'] as $pIdx => $prodi)
                                                <tr class="collapse collapse-row-{{ $groupKey }} bg-white align-middle" id="collapse-{{ $groupKey }}">
                                                    <td class="ps-3 text-muted" style="font-size: 0.78rem;">{{ $noIndex - 1 }}.{{ $pIdx + 1 }}</td>
                                                    <td class="ps-5 text-secondary">
                                                        <i class="bi bi-arrow-return-right me-2 text-primary opacity-75"></i>
                                                        <span class="fw-semibold text-dark">{{ $prodi->nama_unit_kerja }}</span>
                                                    </td>
                                                    <td class="text-center"><span class="badge px-2 py-0.5 rounded-2 fw-semibold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 0.78rem;">{{ $prodi->mou_count }}</span></td>
                                                    <td class="text-center"><span class="badge px-2 py-0.5 rounded-2 fw-semibold" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.78rem;">{{ $prodi->moa_count }}</span></td>
                                                    <td class="text-center"><span class="badge px-2 py-0.5 rounded-2 fw-semibold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.78rem;">{{ $prodi->ia_count }}</span></td>
                                                    <td class="text-center small text-secondary fw-semibold">{{ $prodi->nasional_count }}</td>
                                                    <td class="text-center small text-secondary fw-semibold">{{ $prodi->internasional_count }}</td>
                                                    <td class="text-center small"><span class="badge px-2.5 py-0.5 rounded-pill fw-semibold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.78rem;">{{ $prodi->aktif_count }}</span></td>
                                                    <td class="text-center pe-3 fw-bold text-dark" style="font-size: 0.88rem;">{{ $prodi->total_kerjasama }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr style="border-top: 2px solid #cbd5e1;">
                                        <td colspan="2" class="ps-3 py-3 text-dark fw-bold" style="font-size: 0.9rem;">
                                            <i class="bi bi-check2-all text-primary me-1"></i> TOTAL KESELURUHAN UNIVERSITAS
                                        </td>
                                        <td class="text-center py-3"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 0.9rem;">{{ $mouTotalCount }}</span></td>
                                        <td class="text-center py-3"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.9rem;">{{ $moaTotalCount }}</span></td>
                                        <td class="text-center py-3"><span class="badge px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.9rem;">{{ $iaTotalCount }}</span></td>
                                        <td class="text-center py-3 fw-bold" style="color: #0d6efd; font-size: 0.95rem;">{{ $nasionalCount }}</td>
                                        <td class="text-center py-3 fw-bold" style="color: #6f42c1; font-size: 0.95rem;">{{ $internasionalCount }}</td>
                                        <td class="text-center py-3"><span class="badge px-3 py-1 rounded-pill fw-bold" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.88rem;">{{ $aktifCount }}</span></td>
                                        <td class="text-center py-3 pe-3"><span class="badge px-3 py-1.5 rounded-2 fw-bold" style="background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 0.95rem;">{{ $totalKerjasamaCount }}</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- MODAL DRILLDOWN REKAPITULASI DOKUMEN & BERKAS PER-PRODI / FAKULTAS        --}}
    {{-- ========================================================================= --}}
    <div class="modal fade" id="modalDrilldownDashboard" tabindex="-1" aria-labelledby="modalDrilldownTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 95%;">
            <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-1 d-flex align-items-center gap-2" id="modalDrilldownTitle">
                            <i class="bi bi-folder2-open"></i> Detail & Berkas Dokumen Kerjasama
                        </h5>
                        <p class="mb-0 text-white-50 small" id="modalDrilldownDescription">
                            Rincian dokumen per Unit Kerja, Fakultas, Program Studi, dan akses langsung berkas fisik yang diunggah.
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-3 p-md-4 bg-light">
                    {{-- Loading State --}}
                    <div id="drilldownLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 fw-semibold text-muted">Memuat rincian data dokumen & berkas...</p>
                    </div>

                    {{-- Content State --}}
                    <div id="drilldownContent" style="display: none;">
                        {{-- 1. Rekapitulasi per Universitas, Fakultas & Prodi --}}
                        <div class="card shadow-sm border-0 rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-diagram-3-fill text-primary fs-5"></i>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">Rincian Sebaran Dokumen per Unit Kerja & Program Studi</h6>
                                        <small class="text-muted">Klik pada kartu Fakultas atau tombol Program Studi untuk memfilter daftar dokumen di bawah ini secara langsung.</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary px-3 py-2 rounded-pill fs-7 fw-bold" id="badgeModalTotalDocs">Total: 0 Dokumen</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="resetUnitFilter()" id="btnResetFilter" style="display: none;">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Tampilkan Semua Unit
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3" id="containerFacultiesCards">
                                    {{-- Dynamically populated by renderSummaryCards(data) --}}
                                </div>
                            </div>
                        </div>

                        {{-- 2. Filter & Pencarian Bar --}}
                        <div class="card shadow-sm border-0 rounded-3 mb-3">
                            <div class="card-body p-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                                            <input type="text" id="inputModalSearch" class="form-control border-start-0" placeholder="Cari judul kerjasama, mitra, atau nomor dokumen..." onkeyup="filterModalTable()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-muted"><i class="bi bi-building"></i></span>
                                            <select id="selectModalUnitFilter" class="form-select" onchange="onSelectUnitChange(this.value)">
                                                <option value="">-- Semua Unit Kerja / Prodi --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-md-end text-muted small">
                                        <span id="labelFilteredCount" class="fw-bold text-primary">Menampilkan 0 dokumen</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Tabel Dokumen & Berkas Fisik --}}
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-files me-2 text-primary"></i> Daftar Dokumen & Berkas Laporan</h6>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill" id="badgeActiveFilterName">Semua Unit</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 480px;">
                                    <table class="table table-hover align-middle mb-0 text-nowrap" id="tableModalDocs">
                                        <thead class="table-light sticky-top" style="z-index: 1;">
                                            <tr class="small text-muted text-uppercase fw-semibold">
                                                <th class="ps-3" style="width: 50px;">No</th>
                                                <th>No. Dokumen</th>
                                                <th>Unit Kerja</th>
                                                <th>Mitra</th>
                                                <th>Judul Kerjasama</th>
                                                <th>Durasi</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Dokumen / Berkas Fisik</th>
                                                <th class="text-center pe-3" style="width: 80px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyModalDocs" class="small">
                                            {{-- Rows dynamically generated --}}
                                        </tbody>
                                    </table>
                                </div>
                                <div id="emptyModalDocs" class="text-center py-5 text-muted" style="display: none;">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-semibold">Tidak ditemukan dokumen kerjasama yang sesuai dengan filter.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white py-2 px-4 justify-content-between">
                    <span class="small text-muted"><i class="bi bi-info-circle me-1"></i> Data dan berkas tersinkronisasi langsung dari database & storage aplikasi.</span>
                    <button type="button" class="btn btn-secondary rounded-2 px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles for Clickable Cards & Badges --}}
    <style>
        .card-kpi-clickable:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.12) !important;
        }
        .item-rank-clickable:hover {
            background-color: #e2e8f0 !important;
            transform: translateX(4px);
        }
        .prodi-pill-btn {
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }
        .prodi-pill-btn:hover {
            filter: brightness(0.92);
            transform: translateY(-1px);
        }
        .prodi-pill-btn.active-filter {
            box-shadow: 0 0 0 2px #0d6efd !important;
            font-weight: 700 !important;
            filter: brightness(0.90);
        }
    </style>

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
                        label: { show: true, position: 'top', fontWeight: 'bold', fontSize: 11, color: '#0284c7' },
                        itemStyle: { color: '#0dcaf0', borderRadius: [4, 4, 0, 0] },
                        barGap: '15%'
                    },
                    {
                        name: 'MoA',
                        type: 'bar',
                        data: dokUkData.moa,
                        label: { show: true, position: 'top', fontWeight: 'bold', fontSize: 11, color: '#b45309' },
                        itemStyle: { color: '#ffc107', borderRadius: [4, 4, 0, 0] }
                    },
                    {
                        name: 'IA',
                        type: 'bar',
                        data: dokUkData.ia,
                        label: { show: true, position: 'top', fontWeight: 'bold', fontSize: 11, color: '#15803d' },
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
                    label: { show: true, position: 'top', fontWeight: 'bold', color: '#b45309' },
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

    {{-- SCRIPT INTERAKTIF MODAL DRILLDOWN DOKUMEN & BERKAS PER-PRODI --}}
    <script>
        let currentDrilldownData = null;
        let activeUnitFilterId = null;
        let activeUnitFilterName = null;
        let activeFacultyKey = null;

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function escapeJs(str) {
            if (!str) return '';
            return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
        }

        function openDrilldownModal(type = 'all', unitKerjaId = null, unitKerjaName = null) {
            const modalEl = document.getElementById('modalDrilldownDashboard');
            if (!modalEl) return;
            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            bsModal.show();

            // Set state to loading
            document.getElementById('drilldownLoading').style.display = 'block';
            document.getElementById('drilldownContent').style.display = 'none';

            // Reset search input
            const searchInput = document.getElementById('inputModalSearch');
            if (searchInput) searchInput.value = '';

            // Fetch data from /dashboard/drilldown
            const filterTahunSelect = document.getElementById('filter-tahun-header');
            const filterTahun = filterTahunSelect ? filterTahunSelect.value : '';
            let url = '{{ route("dashboard.drilldown") }}?type=' + encodeURIComponent(type);
            if (filterTahun) {
                url += '&tahun=' + encodeURIComponent(filterTahun);
            }
            if (unitKerjaId) {
                url += '&unit_kerja_id=' + encodeURIComponent(unitKerjaId);
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        currentDrilldownData = data;
                        activeUnitFilterId = unitKerjaId ? parseInt(unitKerjaId) : (data.selected_unit_id ? parseInt(data.selected_unit_id) : null);
                        activeUnitFilterName = unitKerjaName || data.selected_unit_name || null;
                        activeFacultyKey = null;

                        // Check if activeUnitFilterId matches a faculty
                        if (activeUnitFilterId && data.faculties) {
                            const matchedFac = data.faculties.find(f => f.id === activeUnitFilterId);
                            if (matchedFac) activeFacultyKey = matchedFac.key;
                        }

                        // Update header info
                        const modalTitle = document.getElementById('modalDrilldownTitle');
                        const modalDesc = document.getElementById('modalDrilldownDescription');
                        const badgeTotal = document.getElementById('badgeModalTotalDocs');

                        let iconClass = 'bi-folder2-open';
                        if (data.type === 'mou') iconClass = 'bi-file-earmark-check-fill text-success';
                        else if (data.type === 'moa') iconClass = 'bi-file-earmark-text-fill text-primary';
                        else if (data.type === 'ia') iconClass = 'bi-journal-check text-purple';

                        modalTitle.innerHTML = `<i class="bi ${iconClass}"></i> Rincian & Berkas Dokumen: <span class="text-warning">${data.type_title}</span>`;
                        modalDesc.innerHTML = `Data sebaran dokumen per Fakultas, Program Studi, dan berkas fisik laporan yang diunggah.` + (filterTahun ? ` (Filter Tahun: <b>${filterTahun}</b>)` : '');
                        badgeTotal.textContent = `Total Dokumen: ${data.total_count}`;

                        // Render Summary Cards (UIS, FEB, FST, FIKes & child prodis)
                        renderSummaryCards(data);

                        // Populate unit select options
                        populateUnitSelectOptions(data.unit_kerjas_options, activeUnitFilterId);

                        // Filter and render table
                        filterModalTable();

                        // Show content
                        document.getElementById('drilldownLoading').style.display = 'none';
                        document.getElementById('drilldownContent').style.display = 'block';
                    } else {
                        alert('Gagal memuat data: ' + (data.message || 'Terjadi kesalahan sistem'));
                    }
                })
                .catch(err => {
                    console.error('Error fetching drilldown data:', err);
                    document.getElementById('drilldownLoading').innerHTML = `
                        <div class="text-danger py-4">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                            <p class="mt-2 fw-semibold">Gagal memuat data dari server. Silakan coba kembali.</p>
                        </div>
                    `;
                });
        }

        function renderSummaryCards(data) {
            const container = document.getElementById('containerFacultiesCards');
            if (!container) return;
            container.innerHTML = '';

            // 1. UIS Card
            if (data.uis) {
                const uis = data.uis;
                const isUisActive = (activeUnitFilterId === uis.id);
                const colUis = document.createElement('div');
                colUis.className = 'col-lg-3 col-md-6';
                colUis.innerHTML = `
                    <div class="card h-100 border-0 shadow-sm prodi-pill-btn ${isUisActive ? 'active-filter' : ''}" 
                         id="card-unit-${uis.id}"
                         style="background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%); border-left: 4px solid #0d6efd !important; cursor: pointer; border-radius: 10px;"
                         onclick="applyUnitFilter(${uis.id}, '${escapeJs(uis.name)}')">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary px-2.5 py-1 rounded-2 fw-bold" style="font-size: 0.75rem;">Universitas</span>
                                    <span class="badge bg-white text-primary border border-primary-subtle fw-bold fs-6 shadow-sm px-2.5 py-1">${uis.count} Dokumen</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem; line-height: 1.3;">${escapeHtml(uis.name)}</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.78rem;">Dokumen level pimpinan universitas UIS.</p>
                            </div>
                            <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                <small class="text-primary fw-bold" style="font-size: 0.75rem;"><i class="bi bi-filter me-1"></i> Klik untuk filter</small>
                                <i class="bi bi-chevron-right text-primary small"></i>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(colUis);
            }

            // 2. Faculty Cards (FEB, FST, FIKes)
            const facConf = {
                'feb': { 
                    border: '#fd7e14', 
                    bgHeader: 'linear-gradient(135deg, #ffffff 0%, #fff7ed 100%)', 
                    badgeClass: 'bg-warning text-dark', 
                    pillBg: '#fff7ed', 
                    pillBorder: '#fed7aa', 
                    pillColor: '#9a3412',
                    totalBadgeStyle: 'background-color: #ffedd5; color: #c2410c; border: 1px solid #fed7aa;'
                },
                'fst': { 
                    border: '#0284c7', 
                    bgHeader: 'linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%)', 
                    badgeClass: 'bg-info text-dark', 
                    pillBg: '#f0f9ff', 
                    pillBorder: '#bae6fd', 
                    pillColor: '#0369a1',
                    totalBadgeStyle: 'background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;'
                },
                'fikes': { 
                    border: '#15803d', 
                    bgHeader: 'linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%)', 
                    badgeClass: 'bg-success text-white', 
                    pillBg: '#f0fdf4', 
                    pillBorder: '#bbf7d0', 
                    pillColor: '#166534',
                    totalBadgeStyle: 'background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;'
                }
            };

            if (data.faculties && data.faculties.length > 0) {
                data.faculties.forEach(fac => {
                    const conf = facConf[fac.key] || { 
                        border: '#6c757d', 
                        bgHeader: '#ffffff', 
                        badgeClass: 'bg-secondary text-white', 
                        pillBg: '#f8f9fa', 
                        pillBorder: '#e2e8f0', 
                        pillColor: '#334155',
                        totalBadgeStyle: 'background-color: #f1f5f9; color: #334155;'
                    };

                    const isFacActive = (activeFacultyKey === fac.key || (fac.id && activeUnitFilterId === fac.id));

                    let prodisHtml = '';
                    if (fac.prodis && fac.prodis.length > 0) {
                        fac.prodis.forEach(p => {
                            const isPActive = (activeUnitFilterId === p.id);
                            prodisHtml += `
                                <div class="prodi-pill-btn p-1.5 px-2 rounded-2 d-flex align-items-center justify-content-between ${isPActive ? 'active-filter' : ''}"
                                     id="pill-unit-${p.id}"
                                     style="background-color: ${conf.pillBg}; border: 1px solid ${conf.pillBorder}; cursor: pointer;"
                                     onclick="applyUnitFilter(${p.id}, '${escapeJs(p.name)}')">
                                    <span class="text-truncate fw-semibold me-1" style="color: ${conf.pillColor}; font-size: 0.77rem;" title="${escapeHtml(p.name)}">
                                        ${escapeHtml(p.name)}
                                    </span>
                                    <span class="badge bg-white text-dark border px-1.5 py-0.5 fw-bold shadow-sm" style="font-size: 0.74rem;">
                                        ${p.count}
                                    </span>
                                </div>
                            `;
                        });
                    } else {
                        prodisHtml = '<span class="text-muted small fst-italic">Belum ada prodi</span>';
                    }

                    const colFac = document.createElement('div');
                    colFac.className = 'col-lg-3 col-md-6';
                    colFac.innerHTML = `
                        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid ${conf.border} !important; border-radius: 10px; background-color: #ffffff;">
                            <div class="card-body p-3">
                                {{-- Faculty Header (Clickable for whole faculty) --}}
                                <div class="p-2 rounded-2 mb-2.5 prodi-pill-btn ${isFacActive ? 'active-filter' : ''}" 
                                     id="card-fac-${fac.key}"
                                     style="background: ${conf.bgHeader}; border: 1px solid ${conf.pillBorder}; cursor: pointer;"
                                     onclick="applyFacultyFilter('${fac.key}', '${escapeJs(fac.name)}', ${fac.id ? fac.id : 'null'})">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="badge ${conf.badgeClass} px-2 py-0.5 fw-bold rounded-2" style="font-size: 0.72rem;">${escapeHtml(fac.short_name)}</span>
                                        <span class="badge px-2.5 py-0.5 rounded-pill fw-bold shadow-sm" style="${conf.totalBadgeStyle}; font-size: 0.75rem;">
                                            Total: ${fac.total_count}
                                        </span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.88rem;" title="${escapeHtml(fac.name)}">${escapeHtml(fac.name)}</h6>
                                    <div class="d-flex align-items-center justify-content-between mt-1 pt-1 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                                        <small class="fw-bold" style="color: ${conf.pillColor}; font-size: 0.7rem;"><i class="bi bi-funnel me-0.5"></i> Semua prodi ${fac.short_name}</small>
                                        <i class="bi bi-chevron-right small" style="color: ${conf.pillColor};"></i>
                                    </div>
                                </div>

                                {{-- Prodi Pills List --}}
                                <div class="d-flex flex-column gap-1.5">
                                    ${prodisHtml}
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(colFac);
                });
            }
        }

        function populateUnitSelectOptions(options, selectedId) {
            const selectEl = document.getElementById('selectModalUnitFilter');
            if (!selectEl) return;
            selectEl.innerHTML = '<option value="">-- Semua Unit Kerja / Program Studi --</option>';
            if (options && options.length > 0) {
                options.forEach(opt => {
                    const isSelected = (selectedId && parseInt(selectedId) === parseInt(opt.id)) ? 'selected' : '';
                    selectEl.innerHTML += `<option value="${opt.id}" ${isSelected}>${escapeHtml(opt.name)}</option>`;
                });
            }
        }

        function applyUnitFilter(unitId, unitName) {
            activeUnitFilterId = parseInt(unitId);
            activeUnitFilterName = unitName;
            activeFacultyKey = null;

            // Sync with select dropdown
            const selectEl = document.getElementById('selectModalUnitFilter');
            if (selectEl) selectEl.value = unitId;

            updateFilterVisuals();
            filterModalTable();
        }

        function applyFacultyFilter(facKey, facName, facId = null) {
            activeFacultyKey = facKey;
            activeUnitFilterName = facName;
            activeUnitFilterId = facId ? parseInt(facId) : null;

            // Sync select dropdown
            const selectEl = document.getElementById('selectModalUnitFilter');
            if (selectEl) {
                selectEl.value = facId ? facId : '';
            }

            updateFilterVisuals();
            filterModalTable();
        }

        function resetUnitFilter() {
            activeUnitFilterId = null;
            activeUnitFilterName = null;
            activeFacultyKey = null;

            const selectEl = document.getElementById('selectModalUnitFilter');
            if (selectEl) selectEl.value = '';

            const searchInput = document.getElementById('inputModalSearch');
            if (searchInput) searchInput.value = '';

            updateFilterVisuals();
            filterModalTable();
        }

        function onSelectUnitChange(val) {
            if (!val) {
                resetUnitFilter();
            } else {
                const selectEl = document.getElementById('selectModalUnitFilter');
                const selectedText = selectEl.options[selectEl.selectedIndex].text;
                applyUnitFilter(parseInt(val), selectedText);
            }
        }

        function updateFilterVisuals() {
            // Update Reset button visibility
            const btnReset = document.getElementById('btnResetFilter');
            if (btnReset) {
                btnReset.style.display = (activeUnitFilterId || activeFacultyKey) ? 'inline-block' : 'none';
            }

            // Update badge active filter name
            const badgeFilter = document.getElementById('badgeActiveFilterName');
            if (badgeFilter) {
                if (activeUnitFilterName) {
                    badgeFilter.textContent = 'Filter: ' + activeUnitFilterName;
                    badgeFilter.className = 'badge bg-primary text-white px-3 py-1.5 rounded-pill shadow-sm';
                } else {
                    badgeFilter.textContent = 'Semua Unit Kerja';
                    badgeFilter.className = 'badge bg-light text-muted border px-3 py-1.5 rounded-pill';
                }
            }

            // Update active pill classes
            document.querySelectorAll('.prodi-pill-btn').forEach(el => el.classList.remove('active-filter'));

            if (activeUnitFilterId) {
                const unitCard = document.getElementById('card-unit-' + activeUnitFilterId);
                if (unitCard) unitCard.classList.add('active-filter');

                const unitPill = document.getElementById('pill-unit-' + activeUnitFilterId);
                if (unitPill) unitPill.classList.add('active-filter');
            }

            if (activeFacultyKey) {
                const facCard = document.getElementById('card-fac-' + activeFacultyKey);
                if (facCard) facCard.classList.add('active-filter');
            }
        }

        function filterModalTable() {
            if (!currentDrilldownData || !currentDrilldownData.documents) return;

            const searchQuery = (document.getElementById('inputModalSearch')?.value || '').trim().toLowerCase();
            const docs = currentDrilldownData.documents;

            // Find faculty definition if faculty filter is active
            let facultyUnitIds = null;
            if (activeFacultyKey && currentDrilldownData.faculties) {
                const facObj = currentDrilldownData.faculties.find(f => f.key === activeFacultyKey);
                if (facObj && facObj.unit_ids) {
                    facultyUnitIds = facObj.unit_ids;
                }
            }

            const filtered = docs.filter(doc => {
                // 1. Unit filter
                if (activeFacultyKey && facultyUnitIds) {
                    const hasIntersection = (doc.unit_ids || []).some(id => facultyUnitIds.includes(parseInt(id)));
                    const matchesName = activeUnitFilterName && doc.unit_kerja.toLowerCase().includes(activeUnitFilterName.toLowerCase());
                    if (!hasIntersection && !matchesName) return false;
                } else if (activeUnitFilterId) {
                    const hasId = (doc.unit_ids || []).includes(activeUnitFilterId);
                    const matchesName = activeUnitFilterName && doc.unit_kerja.toLowerCase().includes(activeUnitFilterName.toLowerCase());
                    if (!hasId && !matchesName) return false;
                }

                // 2. Search query filter
                if (searchQuery) {
                    const combined = (
                        (doc.nomor_dokumen_kerjasama || '') + ' ' +
                        (doc.nomor_dokumen_mitra || '') + ' ' +
                        (doc.judul_kerjasama || '') + ' ' +
                        (doc.mitra || '') + ' ' +
                        (doc.unit_kerja || '') + ' ' +
                        (doc.jenis_dokumen || '')
                    ).toLowerCase();
                    if (!combined.includes(searchQuery)) return false;
                }

                return true;
            });

            // Update counter
            const labelCount = document.getElementById('labelFilteredCount');
            if (labelCount) {
                labelCount.textContent = `Menampilkan ${filtered.length} dari ${docs.length} dokumen`;
            }

            // Render table
            renderDocumentsTable(filtered);
        }

        function renderDocumentsTable(docs) {
            const tbody = document.getElementById('tbodyModalDocs');
            const emptyEl = document.getElementById('emptyModalDocs');
            const table = document.getElementById('tableModalDocs');
            if (!tbody) return;

            if (docs.length === 0) {
                tbody.innerHTML = '';
                if (emptyEl) emptyEl.style.display = 'block';
                if (table) table.style.display = 'none';
                return;
            }

            if (emptyEl) emptyEl.style.display = 'none';
            if (table) table.style.display = 'table';

            let html = '';
            docs.forEach((doc, idx) => {
                // Status badge
                let statusBadge = '<span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1 rounded-pill">Aktif</span>';
                if (doc.status_kerjasama && doc.status_kerjasama.toLowerCase().includes('berakhir')) {
                    statusBadge = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1 rounded-pill">Berakhir</span>';
                } else if (doc.status_kerjasama && (doc.status_kerjasama.toLowerCase().includes('perpanjang') || doc.status_kerjasama.toLowerCase().includes('proses'))) {
                    statusBadge = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-2 py-1 rounded-pill">Perpanjangan</span>';
                } else if (doc.status_kerjasama && doc.status_kerjasama.toLowerCase().includes('tidak')) {
                    statusBadge = '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">Tidak Aktif</span>';
                }

                // Files button
                let filesHtml = '';
                if (doc.files && doc.files.length > 0) {
                    filesHtml = '<div class="d-flex flex-column gap-1">';
                    doc.files.forEach(f => {
                        const icon = f.is_pdf ? 'bi-file-earmark-pdf-fill text-danger' : 'bi-file-earmark-text-fill text-primary';
                        const btnClass = f.is_pdf ? 'btn-outline-danger' : 'btn-outline-primary';
                        filesHtml += `
                            <a href="${f.url}" target="_blank" class="btn btn-sm ${btnClass} d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-2 shadow-sm text-decoration-none" title="Buka berkas ${escapeHtml(f.name)}" style="font-size: 0.76rem;">
                                <i class="bi ${icon}"></i>
                                <span class="text-truncate" style="max-width: 160px;">${escapeHtml(f.name)}</span>
                                <i class="bi bi-box-arrow-up-right small ms-1 opacity-75"></i>
                            </a>
                        `;
                    });
                    filesHtml += '</div>';
                } else {
                    filesHtml = '<span class="badge bg-white text-muted border px-2 py-1 rounded-2 fw-normal" style="font-size: 0.74rem;"><i class="bi bi-file-earmark-x me-1"></i> Belum ada berkas</span>';
                }

                html += `
                    <tr>
                        <td class="ps-3 text-muted fw-semibold">${idx + 1}</td>
                        <td>
                            <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">${escapeHtml(doc.nomor_dokumen_kerjasama)}</span>
                            ${doc.nomor_dokumen_mitra && doc.nomor_dokumen_mitra !== '-' ? `<small class="text-muted">Mitra: ${escapeHtml(doc.nomor_dokumen_mitra)}</small>` : ''}
                            <span class="badge bg-light text-primary border mt-0.5" style="font-size: 0.72rem;">${escapeHtml(doc.jenis_dokumen)}</span>
                        </td>
                        <td style="max-width: 200px;">
                            <span class="badge bg-light text-dark border text-wrap text-start px-2 py-1" style="font-size: 0.78rem; line-height: 1.3;">
                                <i class="bi bi-building me-1 text-primary"></i> ${escapeHtml(doc.unit_kerja)}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark" style="font-size: 0.85rem;">${escapeHtml(doc.mitra)}</span>
                        </td>
                        <td style="max-width: 260px;">
                            <a href="${doc.detail_url}" class="fw-semibold text-primary text-decoration-none text-wrap d-inline-block" style="font-size: 0.84rem; line-height: 1.35;" title="Lihat rincian kerjasama">
                                ${escapeHtml(doc.judul_kerjasama)}
                            </a>
                        </td>
                        <td class="text-muted" style="font-size: 0.78rem;">
                            <i class="bi bi-calendar3 me-1 text-primary"></i> ${escapeHtml(doc.durasi)}
                        </td>
                        <td class="text-center">${statusBadge}</td>
                        <td class="text-center">${filesHtml}</td>
                        <td class="text-center pe-3">
                            <a href="${doc.detail_url}" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 0.78rem;">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }
    </script>
@endpush