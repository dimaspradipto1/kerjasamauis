<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanPihak;
use App\Models\KegiatanPj;
use App\Models\Kerjasama;
use App\Models\UnitKerja;
use App\Models\Mitra;
use App\Models\BentukKegiatan;
use App\Models\SasaranKinerja;
use App\Models\IndikatorSasaran;
use App\DataTables\KegiatanDataTable;
use App\Http\Requests\KegiatanRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KegiatanExport;
use App\Imports\KegiatanImport;

class KegiatanController extends Controller
{
    public function index(KegiatanDataTable $dataTable)
    {
        $mitras     = Mitra::orderBy('nama_mitra', 'asc')->get();
        $kerjasamas = Kerjasama::orderBy('judul_kerjasama', 'asc')->get();
        $unitKerjas = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();

        return $dataTable->render('pages.kegiatan.index', compact('mitras', 'kerjasamas', 'unitKerjas'));
    }

    public function create()
    {
        $kerjasamas      = Kerjasama::orderBy('judul_kerjasama', 'asc')->get();
        $unitKerjas      = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();
        $bentukKegiatans = BentukKegiatan::orderBy('nama_bentuk_kegiatan', 'asc')->get();
        $sasaranKinerjas = SasaranKinerja::orderBy('sasaran_kinerja', 'asc')->get();

        return view('pages.kegiatan.create', compact('kerjasamas', 'unitKerjas', 'bentukKegiatans', 'sasaranKinerjas'));
    }

    public function store(KegiatanRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('dokumen_file')) {
                $data['url_file'] = $request->file('dokumen_file')->store('dokumen_kegiatan', 'public');
            }

            // Save Kegiatan
            $kegiatan = Kegiatan::create($data);

            // Save Pihak 1 & 2
            foreach ($request->pihak as $key => $p) {
                $pihak = KegiatanPihak::create([
                    'kegiatan_id'      => $kegiatan->id,
                    'pihak_ke'         => $key,
                    'jenis_pihak'      => $p['jenis_pihak'],
                    'nomor_surat_izin' => null, // not in form
                    'penanggung_jawab' => $p['penanggung_jawab'],
                ]);

                // Save Penanggung Jawab PJs
                foreach ($p['penanggung_jawab_pjs'] as $pj) {
                    KegiatanPj::create([
                        'kegiatan_pihak_id' => $pihak->id,
                        'nama'              => $pj['nama'],
                        'nip'               => $pj['nip'] ?? '',
                        'jabatan'           => $pj['jabatan'] ?? '',
                        'nomor_hp'          => $pj['nomor_hp'] ?? '',
                        'email'             => $pj['email'] ?? '',
                    ]);
                }
            }
        });

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load([
            'kerjasama', 'unitKerja', 'mitra', 'sasaranKinerja', 'bentukKegiatan', 'indikator',
            'kegiatanPihaks.penanggungJawabs'
        ]);

        $pihak1 = $kegiatan->kegiatanPihaks->where('pihak_ke', '1')->first();
        $pihak2 = $kegiatan->kegiatanPihaks->where('pihak_ke', '2')->first();

        return view('pages.kegiatan.detail', compact('kegiatan', 'pihak1', 'pihak2'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        $kegiatan->load('kegiatanPihaks.penanggungJawabs');

        $kerjasamas      = Kerjasama::orderBy('judul_kerjasama', 'asc')->get();
        $unitKerjas      = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();
        $bentukKegiatans = BentukKegiatan::orderBy('nama_bentuk_kegiatan', 'asc')->get();
        $sasaranKinerjas = SasaranKinerja::orderBy('sasaran_kinerja', 'asc')->get();

        // Get indicators for current sasaran
        $indikatorSasarans = IndikatorSasaran::where('sasaran_kinerja_id', $kegiatan->sasaran_kinerja_id)
            ->orderBy('indikator_sasaran', 'asc')
            ->get();

        $pihak1 = $kegiatan->kegiatanPihaks->where('pihak_ke', '1')->first();
        $pihak2 = $kegiatan->kegiatanPihaks->where('pihak_ke', '2')->first();

        return view('pages.kegiatan.edit', compact('kegiatan', 'kerjasamas', 'unitKerjas', 'bentukKegiatans', 'sasaranKinerjas', 'indikatorSasarans', 'pihak1', 'pihak2'));
    }

    public function update(KegiatanRequest $request, Kegiatan $kegiatan)
    {
        DB::transaction(function () use ($request, $kegiatan) {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('dokumen_file')) {
                if ($kegiatan->url_file) {
                    Storage::disk('public')->delete($kegiatan->url_file);
                }
                $data['url_file'] = $request->file('dokumen_file')->store('dokumen_kegiatan', 'public');
            }

            // Update Kegiatan
            $kegiatan->update($data);

            // Recreate Pihak and Penanggung Jawab
            foreach ($kegiatan->kegiatanPihaks as $p) {
                $p->penanggungJawabs()->delete();
            }
            $kegiatan->kegiatanPihaks()->delete();

            // Save Pihak & Penanggung Jawab PJs
            foreach ($request->pihak as $key => $p) {
                $pihak = KegiatanPihak::create([
                    'kegiatan_id'      => $kegiatan->id,
                    'pihak_ke'         => $key,
                    'jenis_pihak'      => $p['jenis_pihak'],
                    'nomor_surat_izin' => null,
                    'penanggung_jawab' => $p['penanggung_jawab'],
                ]);

                foreach ($p['penanggung_jawab_pjs'] as $pj) {
                    KegiatanPj::create([
                        'kegiatan_pihak_id' => $pihak->id,
                        'nama'              => $pj['nama'],
                        'nip'               => $pj['nip'] ?? '',
                        'jabatan'           => $pj['jabatan'] ?? '',
                        'nomor_hp'          => $pj['nomor_hp'] ?? '',
                        'email'             => $pj['email'] ?? '',
                    ]);
                }
            }
        });

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        DB::transaction(function () use ($kegiatan) {
            if ($kegiatan->url_file) {
                Storage::disk('public')->delete($kegiatan->url_file);
            }
            foreach ($kegiatan->kegiatanPihaks as $p) {
                $p->penanggungJawabs()->delete();
            }
            $kegiatan->kegiatanPihaks()->delete();
            $kegiatan->delete();
        });

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    // ══ AJAX Endpoints ══
    public function getIndikatorBySasaran($sasaranKinerjaId): JsonResponse
    {
        $indicators = IndikatorSasaran::where('sasaran_kinerja_id', $sasaranKinerjaId)
            ->orderBy('indikator_sasaran', 'asc')
            ->get();
        return response()->json($indicators);
    }

    public function getMitraByKerjasama($kerjasamaId): JsonResponse
    {
        $kerjasama = Kerjasama::with('mitra')->find($kerjasamaId);
        if ($kerjasama && $kerjasama->mitra) {
            return response()->json([
                'success' => true,
                'id'      => $kerjasama->mitra->id,
                'nama'    => $kerjasama->mitra->nama_mitra,
            ]);
        }
        return response()->json(['success' => false]);
    }

    // ══ Excel Import / Export ══
    public function export()
    {
        return Excel::download(new KegiatanExport(), 'data-kegiatan.xlsx');
    }

    public function import(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KegiatanImport(), $request->file('file'));

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data Kegiatan berhasil di-import!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new class () implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array
            {
                return [
                    'Induk Kerjasama',
                    'Unit Kerja',
                    'Mitra',
                    'Sasaran Kinerja',
                    'Bentuk Kegiatan',
                    'Indikator Sasaran',
                    'Nomor Dokumen Kegiatan',
                    'Nomor Dokumen Mitra',
                    'Judul Kegiatan',
                    'Tanggal Awal Kegiatan',
                    'Tanggal Akhir Kegiatan',
                    'Ruang Lingkup',
                    'Hasil Pelaksanaan',
                    'Nilai Kontrak',
                    'Link Dokumen Kegiatan',
                ];
            }
        }, 'format-import-kegiatan.xlsx');
    }
}
