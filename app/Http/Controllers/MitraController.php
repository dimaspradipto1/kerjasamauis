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

    public function ajaxStore(\Illuminate\Http\Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'jenis_mitra'            => 'required|string|in:Perguruan Tinggi,Non Perguruan Tinggi',
            'nama_mitra'             => 'required|string|max:500',
            'kriteria_mitra_id'      => 'required|exists:kriteria_mitras,id',
            'nomor_izin_usaha'       => 'nullable|string|max:255',
            'npwp'                   => 'nullable|string|max:255',
            'lingkup_mitra'          => 'required|string|in:Lokal,Regional,Nasional,Internasional',
            'negara'                 => 'nullable|string|max:255',
            'provinsi'               => 'nullable|string|max:255',
            'kabupaten_kota'         => 'nullable|string|max:255',
            'kecamatan'              => 'nullable|string|max:255',
            'kodepos'                => 'nullable|string|max:20',
            'alamat'                 => 'nullable|string',
            'email'                  => 'nullable|email|max:255',
            'no_telp'                => 'nullable|string|max:50',
            'website'                => 'nullable|string|max:255',
            
            // Kontak Mitra
            'kontak'                 => 'nullable|array',
            'kontak.*.nama_kontak'   => 'nullable|string|max:255',
            'kontak.*.jabatan'       => 'nullable|string|max:255',
            'kontak.*.nomor_handphone' => 'nullable|string|max:50',
            'kontak.*.email'         => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        $mitra = null;
        DB::transaction(function () use ($validatedData, &$mitra) {
            $mitraData = $validatedData;
            unset($mitraData['kontak']);
            $mitra = Mitra::create($mitraData);

            if (!empty($validatedData['kontak'])) {
                foreach ($validatedData['kontak'] as $k) {
                    if (!empty($k['nama_kontak'])) {
                        KontakMitra::create([
                            'mitra_id'        => $mitra->id,
                            'nama_kontak'     => $k['nama_kontak'],
                            'jabatan'         => $k['jabatan'] ?? '',
                            'nomor_handphone' => $k['nomor_handphone'] ?? '',
                            'email'           => $k['email'] ?? '',
                        ]);
                    }
                }
            }
        });

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'         => $mitra->id,
                'nama_mitra' => $mitra->nama_mitra
            ]
        ]);
    }
}
