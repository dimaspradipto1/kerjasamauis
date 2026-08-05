<?php

namespace App\Http\Controllers;

use App\Models\Kerjasama;
use App\Models\Kegiatan;
use App\Models\Mitra;
use App\Models\JenisDokumen;
use App\Models\UnitKerja;
use App\Models\BentukKegiatan;
use App\Models\KriteriaMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunSelected = request('tahun');

        $tahunList = Kerjasama::whereNotNull('tanggal_waktu_berlaku')
            ->selectRaw('YEAR(tanggal_waktu_berlaku) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Statistik Kerjasama Per Tahun (Trend Chart)
        $kerjasamaPerTahunData = Kerjasama::whereNotNull('tanggal_waktu_berlaku')
            ->selectRaw('YEAR(tanggal_waktu_berlaku) as label, count(*) as value')
            ->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();

        $baseKerjasama = Kerjasama::query();
        if ($tahunSelected) {
            $baseKerjasama->whereYear('tanggal_waktu_berlaku', $tahunSelected);
        }

        // 1. Status metrics
        $aktifCount = (clone $baseKerjasama)->where('status_kerjasama', 'Aktif')->count();
        $perpanjanganCount = (clone $baseKerjasama)->where('status_kerjasama', 'Perpanjangan')->count();
        $kedaluwarsaCount = (clone $baseKerjasama)->where('status_kerjasama', 'Kedaluwarsa')->count();
        $tidakAktifCount = (clone $baseKerjasama)->where('status_kerjasama', 'Tidak Aktif')->count();

        // 2. Jenis Dokumen Chart data
        $jenisDokumenData = (clone $baseKerjasama)
            ->join('jenis_dokumens', 'kerjasamas.jenis_dokumen_id', '=', 'jenis_dokumens.id')
            ->selectRaw('jenis_dokumens.nama_jenis_dokumen as label, count(*) as value')
            ->groupBy('jenis_dokumens.id', 'jenis_dokumens.nama_jenis_dokumen')
            ->get();

        // 3. Ruang Lingkup Mitra
        $ruangLingkupData = Mitra::selectRaw('lingkup_mitra as label, count(*) as value')
            ->groupBy('lingkup_mitra')
            ->get();

        // 4. Jenis Mitra
        $jenisMitraData = Mitra::selectRaw('jenis_mitra as label, count(*) as value')
            ->groupBy('jenis_mitra')
            ->get();

        // 5. Bentuk Kegiatan Terbanyak
        $bentukKegiatanData = Kegiatan::join('bentuk_kegiatans', 'kegiatans.bentuk_kegiatan_id', '=', 'bentuk_kegiatans.id')
            ->selectRaw('bentuk_kegiatans.nama_bentuk_kegiatan as label, count(*) as value')
            ->groupBy('bentuk_kegiatans.id', 'bentuk_kegiatans.nama_bentuk_kegiatan')
            ->orderByDesc('value')
            ->limit(5)
            ->get();

        // 6. Rekapitulasi per Unit Kerja / Fakultas (Accurate counting with pivot table & optional Year filter)
        $unitKerjasAll = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();
        $rekapUnitKerjaList = $unitKerjasAll->map(function ($uk) use ($tahunSelected) {
            $kerjasamaIds = DB::table('kerjasama_unit_kerja')
                ->where('unit_kerja_id', $uk->id)
                ->pluck('kerjasama_id')
                ->toArray();

            $query = Kerjasama::where(function ($q) use ($uk, $kerjasamaIds) {
                $q->where('unit_kerja_id', $uk->id);
                if (!empty($kerjasamaIds)) {
                    $q->orWhereIn('id', $kerjasamaIds);
                }
            });

            if ($tahunSelected) {
                $query->whereYear('tanggal_waktu_berlaku', $tahunSelected);
            }

            $uk->total_kerjasama = (clone $query)->count();

            $uk->nasional_count = (clone $query)->where(function ($sub) {
                $sub->whereJsonContains('skala_kerjasama', 'Nasional')
                    ->orWhere('skala_kerjasama', 'like', '%Nasional%');
            })->count();

            $uk->internasional_count = (clone $query)->where(function ($sub) {
                $sub->whereJsonContains('skala_kerjasama', 'Internasional')
                    ->orWhere('skala_kerjasama', 'like', '%Internasional%');
            })->count();

            $uk->mou_count = (clone $query)->whereHas('jenisDokumen', function ($jd) {
                $jd->where('nama_jenis_dokumen', 'like', '%MoU%')
                   ->orWhere('nama_jenis_dokumen', 'like', '%Memorandum of Understanding%');
            })->count();

            $uk->moa_count = (clone $query)->whereHas('jenisDokumen', function ($jd) {
                $jd->where('nama_jenis_dokumen', 'like', '%MoA%')
                   ->orWhere('nama_jenis_dokumen', 'like', '%Memorandum of Agreement%');
            })->count();

            $uk->ia_count = (clone $query)->whereHas('jenisDokumen', function ($jd) {
                $jd->where('nama_jenis_dokumen', 'like', '%IA%')
                   ->orWhere('nama_jenis_dokumen', 'like', '%Implementation Arrangement%');
            })->count();

            return $uk;
        });

        // Top 5 Unit Kerja berdasarkan Total Kerjasama (Filtered by Year if selected)
        $top5UnitKerjaList = $rekapUnitKerjaList->filter(function($uk) {
            return $uk->total_kerjasama > 0;
        })->sortByDesc('total_kerjasama')->take(5)->values();

        if ($top5UnitKerjaList->isEmpty()) {
            $top5UnitKerjaList = $rekapUnitKerjaList->sortByDesc('total_kerjasama')->take(5)->values();
        }

        // Top 5 Unit Kerja berdasarkan MoU, MoA, IA
        $top5MoUList = $rekapUnitKerjaList->sortByDesc('mou_count')->take(5)->values();
        $top5MoAList = $rekapUnitKerjaList->sortByDesc('moa_count')->take(5)->values();
        $top5IAList  = $rekapUnitKerjaList->sortByDesc('ia_count')->take(5)->values();

        $unitKerjaData = $top5UnitKerjaList->map(function($uk) {
            return [
                'label' => $uk->nama_unit_kerja,
                'value' => $uk->total_kerjasama,
                'mou' => $uk->mou_count,
                'moa' => $uk->moa_count,
                'ia' => $uk->ia_count,
            ];
        });

        // Data Grafik Rekapitulasi Vertical Column (MoU, MoA, IA per Unit Kerja)
        $activeUnitKerjas = $rekapUnitKerjaList->filter(function($uk) {
            return $uk->total_kerjasama > 0;
        })->values();

        if ($activeUnitKerjas->isEmpty()) {
            $activeUnitKerjas = $rekapUnitKerjaList->take(6)->values();
        }

        $chartDokumenPerUnitKerja = [
            'labels' => $activeUnitKerjas->map(function($uk) {
                // Clean display label
                return preg_replace('/^(S1|S2|S3|D3)\s*-\s*/i', '', $uk->nama_unit_kerja);
            })->toArray(),
            'full_labels' => $activeUnitKerjas->pluck('nama_unit_kerja')->toArray(),
            'mou'  => $activeUnitKerjas->pluck('mou_count')->toArray(),
            'moa'  => $activeUnitKerjas->pluck('moa_count')->toArray(),
            'ia'   => $activeUnitKerjas->pluck('ia_count')->toArray(),
        ];

        // Data Grafik Tahunan per Top 5 Unit Kerja
        $recentYears = Kerjasama::whereNotNull('tanggal_waktu_berlaku')
            ->selectRaw('YEAR(tanggal_waktu_berlaku) as th')
            ->distinct()
            ->orderBy('th', 'asc')
            ->pluck('th')
            ->toArray();

        if (empty($recentYears)) {
            $recentYears = [(int)date('Y')];
        }

        $top5TahunanSeries = [];
        foreach ($top5UnitKerjaList as $uk) {
            $kerjasamaIds = DB::table('kerjasama_unit_kerja')
                ->where('unit_kerja_id', $uk->id)
                ->pluck('kerjasama_id')
                ->toArray();

            $countsPerYear = [];
            foreach ($recentYears as $yr) {
                $c = Kerjasama::where(function ($q) use ($uk, $kerjasamaIds) {
                    $q->where('unit_kerja_id', $uk->id);
                    if (!empty($kerjasamaIds)) {
                        $q->orWhereIn('id', $kerjasamaIds);
                    }
                })->whereYear('tanggal_waktu_berlaku', $yr)->count();

                $countsPerYear[] = $c;
            }

            $top5TahunanSeries[] = [
                'name' => $uk->nama_unit_kerja,
                'data' => $countsPerYear
            ];
        }

        $top5TahunanData = [
            'years' => array_map(fn($y) => 'Tahun ' . $y, $recentYears),
            'series' => $top5TahunanSeries
        ];

        // 7. Top 5 Provinsi Mitra
        $provinsiMitraData = Mitra::selectRaw('provinsi as label, count(*) as value')
            ->whereNotNull('provinsi')
            ->where('provinsi', '!=', '-')
            ->where('provinsi', '!=', '')
            ->groupBy('provinsi')
            ->orderByDesc('value')
            ->limit(5)
            ->get();

        // 8. Top 5 Kriteria Mitra
        $kriteriaMitraData = Mitra::join('kriteria_mitras', 'mitras.kriteria_mitra_id', '=', 'kriteria_mitras.id')
            ->selectRaw('kriteria_mitras.kriteria_mitra as label, count(*) as value')
            ->groupBy('kriteria_mitras.id', 'kriteria_mitras.kriteria_mitra')
            ->orderByDesc('value')
            ->limit(5)
            ->get();

        // 9. Implementasi Kegiatan
        $denganHasilKegiatan = Kegiatan::whereNotNull('hasil_pelakasanaan')
            ->where('hasil_pelakasanaan', '!=', '')
            ->count();
        $tanpaHasilKegiatan = Kegiatan::where(function($q) {
            $q->whereNull('hasil_pelakasanaan')->orWhere('hasil_pelakasanaan', '');
        })->count();

        // 10. Implementasi Kerjasama
        $denganHasilKerjasama = (clone $baseKerjasama)->whereNotNull('hasil_pelaksanaan')
            ->where('hasil_pelaksanaan', '!=', '')
            ->count();
        $tanpaHasilKerjasama = (clone $baseKerjasama)->where(function($q) {
            $q->whereNull('hasil_pelaksanaan')->orWhere('hasil_pelaksanaan', '');
        })->count();

        // 11. Skala Kerjasama Data (Nasional & Internasional)
        $nasionalCount = (clone $baseKerjasama)->where(function($q) {
            $q->whereJsonContains('skala_kerjasama', 'Nasional')
              ->orWhere('skala_kerjasama', 'like', '%Nasional%');
        })->count();

        $internasionalCount = (clone $baseKerjasama)->where(function($q) {
            $q->whereJsonContains('skala_kerjasama', 'Internasional')
              ->orWhere('skala_kerjasama', 'like', '%Internasional%');
        })->count();

        $lokalCount = (clone $baseKerjasama)->where(function($q) {
            $q->whereJsonContains('skala_kerjasama', 'Lokal')
              ->orWhere('skala_kerjasama', 'like', '%Lokal%');
        })->count();

        // 12. Rekapitulasi Detail Skala Kerjasama
        $skalaTypes = ['Nasional', 'Internasional', 'Lokal'];
        $rekapSkalaDetail = [];
        foreach ($skalaTypes as $stype) {
            $rekapSkalaDetail[$stype] = [
                'total' => (clone $baseKerjasama)->where(function($q) use ($stype) {
                    $q->whereJsonContains('skala_kerjasama', $stype)
                      ->orWhere('skala_kerjasama', 'like', "%{$stype}%");
                })->count(),
                'aktif' => (clone $baseKerjasama)->where('status_kerjasama', 'Aktif')->where(function($q) use ($stype) {
                    $q->whereJsonContains('skala_kerjasama', $stype)
                      ->orWhere('skala_kerjasama', 'like', "%{$stype}%");
                })->count(),
                'perpanjangan' => (clone $baseKerjasama)->where('status_kerjasama', 'Perpanjangan')->where(function($q) use ($stype) {
                    $q->whereJsonContains('skala_kerjasama', $stype)
                      ->orWhere('skala_kerjasama', 'like', "%{$stype}%");
                })->count(),
                'kedaluwarsa' => (clone $baseKerjasama)->where('status_kerjasama', 'Kedaluwarsa')->where(function($q) use ($stype) {
                    $q->whereJsonContains('skala_kerjasama', $stype)
                      ->orWhere('skala_kerjasama', 'like', "%{$stype}%");
                })->count(),
                'tidak_aktif' => (clone $baseKerjasama)->where('status_kerjasama', 'Tidak Aktif')->where(function($q) use ($stype) {
                    $q->whereJsonContains('skala_kerjasama', $stype)
                      ->orWhere('skala_kerjasama', 'like', "%{$stype}%");
                })->count(),
            ];
        }

        return view('layouts.dashboard.index', compact(
            'aktifCount', 'perpanjanganCount', 'kedaluwarsaCount', 'tidakAktifCount',
            'jenisDokumenData', 'ruangLingkupData', 'jenisMitraData', 'bentukKegiatanData',
            'unitKerjaData', 'provinsiMitraData', 'kriteriaMitraData',
            'denganHasilKegiatan', 'tanpaHasilKegiatan', 'denganHasilKerjasama', 'tanpaHasilKerjasama',
            'nasionalCount', 'internasionalCount', 'lokalCount',
            'rekapSkalaDetail', 'rekapUnitKerjaList', 'top5UnitKerjaList',
            'top5MoUList', 'top5MoAList', 'top5IAList',
            'tahunSelected', 'tahunList', 'kerjasamaPerTahunData', 'top5TahunanData',
            'chartDokumenPerUnitKerja'
        ));
    }
}
