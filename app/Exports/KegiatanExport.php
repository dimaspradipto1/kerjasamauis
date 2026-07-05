<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KegiatanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Kegiatan::with([
            'kerjasama', 'unitKerja', 'mitra',
            'sasaranKinerja', 'bentukKegiatan', 'indikator'
        ])->get();
    }

    public function map($row): array
    {
        return [
            $row->kerjasama ? $row->kerjasama->judul_kerjasama : '',
            $row->unitKerja ? $row->unitKerja->nama_unit_kerja : '',
            $row->mitra ? $row->mitra->nama_mitra : '',
            $row->sasaranKinerja ? $row->sasaranKinerja->sasaran_kinerja : '',
            $row->bentukKegiatan ? $row->bentukKegiatan->nama_bentuk_kegiatan : '',
            $row->indikator ? $row->indikator->indikator_sasaran : '',
            $row->nomor_dokumen_kegiatan,
            $row->nomor_dokumen_mitra,
            $row->judul_kegiatan,
            $row->tanggal_awal_kegiatan ? $row->tanggal_awal_kegiatan->format('Y-m-d') : '',
            $row->tanggal_akhir_kegiatan ? $row->tanggal_akhir_kegiatan->format('Y-m-d') : '',
            $row->ruang_lingkup,
            $row->hasil_pelakasanaan,
            $row->nilai_kontrak,
            $row->link_dokumen_kegiatan,
        ];
    }

    public function headings(): array
    {
        return [
            'Induk Kerjasama',
            'Unit Kerja',
            'Mitra',
            'Sasaran Kinerja',
            'Bentuk Kegiatan',
            'Indikator Sasaran',
            'Nomor Dokumen Kegiatan',
            'Nomor Dokumen Mitra',
            'Judul Kegiatan',
            'Tanggal Awal Kegiatan',
            'Tanggal Akhir Kegiatan',
            'Ruang Lingkup',
            'Hasil Pelaksanaan',
            'Nilai Kontrak',
            'Link Dokumen Kegiatan',
        ];
    }
}
