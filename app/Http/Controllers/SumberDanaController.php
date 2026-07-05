<?php

namespace App\Http\Controllers;

use App\Models\SumberDana;
use App\DataTables\SumberDanaDataTable;
use App\Http\Requests\SumberDanaRequest;

class SumberDanaController extends Controller
{
    public function index(SumberDanaDataTable $dataTable)
    {
        return $dataTable->render('pages.sumberdana.index');
    }

    public function create()
    {
        return view('pages.sumberdana.create');
    }

    public function store(SumberDanaRequest $request)
    {
        SumberDana::create($request->validated());

        return redirect()->route('sumber-dana.index')
            ->with('success', 'Sumber dana berhasil ditambahkan.');
    }

    public function edit(SumberDana $sumberDana)
    {
        return view('pages.sumberdana.edit', compact('sumberDana'));
    }

    public function update(SumberDanaRequest $request, SumberDana $sumberDana)
    {
        $sumberDana->update($request->validated());

        return redirect()->route('sumber-dana.index')
            ->with('success', 'Sumber dana berhasil diperbarui.');
    }

    public function destroy(SumberDana $sumberDana)
    {
        $sumberDana->delete();

        return redirect()->route('sumber-dana.index')
            ->with('success', 'Sumber dana berhasil dihapus.');
    }
}
