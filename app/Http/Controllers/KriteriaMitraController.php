<?php

namespace App\Http\Controllers;

use App\Models\KriteriaMitra;
use App\DataTables\KriteriaMitraDataTable;
use App\Http\Requests\KriteriaMitraRequest;

class KriteriaMitraController extends Controller
{
    public function index(KriteriaMitraDataTable $dataTable)
    {
        return $dataTable->render('pages.kriteriamitra.index');
    }

    public function create()
    {
        return view('pages.kriteriamitra.create');
    }

    public function store(KriteriaMitraRequest $request)
    {
        KriteriaMitra::create($request->validated());

        return redirect()->route('kriteria-mitra.index')
            ->with('success', 'Kriteria mitra berhasil ditambahkan.');
    }

    public function edit(KriteriaMitra $kriteriaMitra)
    {
        return view('pages.kriteriamitra.edit', compact('kriteriaMitra'));
    }

    public function update(KriteriaMitraRequest $request, KriteriaMitra $kriteriaMitra)
    {
        $kriteriaMitra->update($request->validated());

        return redirect()->route('kriteria-mitra.index')
            ->with('success', 'Kriteria mitra berhasil diperbarui.');
    }

    public function destroy(KriteriaMitra $kriteriaMitra)
    {
        $kriteriaMitra->delete();

        return redirect()->route('kriteria-mitra.index')
            ->with('success', 'Kriteria mitra berhasil dihapus.');
    }
}
