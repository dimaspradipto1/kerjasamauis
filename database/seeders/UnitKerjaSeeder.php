<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKerja;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'S2 - S2 Manajemen',
            'S1 - S1 Manajemen',
            'S1 - S1 Akuntansi',
            'S2 - Magister Kesehatan Masyarakat',
            'S1 - Kesehatan dan Keselamatan Kerja',
            'S1 - Kesehatan Lingkungan',
            'S1 - Teknik Industri',
            'S1 - Teknik Perkapalan',
            'S1 - Teknik Informatika',
            'S1 - Sistem Informasi',
            'S1 - Teknik Logistik',
        ];

        foreach ($data as $item) {
            UnitKerja::firstOrCreate(
                ['nama_unit_kerja' => $item],
                ['keterangan' => null]
            );
        }

        $this->command->info('UnitKerjaSeeder: ' . count($data) . ' data berhasil di-seed.');
    }
}
