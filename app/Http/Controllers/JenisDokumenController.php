<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use App\DataTables\JenisDokumenDataTable;
use App\Http\Requests\JenisDokumenRequest;

class JenisDokumenController extends Controller
{
    public function index(JenisDokumenDataTable $dataTable)
    {
        return $dataTable->render('pages.jenisdokumen.index');
    }

    public function create()
    {
        return view('pages.jenisdokumen.create');
    }

    public function store(JenisDokumenRequest $request)
    {
        JenisDokumen::create($request->validated());

        return redirect()->route('jenis-dokumen.index')
            ->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    public function edit(JenisDokumen $jenisDokumen)
    {
        return view('pages.jenisdokumen.edit', compact('jenisDokumen'));
    }

    public function update(JenisDokumenRequest $request, JenisDokumen $jenisDokumen)
    {
        $jenisDokumen->update($request->validated());

        return redirect()->route('jenis-dokumen.index')
            ->with('success', 'Jenis dokumen berhasil diperbarui.');
    }

    public function destroy(JenisDokumen $jenisDokumen)
    {
        $jenisDokumen->delete();

        return redirect()->route('jenis-dokumen.index')
            ->with('success', 'Jenis dokumen berhasil dihapus.');
    }
}
