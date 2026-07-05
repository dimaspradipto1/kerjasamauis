<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kerjasama;
use App\Models\KerjasamaPihak;
use App\Models\KerjasamaPenanggungJawab;
use App\Models\JenisDokumen;
use App\Models\Mitra;
use App\Models\UnitKerja;
use App\Models\SumberDana;
use Illuminate\Support\Facades\DB;

class KerjasamaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Re-seed all dependecy lookup data to match screenshot perfectly
            $moa = JenisDokumen::firstOrCreate(['nama_jenis_dokumen' => 'Memorandum of Agreement (MoA)']);
            $mou = JenisDokumen::firstOrCreate(['nama_jenis_dokumen' => 'Memorandum of Understanding (MoU)']);
            $ia  = JenisDokumen::firstOrCreate(['nama_jenis_dokumen' => 'Implementation of Arrangement (IA)']);

            $infinite = Mitra::firstOrCreate([
                'nama_mitra' => 'Infinite Learning Indonesia'
            ], [
                'jenis_mitra'       => 'Non Perguruan Tinggi',
                'lingkup_mitra'     => 'Internasional',
                'alamat' => 'Jl. Hang Lekiu, Sambau, Kecamatan Nongsa, Kota Batam, Kepulauan Riau 29465',
                'provinsi' => 'Kepulauan Riau',
                'kabupaten_kota' => 'Kota Batam',
                'kecamatan' => 'Nongsa',
                'kriteria_mitra_id' => 1
            ]);

            $a2k3 = Mitra::firstOrCreate([
                'nama_mitra' => 'Asosiasi Ahli Keselamatan dan Kesehatan Kerja (A2K3)'
            ], [
                'jenis_mitra'       => 'Non Perguruan Tinggi',
                'lingkup_mitra'     => 'Nasional',
                'alamat' => 'Gedung A2K3 Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kabupaten_kota' => 'Jakarta Pusat',
                'kecamatan' => 'Menteng',
                'kriteria_mitra_id' => 1
            ]);

            $fst = UnitKerja::firstOrCreate(['nama_unit_kerja' => 'S1 - Fakultas Sains dan Teknologi']);
            $uis = UnitKerja::firstOrCreate(['nama_unit_kerja' => 'Universitas Ibnu Sina']);
            $ti  = UnitKerja::firstOrCreate(['nama_unit_kerja' => 'S1 - Teknik Informatika']);
            $fik = UnitKerja::firstOrCreate(['nama_unit_kerja' => 'Fakultas Ilmu Kesehatan']);
            $k3  = UnitKerja::firstOrCreate(['nama_unit_kerja' => 'S1 - Kesehatan dan Keselamatan Kerja']);

            $sumberDana = SumberDana::first();
            if (!$sumberDana) {
                $sumberDana = SumberDana::firstOrCreate(['nama_sumber_dana' => 'Dana mandiri']);
            }

            // Clean tables first to avoid duplicates
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            KerjasamaPenanggungJawab::query()->delete();
            KerjasamaPihak::query()->delete();
            Kerjasama::query()->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Data Definition
            $items = [
                [
                    'nomor_dokumen_kerjasama' => '002/MOA/FT-UIS/XI/2025',
                    'nomor_dokumen_mitra'     => '0323/II-BATAM/MOA/III/2025',
                    'jenis_dokumen_id'        => $moa->id,
                    'unit_kerja_id'           => $fst->id,
                    'mitra_id'                => $infinite->id,
                    'judul_kerjasama'         => 'Pelaksanaan Tri Dharma Perguruan Tinggi',
                    'deskripsi_kerjasama'     => 'Pelaksanaan kerjasama MoA Antara Fakultas Sains dan Teknologi dengan Infinite Learning',
                    'tanggal_waktu_berlaku' => '2025-05-05',
                    'tanggal_akhir_berlaku' => '2030-05-05',
                    'pihak1_name'             => 'Ir. Sanusi, ST., M.Eng., PhD., IPM',
                    'pihak1_jabatan'          => 'DEKAN',
                    'pihak2_name'             => 'Ari Nugrahanto, B.Ed., M.S.C',
                    'pihak2_jabatan'          => 'Direktur Utama',
                ],
                [
                    'nomor_dokumen_kerjasama' => '173/UIS.R/KS/III/2025',
                    'nomor_dokumen_mitra'     => '',
                    'jenis_dokumen_id'        => $mou->id,
                    'unit_kerja_id'           => $uis->id,
                    'mitra_id'                => $infinite->id,
                    'judul_kerjasama'         => 'Tri Dharma Perguruan Tinggi',
                    'deskripsi_kerjasama'     => 'Tri Dharma Perguruan Tinggi MoU tingkat Universitas',
                    'tanggal_waktu_berlaku' => '2025-03-14',
                    'tanggal_akhir_berlaku' => '2030-03-14',
                    'pihak1_name'             => 'Rektor Universitas Ibnu Sina',
                    'pihak1_jabatan'          => 'Rektor',
                    'pihak2_name'             => 'Ari Nugrahanto, B.Ed., M.S.C',
                    'pihak2_jabatan'          => 'Direktur Utama',
                ],
                [
                    'nomor_dokumen_kerjasama' => '173/UIS.R/KS/III/2025',
                    'nomor_dokumen_mitra'     => '',
                    'jenis_dokumen_id'        => $ia->id,
                    'unit_kerja_id'           => $ti->id,
                    'mitra_id'                => $infinite->id,
                    'judul_kerjasama'         => 'Pelaksanaan Implementasi Kunjungan Industri',
                    'deskripsi_kerjasama'     => 'Kunjungan Industri mahasiswa Program Studi Teknik Informatika',
                    'tanggal_waktu_berlaku' => '2025-03-14',
                    'tanggal_akhir_berlaku' => '2030-03-14',
                    'pihak1_name'             => 'Ketua Program Studi',
                    'pihak1_jabatan'          => 'Kaprodi',
                    'pihak2_name'             => 'Manager HRD',
                    'pihak2_jabatan'          => 'HRD',
                ],
                [
                    'nomor_dokumen_kerjasama' => '006/FIKes.D/KP/I/2025',
                    'nomor_dokumen_mitra'     => '',
                    'jenis_dokumen_id'        => $moa->id,
                    'unit_kerja_id'           => $fik->id,
                    'mitra_id'                => $a2k3->id,
                    'judul_kerjasama'         => 'Tri Dharma Perguruan Tinggi',
                    'deskripsi_kerjasama'     => 'Kerjasama Bidang K3 di Fakultas Ilmu Kesehatan',
                    'tanggal_waktu_berlaku' => '2025-01-18',
                    'tanggal_akhir_berlaku' => '2029-01-18',
                    'pihak1_name'             => 'Dekan FIKes',
                    'pihak1_jabatan'          => 'Dekan',
                    'pihak2_name'             => 'Ketua Umum A2K3',
                    'pihak2_jabatan'          => 'Ketua',
                ],
                [
                    'nomor_dokumen_kerjasama' => '006/FIKes.D/KP/I/2025',
                    'nomor_dokumen_mitra'     => '',
                    'jenis_dokumen_id'        => $ia->id,
                    'unit_kerja_id'           => $k3->id,
                    'mitra_id'                => $a2k3->id,
                    'judul_kerjasama'         => '-',
                    'deskripsi_kerjasama'     => 'Praktek Lapangan mahasiswa K3',
                    'tanggal_waktu_berlaku' => '2025-01-18',
                    'tanggal_akhir_berlaku' => '2025-01-18',
                    'pihak1_name'             => 'Kaprodi K3',
                    'pihak1_jabatan'          => 'Kaprodi',
                    'pihak2_name'             => 'Ketua Umum A2K3',
                    'pihak2_jabatan'          => 'Ketua',
                ],
                [
                    'nomor_dokumen_kerjasama' => '024/UIS.R/KS/I/2025',
                    'nomor_dokumen_mitra'     => '',
                    'jenis_dokumen_id'        => $mou->id,
                    'unit_kerja_id'           => $uis->id,
                    'mitra_id'                => $a2k3->id,
                    'judul_kerjasama'         => 'Tri Dharma Perguruan Tinggi',
                    'deskripsi_kerjasama'     => 'MoU Universitas Ibnu Sina dengan A2K3',
                    'tanggal_waktu_berlaku' => '2025-01-18',
                    'tanggal_akhir_berlaku' => '2029-01-18',
                    'pihak1_name'             => 'Rektor UIS',
                    'pihak1_jabatan'          => 'Rektor',
                    'pihak2_name'             => 'Ketua Umum A2K3',
                    'pihak2_jabatan'          => 'Ketua',
                ],
            ];

            foreach ($items as $val) {
                $kerjasama = Kerjasama::create([
                    'nomor_dokumen_kerjasama' => $val['nomor_dokumen_kerjasama'],
                    'nomor_dokumen_mitra'     => $val['nomor_dokumen_mitra'],
                    'jenis_dokumen_id'      => $val['jenis_dokumen_id'],
                    'unit_kerja_id'         => $val['unit_kerja_id'],
                    'mitra_id'              => $val['mitra_id'],
                    'judul_kerjasama'       => $val['judul_kerjasama'],
                    'deskripsi_kerjasama'   => $val['deskripsi_kerjasama'],
                    'sumber_dana_id'        => $sumberDana->id,
                    'anggaran'              => 0,
                    'tanggal_waktu_berlaku' => $val['tanggal_waktu_berlaku'],
                    'tanggal_akhir_berlaku' => $val['tanggal_akhir_berlaku'],
                    'status_kerjasama'      => 'Aktif',
                    'status'                => 'active',
                ]);

                // Pihak 1
                $pihak1 = KerjasamaPihak::create([
                    'kerjasama_id' => $kerjasama->id,
                    'pihak_ke'     => '1',
                    'jenis_pihak'  => 'Unit',
                    'alamat'       => 'Jl. Teuku Umar Lubuk Baja Batam',
                ]);
                KerjasamaPenanggungJawab::create([
                    'kerjasama_pihak_id' => $pihak1->id,
                    'nama'               => $val['pihak1_name'],
                    'nip'                => '',
                    'jabatan'            => $val['pihak1_jabatan'],
                    'nomor_hp'           => '',
                    'email'              => '',
                ]);

                // Pihak 2
                $pihak2 = KerjasamaPihak::create([
                    'kerjasama_id' => $kerjasama->id,
                    'pihak_ke'     => '2',
                    'jenis_pihak'  => 'Mitra',
                    'alamat'       => 'Alamat Mitra',
                ]);
                KerjasamaPenanggungJawab::create([
                    'kerjasama_pihak_id' => $pihak2->id,
                    'nama'               => $val['pihak2_name'],
                    'nip'                => '',
                    'jabatan'            => $val['pihak2_jabatan'],
                    'nomor_hp'           => '',
                    'email'              => '',
                ]);
            }
        });

        $this->command->info('KerjasamaSeeder: 6 data kerjasama sesuai screenshot berhasil di-seed.');
    }
}
