<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BentukKegiatan;

class BentukKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ── Pendidikan ──────────────────────────────────────────
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Pertukaran Mahasiswa'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Transfer Kredit'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Pengiriman Praktisi sebagai Dosen'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Pertukaran Dosen'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Gelar Ganda (Dual Degree)'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Pertukaran Pelajar-Kampus Merdeka'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Gelar Bersama (Joint Degree)'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Pengembangan Kurikulum/Program Bersama'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Asistensi Mengajar di Satuan Pendidikan-Kampus Merdeka'],
            ['jenis_kegiatan' => 'Pendidikan', 'nama_bentuk_kegiatan' => 'Visiting Professor'],

            // ── Penelitian ──────────────────────────────────────────
            ['jenis_kegiatan' => 'Penelitian', 'nama_bentuk_kegiatan' => 'Penelitian Bersama - Artikel/Jurnal Ilmiah'],
            ['jenis_kegiatan' => 'Penelitian', 'nama_bentuk_kegiatan' => 'Penelitian Bersama - Paten'],
            ['jenis_kegiatan' => 'Penelitian', 'nama_bentuk_kegiatan' => 'Penelitian Bersama - Prototipe'],
            ['jenis_kegiatan' => 'Penelitian', 'nama_bentuk_kegiatan' => 'Penelitian/Riset-Kampus Merdeka'],
            ['jenis_kegiatan' => 'Penelitian', 'nama_bentuk_kegiatan' => 'Penerbitan Berkala Ilmiah'],
            ['jenis_kegiatan' => 'Penelitian', 'nama_bentuk_kegiatan' => 'Pengembangan Pusat Penelitian dan Pengembangan Keilmuan'],
            ['jenis_kegiatan' => 'Penelitian', 'nama_bentuk_kegiatan' => 'Penelitian Bersama'],

            // ── Pengabdian ──────────────────────────────────────────
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Pemagangan'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Pengembangan Sistem / Produk'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Proyek Kemanusiaan-Kampus Merdeka'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Penyaluran Lulusan'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Penyelenggaraan Seminar/Konferensi Ilmiah'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Kegiatan Wirausaha-Kampus Merdeka'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Magang/ Praktik Kerja-Kampus Merdeka'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Membangun Desa/KKN Tematik-Kampus Merdeka'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Pelatihan'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Pelatihan Dosen dan Instruktur'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Pengabdian Kepada Masyarakat'],
            ['jenis_kegiatan' => 'Pengabdian', 'nama_bentuk_kegiatan' => 'Studi/Proyek Independen-Kampus Merdeka'],
        ];

        foreach ($data as $item) {
            BentukKegiatan::firstOrCreate(
                ['nama_bentuk_kegiatan' => $item['nama_bentuk_kegiatan']],
                $item
            );
        }

        $this->command->info('BentukKegiatanSeeder: ' . count($data) . ' data berhasil di-seed.');
    }
}
