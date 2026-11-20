<?php

namespace App\Exports;

use App\Models\Kerjasama;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KerjasamaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
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
     * Petakan data ke 12 kolom resmi Excel.
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

        return [
            $row->nomor_dokumen_kerjasama ?? '',
            $row->nomor_dokumen_mitra ?? '',
            $row->jenisDokumen ? $row->jenisDokumen->nama_jenis_dokumen : '',
            $unitKerjaStr,
            $row->mitra ? $row->mitra->nama_mitra : '',
            $row->judul_kerjasama ?? '',
            $row->deskripsi_kerjasama ?? '',
            $row->sumberDana ? $row->sumberDana->nama_sumber_dana : '',
            $row->anggaran ?? 0,
            $row->tanggal_waktu_berlaku ? $row->tanggal_waktu_berlaku->format('Y-m-d') : '',
            $row->tanggal_akhir_berlaku ? $row->tanggal_akhir_berlaku->format('Y-m-d') : '',
            $row->status_kerjasama ?? 'Aktif',
        ];
    }

    /**
     * Judul 12 kolom header Excel (sesuai format resmi).
     */
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

    /**
     * Styling baris header hijau gelap UIS.
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
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0B7A61'],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];
    }

    /**
     * AutoFilter dan styling border.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Aktifkan panah dropdown AutoFilter pada baris 1
                $sheet->setAutoFilter('A1:L1');

                // Atur tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(32);

                // Format border untuk seluruh sel tabel
                $highestRow = $sheet->getHighestRow();
                $cellRange = 'A1:L' . $highestRow;
                $sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D0D7DE'],
                        ],
                    ],
                ]);

                // Format teks wrap pada kolom Judul (F) dan Deskripsi (G)
                $sheet->getStyle('F2:G' . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
