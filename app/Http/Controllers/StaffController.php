<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\UnitKerja;
use App\DataTables\StaffDataTable;
use App\Http\Requests\StaffRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffExport;
use App\Imports\StaffImport;

class StaffController extends Controller
{
    public function index(StaffDataTable $dataTable)
    {
        $unitKerjas = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();
        return $dataTable->render('pages.staff.index', compact('unitKerjas'));
    }

    public function create()
    {
        $unitKerjas = UnitKerja::orderBy('id', 'asc')->get();
        return view('pages.staff.create', compact('unitKerjas'));
    }

    public function store(StaffRequest $request)
    {
        Staff::create($request->validated());

        return redirect()->route('staff.index')
            ->with('success', 'Data staff berhasil ditambahkan.');
    }

    public function edit(Staff $staff)
    {
        $unitKerjas = UnitKerja::orderBy('id', 'asc')->get();
        return view('pages.staff.edit', compact('staff', 'unitKerjas'));
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $staff->update($request->validated());

        return redirect()->route('staff.index')
            ->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Data staff berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new StaffExport(), 'data-staff.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new StaffImport(), $request->file('file'));

        return redirect()->route('staff.index')
            ->with('success', 'Data staff berhasil di-import!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new class () implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array
            {
                return [
                    'Nama Staff',
                    'NUP',
                    'Unit Kerja',
                    'Jabatan',
                    'Email',
                    'Nomor Telepon',
                    'Alamat',
                    'Status',
                ];
            }
        }, 'format-import-staff.xlsx');
    }
}
