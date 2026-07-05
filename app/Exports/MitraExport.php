<?php

namespace App\Exports;

use App\Models\Mitra;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MitraExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Mitra::with('kriteriaMitra')->orderBy('nama_mitra', 'asc')->get();
    }

    /**
    * @var Mitra $row
    */
    public function map($row): array
    {
        return [
            $row->nama_mitra,
            $row->jenis_mitra,
            $row->kriteriaMitra ? $row->kriteriaMitra->kriteria_mitra : '',
            $row->lingkup_mitra,
            $row->nomor_izin_usaha,
            $row->npwp,
            $row->provinsi,
            $row->kabupaten_kota,
            $row->kecamatan,
            $row->kodepos,
            $row->alamat,
            $row->email,
            $row->no_telp,
            $row->website,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Mitra',
            'Jenis Mitra',
            'Kriteria Mitra',
            'Lingkup Mitra',
            'Nomor Izin Usaha',
            'NPWP',
            'Provinsi',
            'Kabupaten/Kota',
            'Kecamatan',
            'Kodepos',
            'Alamat',
            'Email',
            'No. Telp',
            'Website',
        ];
    }
}
