<?php

namespace App\Exports;

use App\Models\Kerjasama;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KerjasamaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * Ambil data kerjasama beserta relasinya.
     */
    public function collection()
    {
        return Kerjasama::with(['jenisDokumen', 'mitra', 'unitKerja', 'unitKerjas', 'sumberDana'])
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Petakan kolom database ke baris Excel.
     */
    public function map($row): array
    {
        // Gabungkan seluruh Unit Kerja jika multi-unit
        $rawUnits = $row->unitKerjas->isNotEmpty()
            ? $row->unitKerjas->pluck('nama_unit_kerja')->toArray()
            : ($row->unitKerja ? [$row->unitKerja->nama_unit_kerja] : []);

        $units = [];
        foreach ($rawUnits as $u) {
            if (str_contains($u, ',')) {
                foreach (array_map('trim', explode(',', $u)) as $p) {
                    if ($p !== '') {
                        $units[] = $p;
                    }
                }
            } else {
                if (trim($u) !== '') {
                    $units[] = trim($u);
                }
            }
        }
        $unitKerjaStr = implode(', ', $units);

        // Skala Kerjasama
        $skala = '';
        if (is_array($row->skala_kerjasama)) {
            $skala = implode(', ', $row->skala_kerjasama);
        } elseif (!empty($row->skala_kerjasama)) {
            $skala = (string) $row->skala_kerjasama;
        }

        return [
            $row->nomor_dokumen_kerjasama ?? '',
            $row->nomor_dokumen_mitra ?? '',
            $row->jenisDokumen ? $row->jenisDokumen->nama_jenis_dokumen : '',
            $row->bidang_implementasi ?? '',
            $unitKerjaStr,
            $row->mitra ? $row->mitra->nama_mitra : '',
            $row->judul_kerjasama ?? '',
            $row->deskripsi_kerjasama ?? '',
            $skala,
            $row->sumberDana ? $row->sumberDana->nama_sumber_dana : '',
            $row->anggaran ?? 0,
            $row->tanggal_waktu_berlaku ? $row->tanggal_waktu_berlaku->format('Y-m-d') : '',
            $row->tanggal_akhir_berlaku ? $row->tanggal_akhir_berlaku->format('Y-m-d') : '',
            $row->status_kerjasama ?? 'Aktif',
            $row->hasil_pelaksanaan ?? '',
        ];
    }

    /**
     * Judul kolom header Excel.
     */
    public function headings(): array
    {
        return [
            'Nomor Dokumen Kerjasama',
            'Nomor Dokumen Mitra',
            'Jenis Dokumen',
            'Bidang Implementasi (IA)',
            'Unit Kerja',
            'Mitra',
            'Judul Kerjasama',
            'Deskripsi Kerjasama',
            'Skala Kerjasama',
            'Sumber Dana',
            'Anggaran',
            'Tanggal Awal Berlaku',
            'Tanggal Akhir Berlaku',
            'Status Kerjasama',
            'Hasil Pelaksanaan',
        ];
    }

    /**
     * Styling baris header.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0B7A61'],
                ],
            ],
        ];
    }
}
