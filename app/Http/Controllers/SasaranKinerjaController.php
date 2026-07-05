<?php

namespace App\Http\Controllers;

use App\Models\SasaranKinerja;
use App\Models\IndikatorSasaran;
use App\DataTables\SasaranKinerjaDataTable;
use App\Http\Requests\SasaranKinerjaStoreRequest;
use App\Http\Requests\SasaranKinerjaUpdateRequest;
use Illuminate\Support\Facades\DB;

class SasaranKinerjaController extends Controller
{
    public function index(SasaranKinerjaDataTable $dataTable)
    {
        return $dataTable->render('pages.sasarankinerja.index');
    }

    public function create()
    {
        return view('pages.sasarankinerja.create');
    }

    public function store(SasaranKinerjaStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Simpan sasaran kinerja
            $sasaran = SasaranKinerja::create([
                'sasaran_kinerja' => $request->sasaran_kinerja,
                'keterangan'      => $request->keterangan,
                'level'           => $request->level,
            ]);

            // Simpan semua indikator sasaran
            foreach ($request->indikator as $ind) {
                if (!empty($ind['indikator_sasaran'])) {
                    IndikatorSasaran::create([
                        'sasaran_kinerja_id' => $sasaran->id,
                        'indikator_sasaran'  => $ind['indikator_sasaran'],
                        'keterangan'         => $ind['keterangan'] ?? null,
                        'volume'             => $ind['volume'] ?? null,
                        'satuan'             => $ind['satuan'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('sasaran-kinerja.index')
            ->with('success', 'Sasaran kinerja beserta indikator berhasil ditambahkan.');
    }

    public function show($id)
    {
        $sasaran = SasaranKinerja::with('indikatorSasarans')->findOrFail($id);
        return view('pages.sasarankinerja.detail', compact('sasaran'));
    }

    public function edit($id)
    {
        $sasaran = SasaranKinerja::with('indikatorSasarans')->findOrFail($id);
        return view('pages.sasarankinerja.edit', compact('sasaran'));
    }

    public function update(SasaranKinerjaUpdateRequest $request, $id)
    {
        $sasaran = SasaranKinerja::findOrFail($id);

        DB::transaction(function () use ($request, $sasaran) {
            // Update sasaran kinerja
            $sasaran->update([
                'sasaran_kinerja' => $request->sasaran_kinerja,
                'keterangan'      => $request->keterangan,
                'level'           => $request->level,
            ]);

            // Kumpulkan ID indikator yang dikirim (untuk menentukan mana yang dihapus)
            $submittedIds = collect($request->indikator ?? [])
                ->pluck('id')
                ->filter()
                ->toArray();

            // Hapus indikator yang tidak ada di submitted form
            $sasaran->indikatorSasarans()
                ->whereNotIn('id', $submittedIds)
                ->delete();

            // Upsert: update existing, insert new
            foreach ($request->indikator ?? [] as $ind) {
                if (empty($ind['indikator_sasaran'])) continue;

                $data = [
                    'sasaran_kinerja_id' => $sasaran->id,
                    'indikator_sasaran'  => $ind['indikator_sasaran'],
                    'keterangan'         => $ind['keterangan'] ?? null,
                    'volume'             => $ind['volume'] ?? null,
                    'satuan'             => $ind['satuan'] ?? null,
                ];

                if (!empty($ind['id'])) {
                    IndikatorSasaran::where('id', $ind['id'])->update($data);
                } else {
                    IndikatorSasaran::create($data);
                }
            }
        });

        return redirect()->route('sasaran-kinerja.index')
            ->with('success', 'Sasaran kinerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sasaran = SasaranKinerja::findOrFail($id);
        $sasaran->delete(); // cascades to indikator_sasarans via DB constraint

        return redirect()->route('sasaran-kinerja.index')
            ->with('success', 'Sasaran kinerja berhasil dihapus.');
    }
}
