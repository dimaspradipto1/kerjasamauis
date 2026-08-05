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
                'tanggal_awal'  => 'nullable|date',
                'tanggal_akhir' => 'nullable|date',
            ]);

            $results = $this->buildReportQuery($request)->get();
        }

        $jenisDokumenNama = 'Semua Jenis Dokumen Kerjasama';
        if ($request->jenis_dokumen_id) {
            $jd = \App\Models\JenisDokumen::find(is_array($request->jenis_dokumen_id) ? $request->jenis_dokumen_id[0] : $request->jenis_dokumen_id);
            if ($jd) {
                $jenisDokumenNama = $jd->nama_jenis_dokumen;
            }
        }

        $unitKerjaNama = 'Semua Unit Kerja';
        if ($request->unit_kerja_id) {
            $uk = \App\Models\UnitKerja::find(is_array($request->unit_kerja_id) ? $request->unit_kerja_id[0] : $request->unit_kerja_id);
            if ($uk) {
                $unitKerjaNama = $uk->nama_unit_kerja;
            }
        }

        return view('pages.laporan.index', compact('jenisDokumens', 'unitKerjas', 'statuses', 'results', 'jenisDokumenNama', 'unitKerjaNama'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
        ]);

        $results = $this->buildReportQuery($request)->get();
        $useKop = $request->has('gunakan_kop');

        $jenisDokumenNama = 'Semua Jenis Dokumen Kerjasama';
        if ($request->jenis_dokumen_id) {
            $jd = \App\Models\JenisDokumen::find(is_array($request->jenis_dokumen_id) ? $request->jenis_dokumen_id[0] : $request->jenis_dokumen_id);
            if ($jd) {
                $jenisDokumenNama = $jd->nama_jenis_dokumen;
            }
        }

        $unitKerjaNama = 'Semua Unit Kerja';
        if ($request->unit_kerja_id) {
            $uk = \App\Models\UnitKerja::find(is_array($request->unit_kerja_id) ? $request->unit_kerja_id[0] : $request->unit_kerja_id);
            if ($uk) {
                $unitKerjaNama = $uk->nama_unit_kerja;
            }
        }

        return view('pages.laporan.cetak', compact('results', 'useKop', 'request', 'jenisDokumenNama', 'unitKerjaNama'));
    }

    private function buildReportQuery(Request $request)
    {
        $query = Kerjasama::with(['jenisDokumen', 'mitra', 'unitKerja', 'unitKerjas']);

        if ($request->filled('tanggal_awal') || $request->filled('tanggal_akhir')) {
            $tglAwal = $request->tanggal_awal ?: '1970-01-01';
            $tglAkhir = $request->tanggal_akhir ?: '2099-12-31';

            // Ensure tglAwal <= tglAkhir
            if ($tglAwal > $tglAkhir) {
                $temp = $tglAwal;
                $tglAwal = $tglAkhir;
                $tglAkhir = $temp;
            }

            $query->where(function ($q) use ($tglAwal, $tglAkhir) {
                $q->whereBetween('tanggal_waktu_berlaku', [$tglAwal, $tglAkhir])
                  ->orWhereBetween('tanggal_akhir_berlaku', [$tglAwal, $tglAkhir])
                  ->orWhere(function ($sub) use ($tglAwal, $tglAkhir) {
                      $sub->where('tanggal_waktu_berlaku', '<=', $tglAwal)
                          ->where('tanggal_akhir_berlaku', '>=', $tglAkhir);
                  });
            });
        }

        if ($request->filled('jenis_dokumen_id')) {
            $jdIds = (array) $request->jenis_dokumen_id;
            $query->whereIn('jenis_dokumen_id', array_filter($jdIds));
        }

        if ($request->filled('unit_kerja_id')) {
            $rawUkIds = array_filter((array) $request->unit_kerja_id);
            if (!empty($rawUkIds)) {
                $allUkIds = [];
                $selectedUnits = UnitKerja::whereIn('id', $rawUkIds)->get();
                foreach ($selectedUnits as $sUnit) {
                    $allUkIds[] = $sUnit->id;
                    $cleanName = preg_replace('/^(S1|S2|S3|D3|D4)\s*-\s*/i', '', trim($sUnit->nama_unit_kerja));
                    if (!empty($cleanName)) {
                        $relatedIds = UnitKerja::where('nama_unit_kerja', 'like', "%{$cleanName}%")->pluck('id')->toArray();
                        $allUkIds = array_merge($allUkIds, $relatedIds);
                    }
                }
                $ukIds = array_unique($allUkIds);

                $query->where(function ($q) use ($ukIds) {
                    $q->whereIn('unit_kerja_id', $ukIds)
                      ->orWhereHas('unitKerjas', function ($sub) use ($ukIds) {
                          $sub->whereIn('unit_kerjas.id', $ukIds);
                      });
                });
            }
        }

        if ($request->filled('status_kerjasama')) {
            $statuses = (array) $request->status_kerjasama;
            $query->whereIn('status_kerjasama', array_filter($statuses));
        }

        return $query->orderBy('tanggal_waktu_berlaku', 'desc');
    }
}
