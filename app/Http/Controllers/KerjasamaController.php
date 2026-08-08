<?php

namespace App\Http\Controllers;

use App\Models\Kerjasama;
use App\Models\KerjasamaPihak;
use App\Models\KerjasamaPenanggungJawab;
use App\Models\JenisDokumen;
use App\Models\Mitra;
use App\Models\UnitKerja;
use App\Models\KriteriaMitra;
use App\Models\SumberDana;
use App\Models\Staff;
use App\DataTables\KerjasamaDataTable;
use App\Http\Requests\KerjasamaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KerjasamaExport;
use App\Imports\KerjasamaImport;

class KerjasamaController extends Controller
{
    public function index(KerjasamaDataTable $dataTable)
    {
        $mitras = Mitra::orderBy('nama_mitra', 'asc')->get();

        $moaCount = Kerjasama::whereHas('jenisDokumen', function($q){
            $q->where('nama_jenis_dokumen', 'like', '%MoA%')
              ->orWhere('nama_jenis_dokumen', 'like', '%Agreement%');
        })->count();

        $mouCount = Kerjasama::whereHas('jenisDokumen', function($q){
            $q->where('nama_jenis_dokumen', 'like', '%MoU%')
              ->orWhere('nama_jenis_dokumen', 'like', '%Understanding%');
        })->count();

        $iaCount = Kerjasama::whereHas('jenisDokumen', function($q){
            $q->where('nama_jenis_dokumen', 'like', '%IA%')
              ->orWhere('nama_jenis_dokumen', 'like', '%Arrangement%');
        })->count();

        $totalKerjasamaCount = Kerjasama::count();

        return $dataTable->render('pages.kerjasama.index', compact('mitras', 'moaCount', 'mouCount', 'iaCount', 'totalKerjasamaCount'));
    }

    public function create()
    {
        $jenisDokumens = JenisDokumen::orderBy('nama_jenis_dokumen', 'asc')->get();
        $mitras        = Mitra::orderBy('nama_mitra', 'asc')->get();
        $unitKerjas    = UnitKerja::orderBy('id', 'asc')->get();
        $sumberDanas   = SumberDana::orderBy('nama_sumber_dana', 'asc')->get();
        $staffList     = Staff::where('status', 'Aktif')->orderBy('nama_staff', 'asc')->get();
        $kriteriaMitras = KriteriaMitra::orderBy('kriteria_mitra', 'asc')->get();

        return view('pages.kerjasama.create', compact('jenisDokumens', 'mitras', 'unitKerjas', 'sumberDanas', 'staffList', 'kriteriaMitras'));
    }

    public function store(KerjasamaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            // Handle custom sumber_dana_id if user typed a custom value
            if (!is_numeric($data['sumber_dana_id'])) {
                $sd = SumberDana::firstOrCreate(['nama_sumber_dana' => trim($data['sumber_dana_id'])]);
                $data['sumber_dana_id'] = $sd->id;
            }

            // Set primary unit_kerja_id (first item) for backward compatibility
            $data['unit_kerja_id'] = $request->unit_kerja_ids[0] ?? null;

            // Handle file upload (multiple or single)
            $uploadedFiles = [];
            if ($request->hasFile('dokumen_files')) {
                foreach ($request->file('dokumen_files') as $file) {
                    $uploadedFiles[] = $this->storeUploadedFile($file, 'dokumen');
                }
            } elseif ($request->hasFile('dokumen_file')) {
                $uploadedFiles[] = $this->storeUploadedFile($request->file('dokumen_file'), 'dokumen');
            }
            $data['url_file'] = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

            $data['status'] = 'active'; // default status

            // Save Kerjasama
            $kerjasama = Kerjasama::create($data);

            // Sync multi-select unit kerjas
            if (!empty($request->unit_kerja_ids)) {
                $kerjasama->unitKerjas()->sync($request->unit_kerja_ids);
            }

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
            'jenisDokumen', 'mitra', 'unitKerja', 'unitKerjas', 'sumberDana',
            'kerjasamaPihaks.penanggungJawabs'
        ]);

        // Mapping Pihak 1 and Pihak 2 for easy blade rendering
        $pihak1 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '1')->first();
        $pihak2 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '2')->first();

        return view('pages.kerjasama.detail', compact('kerjasama', 'pihak1', 'pihak2'));
    }

    public function edit(Kerjasama $kerjasama)
    {
        $kerjasama->load(['unitKerjas', 'kerjasamaPihaks.penanggungJawabs']);
        
        $jenisDokumens = JenisDokumen::orderBy('nama_jenis_dokumen', 'asc')->get();
        $mitras        = Mitra::orderBy('nama_mitra', 'asc')->get();
        $unitKerjas    = UnitKerja::orderBy('id', 'asc')->get();
        $sumberDanas   = SumberDana::orderBy('nama_sumber_dana', 'asc')->get();
        $staffList     = Staff::where('status', 'Aktif')->orderBy('nama_staff', 'asc')->get();
        $kriteriaMitras = KriteriaMitra::orderBy('kriteria_mitra', 'asc')->get();

        $pihak1 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '1')->first();
        $pihak2 = $kerjasama->kerjasamaPihaks->where('pihak_ke', '2')->first();

        return view('pages.kerjasama.edit', compact('kerjasama', 'jenisDokumens', 'mitras', 'unitKerjas', 'sumberDanas', 'pihak1', 'pihak2', 'staffList', 'kriteriaMitras'));
    }

    public function update(KerjasamaRequest $request, Kerjasama $kerjasama)
    {
        DB::transaction(function () use ($request, $kerjasama) {
            $data = $request->validated();

            // Handle custom sumber_dana_id if user typed a custom value
            if (!is_numeric($data['sumber_dana_id'])) {
                $sd = SumberDana::firstOrCreate(['nama_sumber_dana' => trim($data['sumber_dana_id'])]);
                $data['sumber_dana_id'] = $sd->id;
            }

            // Set primary unit_kerja_id (first item) for backward compatibility
            $data['unit_kerja_id'] = $request->unit_kerja_ids[0] ?? null;

            // Handle file upload & existing files
            $existingFiles = $request->input('existing_files', []);
            if (!is_array($existingFiles)) {
                $existingFiles = [];
            }

            // Delete removed files from storage
            $currentFiles = $kerjasama->url_file;
            foreach ($currentFiles as $oldFile) {
                if (!in_array($oldFile, $existingFiles)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }

            $uploadedFiles = $existingFiles;
            if ($request->hasFile('dokumen_files')) {
                foreach ($request->file('dokumen_files') as $file) {
                    $uploadedFiles[] = $this->storeUploadedFile($file, 'dokumen');
                }
            } elseif ($request->hasFile('dokumen_file')) {
                $uploadedFiles[] = $this->storeUploadedFile($request->file('dokumen_file'), 'dokumen');
            }
            $data['url_file'] = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

            // Update Kerjasama
            $kerjasama->update($data);

            // Sync multi-select unit kerjas
            if (!empty($request->unit_kerja_ids)) {
                $kerjasama->unitKerjas()->sync($request->unit_kerja_ids);
            }

            // Recreate Pihak and Penanggung Jawab
            // 1. Delete existing
            foreach ($kerjasama->kerjasamaPihaks as $p) {
                $p->penanggungJawabs()->delete();
            }
            $kerjasama->kerjasamaPihaks()->delete();

            // 2. Create new
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
            $files = $kerjasama->url_file;
            if (!empty($files)) {
                foreach ($files as $filePath) {
                    Storage::disk('public')->delete($filePath);
                }
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

    public function bulkDelete(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.'], 400);
            }
            return redirect()->route('kerjasama.index')->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        $count = count($ids);
        DB::transaction(function () use ($ids) {
            $kerjasamas = Kerjasama::whereIn('id', $ids)->get();
            foreach ($kerjasamas as $kerjasama) {
                $files = $kerjasama->url_file;
                if (!empty($files)) {
                    foreach ($files as $filePath) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
                foreach ($kerjasama->kerjasamaPihaks as $p) {
                    $p->penanggungJawabs()->delete();
                }
                $kerjasama->kerjasamaPihaks()->delete();
                $kerjasama->delete();
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $count . ' data kerjasama berhasil dihapus.']);
        }

        return redirect()->route('kerjasama.index')->with('success', $count . ' data kerjasama berhasil dihapus.');
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

    private function storeUploadedFile($file, $folder)
    {
        $origName = $file->getClientOriginalName();
        $filename = $origName;
        if (Storage::disk('public')->exists($folder . '/' . $filename)) {
            $nameOnly = pathinfo($origName, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $filename = $nameOnly . '_' . time() . ($ext ? '.' . $ext : '');
        }
        return $file->storeAs($folder, $filename, 'public');
    }
}
