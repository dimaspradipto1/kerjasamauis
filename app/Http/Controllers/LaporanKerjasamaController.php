<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kerjasama;
use App\Models\JenisDokumen;
use App\Models\UnitKerja;

class LaporanKerjasamaController extends Controller
{
    public function index(Request $request)
    {
        $jenisDokumens = JenisDokumen::orderBy('nama_jenis_dokumen', 'asc')->get();
        $unitKerjas    = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();
        
        // Status options matching specification
        $statuses = [
            'Aktif',
            'Draft',
            'Kedaluwarsa',
            'Perpanjangan',
            'Tidak Aktif'
        ];

        $results = null;

        // If form submitted to "Tampilkan" (inline preview)
        if ($request->has('tampilkan')) {
            $request->validate([
                'tanggal_awal'  => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            ]);

            $query = Kerjasama::with(['jenisDokumen', 'mitra', 'unitKerja'])
                ->whereBetween('tanggal_waktu_berlaku', [$request->tanggal_awal, $request->tanggal_akhir]);

            if ($request->jenis_dokumen_id) {
                $query->where('jenis_dokumen_id', $request->jenis_dokumen_id);
            }

            if ($request->unit_kerja_id) {
                $query->where('unit_kerja_id', $request->unit_kerja_id);
            }

            if ($request->status_kerjasama) {
                $query->where('status_kerjasama', $request->status_kerjasama);
            }

            $results = $query->orderBy('tanggal_waktu_berlaku', 'desc')->get();
        }

        $jenisDokumenNama = 'Semua Jenis Dokumen Kerjasama';
        if ($request->jenis_dokumen_id) {
            $jd = \App\Models\JenisDokumen::find($request->jenis_dokumen_id);
            if ($jd) {
                $jenisDokumenNama = $jd->nama_jenis_dokumen;
            }
        }

        $unitKerjaNama = 'Semua Unit Kerja';
        if ($request->unit_kerja_id) {
            $uk = \App\Models\UnitKerja::find($request->unit_kerja_id);
            if ($uk) {
                $unitKerjaNama = $uk->nama_unit_kerja;
            }
        }

        return view('pages.laporan.index', compact('jenisDokumens', 'unitKerjas', 'statuses', 'results', 'jenisDokumenNama', 'unitKerjaNama'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $query = Kerjasama::with(['jenisDokumen', 'mitra', 'unitKerja'])
            ->whereBetween('tanggal_waktu_berlaku', [$request->tanggal_awal, $request->tanggal_akhir]);

        if ($request->jenis_dokumen_id) {
            $query->where('jenis_dokumen_id', $request->jenis_dokumen_id);
        }

        if ($request->unit_kerja_id) {
            $query->where('unit_kerja_id', $request->unit_kerja_id);
        }

        if ($request->status_kerjasama) {
            $query->where('status_kerjasama', $request->status_kerjasama);
        }

        $results = $query->orderBy('tanggal_waktu_berlaku', 'desc')->get();
        $useKop = $request->has('gunakan_kop');

        $jenisDokumenNama = 'Semua Jenis Dokumen Kerjasama';
        if ($request->jenis_dokumen_id) {
            $jd = \App\Models\JenisDokumen::find($request->jenis_dokumen_id);
            if ($jd) {
                $jenisDokumenNama = $jd->nama_jenis_dokumen;
            }
        }

        $unitKerjaNama = 'Semua Unit Kerja';
        if ($request->unit_kerja_id) {
            $uk = \App\Models\UnitKerja::find($request->unit_kerja_id);
            if ($uk) {
                $unitKerjaNama = $uk->nama_unit_kerja;
            }
        }

        return view('pages.laporan.cetak', compact('results', 'useKop', 'request', 'jenisDokumenNama', 'unitKerjaNama'));
    }
}
