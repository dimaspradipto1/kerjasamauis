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

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Status metrics
        $aktifCount = Kerjasama::where('status_kerjasama', 'Aktif')->count();
        $perpanjanganCount = Kerjasama::where('status_kerjasama', 'Perpanjangan')->count();
        $kedaluwarsaCount = Kerjasama::where('status_kerjasama', 'Kedaluwarsa')->count();
        $tidakAktifCount = Kerjasama::where('status_kerjasama', 'Tidak Aktif')->count();

        // 2. Jenis Dokumen Chart data
        $jenisDokumenData = Kerjasama::join('jenis_dokumens', 'kerjasamas.jenis_dokumen_id', '=', 'jenis_dokumens.id')
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

        // 6. Top 5 Unit Kerja
        $unitKerjaData = Kerjasama::join('unit_kerjas', 'kerjasamas.unit_kerja_id', '=', 'unit_kerjas.id')
            ->selectRaw('unit_kerjas.nama_unit_kerja as label, count(*) as value')
            ->groupBy('unit_kerjas.id', 'unit_kerjas.nama_unit_kerja')
            ->orderByDesc('value')
            ->limit(5)
            ->get();

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
        $denganHasilKerjasama = Kerjasama::whereNotNull('hasil_pelaksanaan')
            ->where('hasil_pelaksanaan', '!=', '')
            ->count();
        $tanpaHasilKerjasama = Kerjasama::where(function($q) {
            $q->whereNull('hasil_pelaksanaan')->orWhere('hasil_pelaksanaan', '');
        })->count();

        return view('layouts.dashboard.index', compact(
            'aktifCount', 'perpanjanganCount', 'kedaluwarsaCount', 'tidakAktifCount',
            'jenisDokumenData', 'ruangLingkupData', 'jenisMitraData', 'bentukKegiatanData',
            'unitKerjaData', 'provinsiMitraData', 'kriteriaMitraData',
            'denganHasilKegiatan', 'tanpaHasilKegiatan', 'denganHasilKerjasama', 'tanpaHasilKerjasama'
        ));
    }
}
