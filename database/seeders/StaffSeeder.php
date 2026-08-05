<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\UnitKerja;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $unitKerja = UnitKerja::first();

        $data = [
            [
                'nama_staff'    => 'Ahmad Fauzi, S.Kom.',
                'nup'           => '199208152020121001',
                'unit_kerja_id' => $unitKerja ? $unitKerja->id : null,
                'jabatan'       => 'Staf Administrasi Kerjasama',
                'email'         => 'ahmad.fauzi@uis.ac.id',
                'nomor_hp'      => '081234567890',
                'alamat'        => 'Jl. Teuku Umar No. 1, Batam',
                'status'        => 'Aktif',
            ],
            [
                'nama_staff'    => 'Siti Rahmah, S.E.',
                'nup'           => '199503102021012002',
                'unit_kerja_id' => $unitKerja ? $unitKerja->id : null,
                'jabatan'       => 'Staf Keuangan & Pelaporan',
                'email'         => 'siti.rahmah@uis.ac.id',
                'nomor_hp'      => '081298765432',
                'alamat'        => 'Jl. Hang Lekiu No. 12, Batam',
                'status'        => 'Aktif',
            ],
        ];

        foreach ($data as $item) {
            Staff::firstOrCreate(
                ['nama_staff' => $item['nama_staff']],
                $item
            );
        }

        $this->command->info('StaffSeeder: ' . count($data) . ' data staff berhasil di-seed.');
    }
}
