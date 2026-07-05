<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mitra;
use App\Models\KriteriaMitra;

class MitraSeeder extends Seeder
{
    public function run(): void
    {
        // Cari kriteria mitra yang cocok
        $kriteria = KriteriaMitra::where('kriteria_mitra', 'Perusahaan teknologi global')->first();
        $kriteriaId = $kriteria ? $kriteria->id : 1;

        Mitra::firstOrCreate(
            ['nama_mitra' => 'Infinite Learning Indonesia'],
            [
                'jenis_mitra'       => 'Non Perguruan Tinggi',
                'kriteria_mitra_id' => $kriteriaId,
                'nomor_izin_usaha'  => null,
                'npwp'              => null,
                'lingkup_mitra'     => 'Internasional',
                'provinsi'          => 'KEPULAUAN RIAU',
                'kabupaten_kota'    => 'KOTA BATAM',
                'kecamatan'         => 'NONGSA',
                'kodepos'           => '29465',
                'alamat'            => 'Jl. Hang Lekiu, Sambau',
                'email'             => null,
                'no_telp'           => null,
                'website'           => null,
            ]
        );

        $this->command->info('MitraSeeder: Data Infinite Learning Indonesia berhasil di-seed.');
    }
}
