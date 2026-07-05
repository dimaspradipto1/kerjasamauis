<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\DataTables\UnitKerjaDataTable;
use App\Http\Requests\UnitKerjaRequest;

class UnitKerjaController extends Controller
{
    public function index(UnitKerjaDataTable $dataTable)
    {
        return $dataTable->render('pages.unitkerja.index');
    }

    public function create()
    {
        return view('pages.unitkerja.create');
    }

    public function store(UnitKerjaRequest $request)
    {
        UnitKerja::create($request->validated());

        return redirect()->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function edit(UnitKerja $unitKerja)
    {
        return view('pages.unitkerja.edit', compact('unitKerja'));
    }

    public function update(UnitKerjaRequest $request, UnitKerja $unitKerja)
    {
        $unitKerja->update($request->validated());

        return redirect()->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unitKerja)
    {
        $unitKerja->delete();

        return redirect()->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil dihapus.');
    }
}
