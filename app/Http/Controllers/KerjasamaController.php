<?php

namespace App\Http\Controllers;

use App\Models\Kerjasama;
use App\Models\KerjasamaPihak;
use App\Models\KerjasamaPenanggungJawab;
use App\Models\JenisDokumen;
use App\Models\Mitra;
use App\Models\UnitKerja;
use App\Models\SumberDana;
use App\DataTables\KerjasamaDataTable;
use App\Http\Requests\KerjasamaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KerjasamaExport;
use App\Imports\KerjasamaImport;

class KerjasamaController extends Controller
{
    public function index(KerjasamaDataTable $dataTable)
    {
        $mitras = Mitra::orderBy('nama_mitra', 'asc')->get();
        return $dataTable->render('pages.kerjasama.index', compact('mitras'));
    }

    public function create()
    {
        $jenisDokumens = JenisDokumen::orderBy('nama_jenis_dokumen', 'asc')->get();
        $mitras        = Mitra::orderBy('nama_mitra', 'asc')->get();
        $unitKerjas    = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();
        $sumberDanas   = SumberDana::orderBy('nama_sumber_dana', 'asc')->get();

        return view('pages.kerjasama.create', compact('jenisDokumens', 'mitras', 'unitKerjas', 'sumberDanas'));
    }

    public function store(KerjasamaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('dokumen_file')) {
                $data['url_file'] = $request->file('dokumen_file')->store('dokumen', 'public');
            }

            $data['status'] = 'active'; // default status

            // Save Kerjasama
            $kerjasama = Kerjasama::create($data);

            // Save Pihak 1 & 2
            foreach ($request->pihak as $key => $p) {
                $pihak = KerjasamaPihak::create([
                    'kerjasama_id' => $kerjasama->id,
                    'pihak_ke'     => $key,
                    'jenis_pihak'  => $p['jenis_pihak'],
                    'alamat'       => $p['alamat'] ?? null,
                ]);

                // Save Penanggung Jawab
                foreach ($p['penanggung_jawab'] as $pj) {
                    KerjasamaPenanggungJawab::create([
                        'kerjasama_pihak_id' => $pihak->id,
                        'nama'               => $pj['nama'],
                        'nip'                => $pj['nip'] ?? '',
                        'jabatan'            => $pj['jabatan'] ?? '',
                        'nomor_hp'           => $pj['nomor_hp'] ?? '',
                        'email'              => $pj['email'] ?? '',
                    ]);
                }
            }
        });

        return redirect()->route('kerjasama.index')
            ->with('success', 'Kerjasama berhasil ditambahkan.');
    }

    public function show(Kerjasama $kerjasama)
    {
        $kerjasama->load([
            'jenisDokumen', 'mitra', 'unitKerja', 'sumberDana',
            'kerjasamaPihaks.penanggungJawabs'
        ]);

        // Mapping Pihak 1 and Pihak 2 for easy blade rendering
        $pihak1 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '1')->first();
        $pihak2 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '2')->first();

        return view('pages.kerjasama.detail', compact('kerjasama', 'pihak1', 'pihak2'));
    }

    public function edit(Kerjasama $kerjasama)
    {
        $kerjasama->load('kerjasamaPihaks.penanggungJawabs');
        
        $jenisDokumens = JenisDokumen::orderBy('nama_jenis_dokumen', 'asc')->get();
        $mitras        = Mitra::orderBy('nama_mitra', 'asc')->get();
        $unitKerjas    = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();
        $sumberDanas   = SumberDana::orderBy('nama_sumber_dana', 'asc')->get();

        $pihak1 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '1')->first();
        $pihak2 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '2')->first();

        return view('pages.kerjasama.edit', compact('kerjasama', 'jenisDokumens', 'mitras', 'unitKerjas', 'sumberDanas', 'pihak1', 'pihak2'));
    }

    public function update(KerjasamaRequest $request, Kerjasama $kerjasama)
    {
        DB::transaction(function () use ($request, $kerjasama) {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('dokumen_file')) {
                // Delete old file if exists
                if ($kerjasama->url_file) {
                    Storage::disk('public')->delete($kerjasama->url_file);
                }
                $data['url_file'] = $request->file('dokumen_file')->store('dokumen', 'public');
            }

            // Update Kerjasama
            $kerjasama->update($data);

            // Recreate Pihak and Penanggung Jawab
            // 1. Delete existing
            foreach ($kerjasama->kerjasamaPihaks as $p) {
                $p->penanggungJawabs()->delete();
            }
            $kerjasama->kerjasamaPihaks()->delete();

            // 2. Save new Pihak & Penanggung Jawab
            foreach ($request->pihak as $key => $p) {
                $pihak = KerjasamaPihak::create([
                    'kerjasama_id' => $kerjasama->id,
                    'pihak_ke'     => $key,
                    'jenis_pihak'  => $p['jenis_pihak'],
                    'alamat'       => $p['alamat'] ?? null,
                ]);

                // Save Penanggung Jawab
                foreach ($p['penanggung_jawab'] as $pj) {
                    KerjasamaPenanggungJawab::create([
                        'kerjasama_pihak_id' => $pihak->id,
                        'nama'               => $pj['nama'],
                        'nip'                => $pj['nip'] ?? '',
                        'jabatan'            => $pj['jabatan'] ?? '',
                        'nomor_hp'           => $pj['nomor_hp'] ?? '',
                        'email'              => $pj['email'] ?? '',
                    ]);
                }
            }
        });

        return redirect()->route('kerjasama.index')
            ->with('success', 'Kerjasama berhasil diperbarui.');
    }

    public function destroy(Kerjasama $kerjasama)
    {
        DB::transaction(function () use ($kerjasama) {
            if ($kerjasama->url_file) {
                Storage::disk('public')->delete($kerjasama->url_file);
            }
            // Cascade deletion is handled by DB foreign keys, but we can do it explicitly as well
            foreach ($kerjasama->kerjasamaPihaks as $p) {
                $p->penanggungJawabs()->delete();
            }
            $kerjasama->kerjasamaPihaks()->delete();
            $kerjasama->delete();
        });

        return redirect()->route('kerjasama.index')
            ->with('success', 'Kerjasama berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new KerjasamaExport(), 'data-kerjasama.xlsx');
    }

    public function import(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KerjasamaImport(), $request->file('file'));

        return redirect()->route('kerjasama.index')
            ->with('success', 'Data Kerjasama berhasil di-import!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new class () implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array
            {
                return [
                    'Nomor Dokumen Kerjasama',
                    'Nomor Dokumen Mitra',
                    'Jenis Dokumen',
                    'Unit Kerja',
                    'Mitra',
                    'Judul Kerjasama',
                    'Deskripsi Kerjasama',
                    'Sumber Dana',
                    'Anggaran',
                    'Tanggal Awal Berlaku',
                    'Tanggal Akhir Berlaku',
                    'Status Kerjasama',
                ];
            }
        }, 'format-import-kerjasama.xlsx');
    }
}
