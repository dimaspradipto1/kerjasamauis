<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SumberDana;

class SumberDanaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Badan PSDMPK dan PMP',
            'Balitbang',
            'Bantuan Asing',
            'Bantuan Swasta',
            'Biro PKLN',
            'DIPA PTN',
            'DP2M Ristekdikti',
            'Dana mandiri',
            'Dikti',
            'Dinas Kabupaten',
            'Dinas Propinsi',
            'Direktorat P2TK Dikdas',
            'Direktorat P2TK Dikmen',
            'Direktorat PKLK Dikdas',
            'Direktorat PKLK Dikmen',
            'Direktorat PSD',
            'Direktorat PSMA',
            'Direktorat PSMK',
            'Direktorat PSMP',
            'Insinas Ristekdikti',
            'Lembaga donor dalam negeri',
            'Lembaga donor luar negeri',
            'Puskurbuk',
            'Puspendik',
            'Pustekkom',
            'Sekretariat Dikdas',
            'Sekretariat Dikmen',
        ];

        foreach ($data as $item) {
            SumberDana::firstOrCreate(
                ['nama_sumber_dana' => $item],
                ['keterangan' => null]
            );
        }

        $this->command->info('SumberDanaSeeder: ' . count($data) . ' data berhasil di-seed.');
    }
}
