<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KriteriaMitra;

class KriteriaMitraSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Dunia Usaha',
            'Instansi pemerintah Pusat dan/atau Daerah BUMN dan/atau BUMD',
            'Institusi Pendidikan',
            'Institusi/ Organisasi multilateral',
            'Lembaga kebudayaan berskala nasional/ bereputasi',
            'Lembaga riset pemerintah, swasta, nasional, maupun internasional',
            'Organisasi / Instansi pemerintahan',
            'Organisasi nirlaba kelas dunia',
            'Perguruan tinggi dalam negeri dalam daftar QS200 berdasarkan bidang ilmu',
            'Perguruan tinggi luar negeri dalam daftar QS200 berdasarkan bidang ilmu',
            'Perguruan tinggi, fakultas, atau program studi dalam bidang yang relevan',
            'Perusahaan multinasional',
            'Perusahaan nasional berstandar tinggi',
            'Perusahaan rintisan (startup company) teknologi',
            'Perusahaan teknologi global',
            'Rumah Sakit',
        ];

        foreach ($data as $item) {
            KriteriaMitra::firstOrCreate(
                ['kriteria_mitra' => $item],
                ['keterangan' => null]
            );
        }

        $this->command->info('KriteriaMitraSeeder: ' . count($data) . ' data berhasil di-seed.');
    }
}
