<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KerjasamaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    /**
     * 12 Kolom Header Resmi Sesuai Format Excel UIS.
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
     * Contoh data pada template mengikuti format resmi dari user:
     * - Baris 1: Contoh data dengan kolom nullable dibiarkan kosong (MoU UIS - Gontor).
     * - Baris 2: Contoh data lengkap dengan nomor dokumen & multi unit kerja (IA).
     */
    public function array(): array
    {
        return [
            [
                '', // Nomor Dokumen Kerjasama (Boleh kosong / nullable)
                '', // Nomor Dokumen Mitra (Boleh kosong / nullable)
                'MoU',
                'Universitas Ibnu Sina',
                'Universitas Darussalam Gontor',
                'Nota Kesepahaman antara Universitas Ibnu Sina dan Universitas Darussalam Gontor tentang Kerja Sama Tri Dharma Perguruan Tinggi',
                'Nota kesepahaman kerja sama bidang Tri Dharma Perguruan Tinggi (pendidikan, penelitian, dan pengabdian kepada masyarakat) antara Universitas Ibnu Sina dan Universitas Darussalam Gontor tentang Kerja Sama Tri Dharma Perguruan Tinggi',
                '', // Sumber Dana (Boleh kosong / nullable)
                '', // Anggaran (Boleh kosong / nullable)
                '2019-03-12',
                '2024-03-12',
                'Berakhir',
            ],
            [
                '002/UIS.KUI/VII/2025',
                'UTHM/IA/2025/01',
                'Implementation of Arrangement (IA)',
                'S1 - Teknik Industri, S1 - Teknik Informatika',
                'Universitas Tun Hussein Onn Malaysia (UTHM)',
                'Guest Lecture with theme : Introduction to Additive Manufacturing (Opportunity and Challenge)',
                'Pelaksanaan kuliah umum internasional bersama dosen tamu UTHM',
                'Mandiri',
                '5000000',
                '2025-07-24',
                '2025-07-24',
                'Aktif',
            ],
        ];
    }

    /**
     * Styling baris header hijau gelap UIS dan data cell.
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
                    'startColor' => ['rgb' => '0B7A61'], // Hijau UIS resmi
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];
    }

    /**
     * Aktifkan AutoFilter (dropdown panah) dan border sel.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Aktifkan panah dropdown AutoFilter pada baris 1 (kolom A s.d L)
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
