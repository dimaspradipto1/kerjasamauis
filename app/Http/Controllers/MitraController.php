<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\KriteriaMitra;
use App\Models\KontakMitra;
use App\DataTables\MitraDataTable;
use App\Http\Requests\MitraRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MitraExport;

class MitraController extends Controller
{
    public function index(MitraDataTable $dataTable)
    {
        return $dataTable->render('pages.mitra.index');
    }

    public function create()
    {
        $kriteriaMitra = KriteriaMitra::orderBy('kriteria_mitra', 'asc')->get();
        return view('pages.mitra.create', compact('kriteriaMitra'));
    }

    public function store(MitraRequest $request)
    {
        DB::transaction(function () use ($request) {
            $mitra = Mitra::create($request->validated());

            foreach ($request->kontak as $k) {
                KontakMitra::create([
                    'mitra_id'        => $mitra->id,
                    'nama_kontak'     => $k['nama_kontak'],
                    'jabatan'         => $k['jabatan'],
                    'nomor_handphone' => $k['nomor_handphone'],
                    'email'           => $k['email'],
                ]);
            }
        });

        return redirect()->route('mitra.index')
            ->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function show(Mitra $mitra)
    {
        $mitra->load(['kriteriaMitra', 'kontakMitras']);
        return view('pages.mitra.detail', compact('mitra'));
    }

    public function edit(Mitra $mitra)
    {
        $mitra->load('kontakMitras');
        $kriteriaMitra = KriteriaMitra::orderBy('kriteria_mitra', 'asc')->get();
        return view('pages.mitra.edit', compact('mitra', 'kriteriaMitra'));
    }

    public function update(MitraRequest $request, Mitra $mitra)
    {
        DB::transaction(function () use ($request, $mitra) {
            $mitra->update($request->validated());

            $submittedIds = collect($request->kontak ?? [])
                ->pluck('id')
                ->filter()
                ->toArray();

            // Hapus kontak yang tidak ada di form yang disubmit
            $mitra->kontakMitras()
                ->whereNotIn('id', $submittedIds)
                ->delete();

            // Upsert kontak
            foreach ($request->kontak ?? [] as $k) {
                $data = [
                    'mitra_id'        => $mitra->id,
                    'nama_kontak'     => $k['nama_kontak'],
                    'jabatan'         => $k['jabatan'],
                    'nomor_handphone' => $k['nomor_handphone'],
                    'email'           => $k['email'],
                ];

                if (!empty($k['id'])) {
                    KontakMitra::where('id', $k['id'])->update($data);
                } else {
                    KontakMitra::create($data);
                }
            }
        });

        return redirect()->route('mitra.index')
            ->with('success', 'Mitra berhasil diperbarui.');
    }

    public function destroy(Mitra $mitra)
    {
        $mitra->delete(); // Cascades delete to kontak_mitras

        return redirect()->route('mitra.index')
            ->with('success', 'Mitra berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new MitraExport(), 'data-mitra.xlsx');
    }
}
