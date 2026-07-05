<?php

namespace App\Http\Controllers;

use App\Models\BentukKegiatan;
use App\DataTables\BentukKegiatanDataTable;
use App\Http\Requests\BentukKegiatanStoreRequest;
use App\Http\Requests\BentukKegiatanUpdateRequest;
use Illuminate\Http\Request;

class BentukKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BentukKegiatanDataTable $dataTable)
    {
        return $dataTable->render('pages.bentuk-kegiatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisKegiatan = BentukKegiatan::JENIS;
        return view('pages.bentuk-kegiatan.create', compact('jenisKegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BentukKegiatanStoreRequest $request)
    {
        BentukKegiatan::create($request->validated());

        return redirect()->route('bentuk-kegiatan.index')
            ->with('success', 'Bentuk kegiatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bentukKegiatan = BentukKegiatan::findOrFail($id);
        $jenisKegiatan  = BentukKegiatan::JENIS;
        return view('pages.bentuk-kegiatan.edit', compact('bentukKegiatan', 'jenisKegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BentukKegiatanUpdateRequest $request, $id)
    {
        $bentukKegiatan = BentukKegiatan::findOrFail($id);
        $bentukKegiatan->update($request->validated());

        return redirect()->route('bentuk-kegiatan.index')
            ->with('success', 'Bentuk kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bentukKegiatan = BentukKegiatan::findOrFail($id);
        $bentukKegiatan->delete();

        return redirect()->route('bentuk-kegiatan.index')
            ->with('success', 'Bentuk kegiatan berhasil dihapus.');
    }
}
