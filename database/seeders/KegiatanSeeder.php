<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\KegiatanPihak;
use App\Models\KegiatanPj;
use App\Models\Kerjasama;
use App\Models\UnitKerja;
use App\Models\Mitra;
use App\Models\BentukKegiatan;
use App\Models\SasaranKinerja;
use App\Models\IndikatorSasaran;
use Illuminate\Support\Facades\DB;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Find references
            $kerjasama = Kerjasama::where('judul_kerjasama', 'like', '%Tri Dharma%')->first();
            if (!$kerjasama) {
                $kerjasama = Kerjasama::first();
            }

            $unitKerja = UnitKerja::where('nama_unit_kerja', 'like', '%Fakultas Sains dan Teknologi%')->first();
            if (!$unitKerja) {
                $unitKerja = UnitKerja::first();
            }

            $mitra = Mitra::where('nama_mitra', 'like', '%Infinite Learning%')->first();
            if (!$mitra) {
                $mitra = Mitra::first();
            }

            $bentukKegiatan = BentukKegiatan::first();
            $sasaranKinerja = SasaranKinerja::first();
            $indikator = IndikatorSasaran::first();

            // Truncate tables first
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            KegiatanPj::query()->delete();
            KegiatanPihak::query()->delete();
            Kegiatan::query()->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Create Kegiatan
            $kegiatan = Kegiatan::create([
                'kerjasama_id'            => $kerjasama->id,
                'unit_kerja_id'           => $unitKerja->id,
                'mitra_id'                => $mitra->id,
                'sasaran_kinerja_id'      => $sasaranKinerja->id,
                'bentuk_kegiatan_id'      => $bentukKegiatan->id,
                'indikator_id'            => $indikator->id,
                'nomor_dokumen_kegiatan'  => '002/MOA/FT-UIS/XI/2025/KEG-01',
                'nomor_dokumen_mitra'     => '0323/II-BATAM/MOA/III/2025/KEG-01',
                'judul_kegiatan'          => 'MoA Antara Fakultas Sains dan Teknologi Universitas Ibnu Sina dengan Infinite Learning',
                'tanggal_awal_kegiatan'   => '2025-05-05',
                'tanggal_akhir_kegiatan'  => '2029-05-05',
                'ruang_lingkup'           => 'Tri Dharma Perguruan Tinggi',
                'hasil_pelakasanaan'      => 'Pendidikan & Pelatihan Kampus Merdeka',
                'nilai_kontrak'           => 0,
                'link_dokumen_kegiatan'   => null,
                'url_file'                => null,
            ]);

            // Pihak 1
            $pihak1 = KegiatanPihak::create([
                'kegiatan_id'      => $kegiatan->id,
                'pihak_ke'         => '1',
                'jenis_pihak'      => 'Unit',
                'nomor_surat_izin' => null,
                'penanggung_jawab' => 'S1 - Fakultas Sains dan Teknologi',
            ]);

            KegiatanPj::create([
                'kegiatan_pihak_id' => $pihak1->id,
                'nama'              => 'Ir. Sanusi, ST., M.Eng., PhD., IPM',
                'nip'               => '',
                'jabatan'           => 'DEKAN',
                'nomor_hp'          => '',
                'email'             => '',
            ]);

            // Pihak 2
            $pihak2 = KegiatanPihak::create([
                'kegiatan_id'      => $kegiatan->id,
                'pihak_ke'         => '2',
                'jenis_pihak'      => 'Mitra',
                'nomor_surat_izin' => null,
                'penanggung_jawab' => 'Infinite Learning Indonesia',
            ]);

            KegiatanPj::create([
                'kegiatan_pihak_id' => $pihak2->id,
                'nama'              => 'Ari Nugrahanto, B.Ed., M.S.C',
                'nip'               => '',
                'jabatan'           => 'Direktur Utama',
                'nomor_hp'          => '',
                'email'             => '',
            ]);
        });

        $this->command->info('KegiatanSeeder: Data kegiatan berhasil di-seed.');
    }
}
