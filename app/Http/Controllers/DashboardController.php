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
        $today = now()->toDateString();

        $tahunList = Kerjasama::whereNotNull('tanggal_waktu_berlaku')
            ->selectRaw('YEAR(tanggal_waktu_berlaku) as tahun')
            ->whereRaw('YEAR(tanggal_waktu_berlaku) >= 2000')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($tahunList->isEmpty()) {
            $tahunList = collect(range(now()->year, now()->year - 4));
        }

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

        // Helper closures for synchronized conditional counting
        $applyMou = function ($q) {
            $q->where(function ($sub) {
                $sub->where('jenis_dokumen_id', 3)
                    ->orWhereHas('jenisDokumen', function ($jd) {
                        $jd->where('nama_jenis_dokumen', 'like', '%MoU%')
                           ->orWhere('nama_jenis_dokumen', 'like', '%Memorandum of Understanding%')
                           ->orWhere('nama_jenis_dokumen', 'like', '%Kesepahaman%');
                    });
            });
        };

        $applyMoa = function ($q) {
            $q->where(function ($sub) {
                $sub->where('jenis_dokumen_id', 2)
                    ->orWhereHas('jenisDokumen', function ($jd) {
                        $jd->where('nama_jenis_dokumen', 'like', '%MoA%')
                           ->orWhere('nama_jenis_dokumen', 'like', '%Memorandum of Agreement%')
                           ->orWhere('nama_jenis_dokumen', 'like', '%Perjanjian%');
                    });
            });
        };

        $applyIa = function ($q) {
            $q->where(function ($sub) {
                $sub->where('jenis_dokumen_id', 1)
                    ->orWhereHas('jenisDokumen', function ($jd) {
                        $jd->where('nama_jenis_dokumen', 'like', '%IA%')
                           ->orWhere('nama_jenis_dokumen', 'like', '%Arrangement%')
                           ->orWhere('nama_jenis_dokumen', 'like', '%Implementation%')
                           ->orWhere('nama_jenis_dokumen', 'like', '%Pelaksanaan%');
                    });
            });
        };

        $applyNasional = function ($q) {
            $q->where(function ($sub) {
                $sub->whereJsonContains('skala_kerjasama', 'Nasional')
                    ->orWhere('skala_kerjasama', 'like', '%Nasional%')
                    ->orWhere(function ($fallback) {
                        $fallback->where(function ($s) {
                            $s->whereNull('skala_kerjasama')->orWhere('skala_kerjasama', '')->orWhere('skala_kerjasama', '[]');
                        })->whereHas('mitra', function ($m) {
                            $m->where('lingkup_mitra', 'like', '%Nasional%');
                        });
                    });
            });
        };

        $applyInternasional = function ($q) {
            $q->where(function ($sub) {
                $sub->whereJsonContains('skala_kerjasama', 'Internasional')
                    ->orWhere('skala_kerjasama', 'like', '%Internasional%')
                    ->orWhere(function ($fallback) {
                        $fallback->where(function ($s) {
                            $s->whereNull('skala_kerjasama')->orWhere('skala_kerjasama', '')->orWhere('skala_kerjasama', '[]');
                        })->whereHas('mitra', function ($m) {
                            $m->where('lingkup_mitra', 'like', '%Internasional%');
                        });
                    });
            });
        };

        $applyAktif = function ($q) use ($today) {
            $q->where(function ($sub) use ($today) {
                $sub->whereIn('status_kerjasama', ['Aktif', 'Active'])
                    ->orWhere(function ($d) use ($today) {
                        $d->whereNull('status_kerjasama')
                          ->where(function ($subDate) use ($today) {
                              $subDate->whereNull('tanggal_akhir_berlaku')
                                      ->orWhere('tanggal_akhir_berlaku', '>=', $today);
                          });
                    });
            });
        };

        $applyPerpanjangan = function ($q) {
            $q->whereIn('status_kerjasama', ['Perpanjangan', 'perpanjangan']);
        };

        $applyKedaluwarsa = function ($q) use ($today) {
            $q->where(function ($sub) use ($today) {
                $sub->whereIn('status_kerjasama', ['Kedaluwarsa', 'Kadaluarsa', 'Berakhir', 'Expired', 'Selesai'])
                    ->orWhere(function ($d) use ($today) {
                        $d->whereNotIn('status_kerjasama', ['Aktif', 'Active', 'Perpanjangan', 'Tidak Aktif', 'Nonaktif', 'Inactive'])
                          ->whereNotNull('tanggal_akhir_berlaku')
                          ->where('tanggal_akhir_berlaku', '<', $today);
                    });
            });
        };

        $applyTidakAktif = function ($q) {
            $q->whereIn('status_kerjasama', ['Tidak Aktif', 'Nonaktif', 'Inactive']);
        };

        // 1. Status & Document metrics (Top KPI Cards)
        $totalKerjasamaCount = (clone $baseKerjasama)->count();

        $mouTotalCount = (clone $baseKerjasama)->where($applyMou)->count();
        $moaTotalCount = (clone $baseKerjasama)->where($applyMoa)->count();
        $iaTotalCount  = (clone $baseKerjasama)->where($applyIa)->count();

        $aktifCount        = (clone $baseKerjasama)->where($applyAktif)->count();
        $perpanjanganCount = (clone $baseKerjasama)->where($applyPerpanjangan)->count();
        $kedaluwarsaCount  = (clone $baseKerjasama)->where($applyKedaluwarsa)->count();
        $tidakAktifCount   = (clone $baseKerjasama)->where($applyTidakAktif)->count();

        // Ensure status breakdown accounts for all documents accurately
        $unclassified = $totalKerjasamaCount - ($aktifCount + $perpanjanganCount + $kedaluwarsaCount + $tidakAktifCount);
        if ($unclassified > 0) {
            $kedaluwarsaCount += $unclassified;
        }

        // Skala counts
        $nasionalCount      = (clone $baseKerjasama)->where($applyNasional)->count();
        $internasionalCount = (clone $baseKerjasama)->where($applyInternasional)->count();
        $lokalCount         = (clone $baseKerjasama)->where(function ($q) {
            $q->whereJsonContains('skala_kerjasama', 'Lokal')
              ->orWhere('skala_kerjasama', 'like', '%Lokal%');
        })->count();

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
        $rekapUnitKerjaList = $unitKerjasAll->map(function ($uk) use ($tahunSelected, $applyMou, $applyMoa, $applyIa, $applyNasional, $applyInternasional, $applyAktif) {
            $query = Kerjasama::where(function ($q) use ($uk) {
                $q->where('unit_kerja_id', $uk->id)
                  ->orWhereExists(function ($sub) use ($uk) {
                      $sub->select(DB::raw(1))
                          ->from('kerjasama_unit_kerja')
                          ->whereColumn('kerjasama_unit_kerja.kerjasama_id', 'kerjasamas.id')
                          ->where('kerjasama_unit_kerja.unit_kerja_id', $uk->id);
                  });
            });

            if ($tahunSelected) {
                $query->whereYear('tanggal_waktu_berlaku', $tahunSelected);
            }

            $uk->total_kerjasama     = (clone $query)->count();
            $uk->nasional_count      = (clone $query)->where($applyNasional)->count();
            $uk->internasional_count = (clone $query)->where($applyInternasional)->count();
            $uk->mou_count           = (clone $query)->where($applyMou)->count();
            $uk->moa_count           = (clone $query)->where($applyMoa)->count();
            $uk->ia_count            = (clone $query)->where($applyIa)->count();
            $uk->aktif_count         = (clone $query)->where($applyAktif)->count();

            return $uk;
        });

        // Top 5 Unit Kerja berdasarkan Total Kerjasama (Filtered by Year if selected)
        $top5UnitKerjaList = $rekapUnitKerjaList->filter(function ($uk) {
            return $uk->total_kerjasama > 0;
        })->sortByDesc('total_kerjasama')->take(5)->values();

        if ($top5UnitKerjaList->isEmpty()) {
            $top5UnitKerjaList = $rekapUnitKerjaList->sortByDesc('total_kerjasama')->take(5)->values();
        }

        // Top 5 Unit Kerja berdasarkan MoU, MoA, IA
        $top5MoUList = $rekapUnitKerjaList->sortByDesc('mou_count')->take(5)->values();
        $top5MoAList = $rekapUnitKerjaList->sortByDesc('moa_count')->take(5)->values();
        $top5IAList  = $rekapUnitKerjaList->sortByDesc('ia_count')->take(5)->values();

        $unitKerjaData = $top5UnitKerjaList->map(function ($uk) {
            return [
                'label' => $uk->nama_unit_kerja,
                'value' => $uk->total_kerjasama,
                'mou' => $uk->mou_count,
                'moa' => $uk->moa_count,
                'ia' => $uk->ia_count,
            ];
        });

        // Data Grafik Rekapitulasi Vertical Column (MoU, MoA, IA per Unit Kerja)
        $activeUnitKerjas = $rekapUnitKerjaList->filter(function ($uk) {
            return $uk->total_kerjasama > 0;
        })->values();

        if ($activeUnitKerjas->isEmpty()) {
            $activeUnitKerjas = $rekapUnitKerjaList->take(6)->values();
        }

        $chartDokumenPerUnitKerja = [
            'labels' => $activeUnitKerjas->map(function ($uk) {
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
            $countsPerYear = [];
            foreach ($recentYears as $yr) {
                $c = Kerjasama::where(function ($q) use ($uk) {
                    $q->where('unit_kerja_id', $uk->id)
                      ->orWhereExists(function ($sub) use ($uk) {
                          $sub->select(DB::raw(1))
                              ->from('kerjasama_unit_kerja')
                              ->whereColumn('kerjasama_unit_kerja.kerjasama_id', 'kerjasamas.id')
                              ->where('kerjasama_unit_kerja.unit_kerja_id', $uk->id);
                      });
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
        $tanpaHasilKegiatan = Kegiatan::where(function ($q) {
            $q->whereNull('hasil_pelakasanaan')->orWhere('hasil_pelakasanaan', '');
        })->count();

        // 10. Implementasi Kerjasama
        $denganHasilKerjasama = (clone $baseKerjasama)->whereNotNull('hasil_pelaksanaan')
            ->where('hasil_pelaksanaan', '!=', '')
            ->count();
        $tanpaHasilKerjasama = (clone $baseKerjasama)->where(function ($q) {
            $q->whereNull('hasil_pelaksanaan')->orWhere('hasil_pelaksanaan', '');
        })->count();

        // 12. Rekapitulasi Detail Skala Kerjasama
        $skalaTypes = ['Nasional', 'Internasional', 'Lokal'];
        $rekapSkalaDetail = [];
        foreach ($skalaTypes as $stype) {
            $skalaFilter = function ($q) use ($stype) {
                $q->where(function ($sub) use ($stype) {
                    $sub->whereJsonContains('skala_kerjasama', $stype)
                        ->orWhere('skala_kerjasama', 'like', "%{$stype}%")
                        ->orWhere(function ($fb) use ($stype) {
                            $fb->where(function ($s) {
                                $s->whereNull('skala_kerjasama')->orWhere('skala_kerjasama', '')->orWhere('skala_kerjasama', '[]');
                            })->whereHas('mitra', function ($m) use ($stype) {
                                $m->where('lingkup_mitra', 'like', "%{$stype}%");
                            });
                        });
                });
            };

            $rekapSkalaDetail[$stype] = [
                'total'        => (clone $baseKerjasama)->where($skalaFilter)->count(),
                'aktif'        => (clone $baseKerjasama)->where($skalaFilter)->where($applyAktif)->count(),
                'perpanjangan' => (clone $baseKerjasama)->where($skalaFilter)->where($applyPerpanjangan)->count(),
                'kedaluwarsa'  => (clone $baseKerjasama)->where($skalaFilter)->where($applyKedaluwarsa)->count(),
                'tidak_aktif'  => (clone $baseKerjasama)->where($skalaFilter)->where($applyTidakAktif)->count(),
            ];
        }

        // 13. Build Hierarchical Grouping for Fakultas & Prodi Rekapitulasi
        $facultiesDef = [
            [
                'key' => 'feb',
                'name' => 'Fakultas Ekonomi dan Bisnis',
                'short_name' => 'FEB',
                'match' => 'ekonomi',
                'prodi_matches' => ['manajemen', 'akuntansi'],
            ],
            [
                'key' => 'fst',
                'name' => 'Fakultas Sains dan Teknologi',
                'short_name' => 'FST',
                'match' => 'sains',
                'prodi_matches' => ['informatika', 'sistem informasi', 'industri', 'perkapalan', 'logistik'],
            ],
            [
                'key' => 'fikes',
                'name' => 'Fakultas Ilmu Kesehatan',
                'short_name' => 'FIKes',
                'match' => 'kesehatan',
                'prodi_matches' => ['keselamatan', 'lingkungan', 'masyarakat'],
            ],
        ];

        $hierarchicalRekap = [];

        $universitasUnit = $rekapUnitKerjaList->first(function ($uk) {
            return str_contains(strtolower($uk->nama_unit_kerja), 'universitas');
        });

        if ($universitasUnit) {
            $hierarchicalRekap['uis'] = [
                'type' => 'universitas',
                'key' => 'uis',
                'id' => 'group_uis',
                'nama' => $universitasUnit->nama_unit_kerja,
                'short_nama' => 'UIS',
                'mou_count' => $universitasUnit->mou_count,
                'moa_count' => $universitasUnit->moa_count,
                'ia_count' => $universitasUnit->ia_count,
                'nasional_count' => $universitasUnit->nasional_count,
                'internasional_count' => $universitasUnit->internasional_count,
                'aktif_count' => $universitasUnit->aktif_count,
                'total_kerjasama' => $universitasUnit->total_kerjasama,
                'prodis' => collect([])
            ];
        }

        foreach ($facultiesDef as $fac) {
            $facHeader = $rekapUnitKerjaList->first(function ($uk) use ($fac) {
                return str_contains(strtolower($uk->nama_unit_kerja), 'fakultas') && 
                       str_contains(strtolower($uk->nama_unit_kerja), $fac['match']);
            });

            $childProdis = $rekapUnitKerjaList->filter(function ($uk) use ($fac) {
                $nameLower = strtolower($uk->nama_unit_kerja);
                if (str_contains($nameLower, 'fakultas') || str_contains($nameLower, 'universitas')) {
                    return false;
                }
                foreach ($fac['prodi_matches'] as $pm) {
                    if (str_contains($nameLower, $pm)) {
                        return true;
                    }
                }
                return false;
            })->values();

            // Collect all unit IDs belonging to this faculty (Dean's office + child prodis)
            $facultyUnitIds = $childProdis->pluck('id')->toArray();
            if ($facHeader) {
                $facultyUnitIds[] = $facHeader->id;
            }

            // Calculate DISTINCT kerjasama documents for this faculty
            $facDistinctQuery = Kerjasama::where(function ($q) use ($facultyUnitIds) {
                $q->whereIn('unit_kerja_id', $facultyUnitIds)
                  ->orWhereExists(function ($sub) use ($facultyUnitIds) {
                      $sub->select(DB::raw(1))
                          ->from('kerjasama_unit_kerja')
                          ->whereColumn('kerjasama_unit_kerja.kerjasama_id', 'kerjasamas.id')
                          ->whereIn('kerjasama_unit_kerja.unit_kerja_id', $facultyUnitIds);
                  });
            });

            if ($tahunSelected) {
                $facDistinctQuery->whereYear('tanggal_waktu_berlaku', $tahunSelected);
            }

            $totalMou           = (clone $facDistinctQuery)->where($applyMou)->count();
            $totalMoa           = (clone $facDistinctQuery)->where($applyMoa)->count();
            $totalIa            = (clone $facDistinctQuery)->where($applyIa)->count();
            $totalNasional      = (clone $facDistinctQuery)->where($applyNasional)->count();
            $totalInternasional = (clone $facDistinctQuery)->where($applyInternasional)->count();
            $totalAktif         = (clone $facDistinctQuery)->where($applyAktif)->count();
            $totalKerjasama     = (clone $facDistinctQuery)->count();

            $hierarchicalRekap[$fac['key']] = [
                'type' => 'fakultas',
                'key' => $fac['key'],
                'id' => 'group_' . $fac['key'],
                'nama' => $facHeader ? $facHeader->nama_unit_kerja : $fac['name'],
                'short_nama' => $fac['short_name'],
                'mou_count' => $totalMou,
                'moa_count' => $totalMoa,
                'ia_count' => $totalIa,
                'nasional_count' => $totalNasional,
                'internasional_count' => $totalInternasional,
                'aktif_count' => $totalAktif,
                'total_kerjasama' => $totalKerjasama,
                'prodis' => $childProdis
            ];
        }

        return view('layouts.dashboard.index', compact(
            'aktifCount', 'perpanjanganCount', 'kedaluwarsaCount', 'tidakAktifCount',
            'mouTotalCount', 'moaTotalCount', 'iaTotalCount', 'totalKerjasamaCount',
            'jenisDokumenData', 'ruangLingkupData', 'jenisMitraData', 'bentukKegiatanData',
            'unitKerjaData', 'provinsiMitraData', 'kriteriaMitraData',
            'denganHasilKegiatan', 'tanpaHasilKegiatan', 'denganHasilKerjasama', 'tanpaHasilKerjasama',
            'nasionalCount', 'internasionalCount', 'lokalCount',
            'rekapSkalaDetail', 'rekapUnitKerjaList', 'top5UnitKerjaList',
            'top5MoUList', 'top5MoAList', 'top5IAList',
            'tahunSelected', 'tahunList', 'kerjasamaPerTahunData', 'top5TahunanData',
            'chartDokumenPerUnitKerja', 'hierarchicalRekap'
        ));
    }
}
