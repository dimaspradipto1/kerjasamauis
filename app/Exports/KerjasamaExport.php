<?php

namespace App\Exports;

use App\Models\Kerjasama;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KerjasamaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Kerjasama::with(['jenisDokumen', 'mitra', 'unitKerja', 'sumberDana'])->get();
    }

    /**
    * @var Kerjasama $row
    */
    public function map($row): array
    {
        return [
            $row->nomor_dokumen_kerjasama,
            $row->nomor_dokumen_mitra,
            $row->jenisDokumen ? $row->jenisDokumen->nama_jenis_dokumen : '',
            $row->unitKerja ? $row->unitKerja->nama_unit_kerja : '',
            $row->mitra ? $row->mitra->nama_mitra : '',
            $row->judul_kerjasama,
            $row->deskripsi_kerjasama,
            $row->sumberDana ? $row->sumberDana->nama_sumber_dana : '',
            $row->anggaran,
            $row->tanggal_waktu_berlaku ? $row->tanggal_waktu_berlaku->format('Y-m-d') : '',
            $row->tanggal_akhir_berlaku ? $row->tanggal_akhir_berlaku->format('Y-m-d') : '',
            $row->status_kerjasama,
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Dokumen Kerjasama',
            'Nomor Dokumen Mitra',
            'Jenis Dokumen',
            'Unit Kerja',
            'Mitra',
            'Judul Kerjasama',
            'Deskripsi Kerjasama',
            'Sumber Dana',
            'Anggaran',
            'Tanggal Awal Berlaku',
            'Tanggal Akhir Berlaku',
            'Status Kerjasama',
        ];
    }
}
