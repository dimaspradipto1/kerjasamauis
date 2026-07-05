<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BentukKegiatanSeeder::class,
            SasaranKinerjaSeeder::class,
            KriteriaMitraSeeder::class,
            SumberDanaSeeder::class,
            JenisDokumenSeeder::class,
            UnitKerjaSeeder::class,
            MitraSeeder::class,
            KerjasamaSeeder::class,
        ]);
    }
}
