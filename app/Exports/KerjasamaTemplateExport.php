<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KerjasamaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * 12 Kolom Header Template Excel.
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
     * Contoh data pada template (sebagian kolom dikosongkan untuk menunjukkan sifat nullable).
     */
    public function array(): array
    {
        return [
            [
                '002/UIS.KUI/VII/2025',
                'UTHM/IA/2025/01',
                'Implementation of Arrangement (IA)',
                'S1 - Teknik Industri, S1 - Teknik Informatika',
                'Universitas Tun Hussein Onn Malaysia (UTHM)',
                'Guest Lecture with theme : Introduction to Additive Manufacturing (Opportunity and Challenge)',
                'Pelaksanaan kuliah umum internasional bersama dosen tamu UTHM',
                'Internal UIS',
                '5000000',
                '2025-07-24',
                '2025-07-24',
                'Aktif',
            ],
            [
                '', // Nomor Dokumen Kerjasama (boleh kosong / nullable)
                '', // Nomor Dokumen Mitra (boleh kosong / nullable)
                'MoU',
                'Universitas Ibnu Sina',
                'Universitas Darussalam Gontor',
                'Nota Kesepahaman antara Universitas Ibnu Sina dan Universitas Darussalam Gontor tentang Kerja Sama Tri Dharma Perguruan Tinggi',
                '', // Deskripsi Kerjasama (boleh kosong / nullable)
                '', // Sumber Dana (boleh kosong / nullable)
                '', // Anggaran (boleh kosong / nullable)
                '2019-10-12',
                '2024-05-12',
                'Berakhir',
            ],
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
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0B7A61'],
                ],
            ],
        ];
    }
}
