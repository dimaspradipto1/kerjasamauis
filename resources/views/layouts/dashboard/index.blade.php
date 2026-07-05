@extends('layouts.dashboard.template')

@section('content')

    <div class="pagetitle mb-3">
        <h1 class="fw-bold text-dark">Dashboard</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        {{-- Row 1: 4 Status Metrics Cards --}}
        <div class="row g-3 mb-4">
            {{-- Aktif Card --}}
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #198754 !important;">
                    <div class="card-body p-3">
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
                    <div class="card-body p-3">
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
                    <div class="card-body p-3">
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
                    <div class="card-body p-3">
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

        {{-- Row 2: Jenis Dokumen, Ruang Lingkup Mitra, Jenis Mitra --}}
        <div class="row g-3 mb-4">
            {{-- Jenis Dokumen --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-bar-graph me-1"></i> Jenis Dokumen</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartJenisDokumen" style="min-height: 280px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Ruang Lingkup Mitra --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-globe me-1"></i> Ruang Lingkup Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartRuangLingkup" style="min-height: 280px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Jenis Mitra --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-tag me-1"></i> Jenis Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartJenisMitra" style="min-height: 280px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: Bentuk Kegiatan Terbanyak, Top 5 Unit Kerja --}}
        <div class="row g-3 mb-4">
            {{-- Bentuk Kegiatan Terbanyak --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-grid-3x3-gap me-1"></i> Bentuk Kegiatan Terbanyak</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartBentukKegiatan" style="min-height: 320px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Top 5 Unit Kerja --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-building me-1"></i> Top 5 Unit Kerja</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartUnitKerja" style="min-height: 320px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 4: Top 5 Provinsi Mitra, Top 5 Kriteria Mitra --}}
        <div class="row g-3 mb-4">
            {{-- Top 5 Provinsi Mitra --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-map me-1"></i> Top 5 Provinsi Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartProvinsiMitra" style="min-height: 320px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Top 5 Kriteria Mitra --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bookmark-star me-1"></i> Top 5 Kriteria Mitra</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartKriteriaMitra" style="min-height: 320px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 5: Implementasi Kegiatan, Implementasi Kerjasama --}}
        <div class="row g-3">
            {{-- Implementasi Kegiatan --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-gear-wide-connected me-1"></i> Implementasi Kegiatan</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartImplementasiKegiatan" style="min-height: 300px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Implementasi Kerjasama --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-layout-text-sidebar me-1"></i> Implementasi Kerjasama</h6>
                    </div>
                    <div class="card-body p-3">
                        <div id="chartImplementasiKerjasama" style="min-height: 300px; width: 100%;"></div>
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
            // Colors scheme configuration
            const colors = ['#0d6efd', '#198754', '#0dcaf0', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14'];

            // 1. Jenis Dokumen Chart (Bar)
            const jenisDokumenRaw = @json($jenisDokumenData);
            const jdLabels = jenisDokumenRaw.map(item => item.label || 'N/A');
            const jdValues = jenisDokumenRaw.map(item => item.value);
            
            const chartJD = echarts.init(document.getElementById('chartJenisDokumen'));
            chartJD.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
                xAxis: {
                    type: 'category',
                    data: jdLabels,
                    axisLabel: { interval: 0, rotate: 15, fontSize: 10 }
                },
                yAxis: { type: 'value', minInterval: 1 },
                series: [{
                    name: 'Jumlah',
                    type: 'bar',
                    data: jdValues,
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: '#0d6efd' },
                            { offset: 1, color: '#0b5ed7' }
                        ]),
                        borderRadius: [4, 4, 0, 0]
                    },
                    barWidth: '40%'
                }]
            });

            // 2. Ruang Lingkup Mitra (Pie)
            const lingkupRaw = @json($ruangLingkupData);
            const lingkupChartData = lingkupRaw.map(item => ({ name: item.label || 'Lokal', value: item.value }));
            
            const chartRL = echarts.init(document.getElementById('chartRuangLingkup'));
            chartRL.setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 11 } },
                series: [{
                    type: 'pie',
                    radius: '65%',
                    center: ['50%', '45%'],
                    data: lingkupChartData.length ? lingkupChartData : [{ name: 'Tidak ada data', value: 0 }],
                    color: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
                    label: { show: true, fontSize: 10, formatter: '{c}' }
                }]
            });

            // 3. Jenis Mitra (Pie)
            const jenisMitraRaw = @json($jenisMitraData);
            const jenisMitraChartData = jenisMitraRaw.map(item => ({ name: item.label || 'N/A', value: item.value }));

            const chartJM = echarts.init(document.getElementById('chartJenisMitra'));
            chartJM.setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: '0', left: 'center', itemWidth: 10, itemHeight: 10, textStyle: { fontSize: 11 } },
                series: [{
                    type: 'pie',
                    radius: '65%',
                    center: ['50%', '45%'],
                    data: jenisMitraChartData.length ? jenisMitraChartData : [{ name: 'Tidak ada data', value: 0 }],
                    color: ['#0d6efd', '#6f42c1', '#fd7e14', '#0dcaf0'],
                    label: { show: true, fontSize: 10, formatter: '{c}' }
                }]
            });

            // 4. Bentuk Kegiatan Terbanyak (Horizontal Bar)
            const bentukKegRaw = @json($bentukKegiatanData);
            const bkLabels = bentukKegRaw.map(item => item.label || 'N/A');
            const bkValues = bentukKegRaw.map(item => item.value);

            const chartBK = echarts.init(document.getElementById('chartBentukKegiatan'));
            chartBK.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                grid: { left: '3%', right: '8%', bottom: '3%', containLabel: true },
                xAxis: { type: 'value', minInterval: 1 },
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
                    itemStyle: {
                        color: '#0dcaf0',
                        borderRadius: [0, 4, 4, 0]
                    },
                    barWidth: '50%'
                }]
            });

            // 5. Top 5 Unit Kerja (Horizontal Bar)
            const unitKerjaRaw = @json($unitKerjaData);
            const ukLabels = unitKerjaRaw.map(item => item.label || 'N/A');
            const ukValues = unitKerjaRaw.map(item => item.value);

            const chartUK = echarts.init(document.getElementById('chartUnitKerja'));
            chartUK.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                grid: { left: '3%', right: '8%', bottom: '3%', containLabel: true },
                xAxis: { type: 'value', minInterval: 1 },
                yAxis: {
                    type: 'category',
                    data: ukLabels,
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
                    data: ukValues,
                    itemStyle: {
                        color: '#0d6efd',
                        borderRadius: [0, 4, 4, 0]
                    },
                    barWidth: '50%'
                }]
            });

            // 6. Top 5 Provinsi Mitra (Vertical Bar)
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
                    name: 'Mitra',
                    type: 'bar',
                    data: provValues,
                    itemStyle: {
                        color: '#198754',
                        borderRadius: [4, 4, 0, 0]
                    },
                    barWidth: '40%'
                }]
            });

            // 7. Top 5 Kriteria Mitra (Horizontal Bar)
            const kriteriaRaw = @json($kriteriaMitraData);
            const kritLabels = kriteriaRaw.map(item => item.label || 'N/A');
            const kritValues = kriteriaRaw.map(item => item.value);

            const chartKM = echarts.init(document.getElementById('chartKriteriaMitra'));
            chartKM.setOption({
                tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                grid: { left: '3%', right: '8%', bottom: '3%', containLabel: true },
                xAxis: { type: 'value', minInterval: 1 },
                yAxis: {
                    type: 'category',
                    data: kritLabels,
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
                    name: 'Mitra',
                    type: 'bar',
                    data: kritValues,
                    itemStyle: {
                        color: '#fd7e14',
                        borderRadius: [0, 4, 4, 0]
                    },
                    barWidth: '50%'
                }]
            });

            // 8. Implementasi Kegiatan (Donut / Pie)
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

            // 9. Implementasi Kerjasama (Donut / Pie)
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

            // Make all charts responsive to window resize
            window.addEventListener('resize', function () {
                chartJD.resize();
                chartRL.resize();
                chartJM.resize();
                chartBK.resize();
                chartUK.resize();
                chartPM.resize();
                chartKM.resize();
                chartIK.resize();
                chartICS.resize();
            });
        });
    </script>
@endpush