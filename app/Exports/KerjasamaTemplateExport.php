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
     * Judul kolom header Excel template import.
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
     * Contoh data pada template untuk memandu pengisian user.
     */
    public function array(): array
    {
        return [
            [
                '002/UIS.KUI/VII/2025',
                'UTHM/IA/2025/01',
                'Implementation of Arrangement (IA)',
                'Pendidikan',
                'S1 - Teknik Industri, S1 - Teknik Informatika',
                'Universitas Tun Hussein Onn Malaysia (UTHM)',
                'Guest Lecture with theme : Introduction to Additive Manufacturing (Opportunity and Challenge)',
                'Pelaksanaan kuliah umum internasional bersama dosen tamu UTHM',
                'Internasional',
                'Internal UIS',
                '5000000',
                '2025-07-24',
                '2025-07-24',
                'Aktif',
                'Terlaksana kuliah umum dengan dihadiri 120 mahasiswa.',
            ],
            [
                '001/UIS.KUI/IV/2022',
                '',
                'Memorandum of Understanding (MoU)',
                '',
                'S1 - Manajemen, S1 - Akuntansi, Fakultas Ekonomi dan Bisnis',
                'Bank Muamalat',
                'Tridharma Perguruan tinggi',
                'Kerjasama tridharma perguruan tinggi dan program magang mahasiswa',
                'Nasional',
                'Mandiri',
                '0',
                '2022-04-24',
                '2027-04-24',
                'Aktif',
                '',
            ],
        ];
    }

    /**
     * Styling baris header template.
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
