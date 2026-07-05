<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisDokumen;

class JenisDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Implementation of Arrangement (IA)',
            'Memorandum of Agreement (MoA)',
            'Memorandum of Understanding (MoU)',
        ];

        foreach ($data as $item) {
            JenisDokumen::firstOrCreate(
                ['nama_jenis_dokumen' => $item],
                ['keterangan' => null]
            );
        }

        $this->command->info('JenisDokumenSeeder: ' . count($data) . ' data berhasil di-seed.');
    }
}
