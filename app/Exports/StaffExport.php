<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StaffExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Staff::with('unitKerja')->get();
    }

    /**
    * @var Staff $staff
    */
    public function map($staff): array
    {
        return [
            $staff->nama_staff,
            $staff->nup ?? '',
            $staff->unitKerja ? $staff->unitKerja->nama_unit_kerja : '',
            $staff->jabatan ?? '',
            $staff->email ?? '',
            $staff->nomor_hp ?? '',
            $staff->alamat ?? '',
            $staff->status ?? 'Aktif',
        ];
    }

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
}
