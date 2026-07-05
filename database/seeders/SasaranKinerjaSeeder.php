<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SasaranKinerja;
use App\Models\IndikatorSasaran;

class SasaranKinerjaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'sasaran_kinerja' => 'Meningkatnya inovasi perguruan tinggi dalam rangka meningkatkan mutu pendidikan',
                'keterangan'      => null,
                'level'           => 'Prioritas',
                'indikator'       => [
                    [
                        'indikator_sasaran' => 'Link and match PTS',
                        'keterangan'        => 'Persentase PTS yang berhasil meningkatkan kinerja dengan meningkatkan jumlah dosen yang berkegiatan tridarma di luar kampus dan jumlah program studi yang bekerjasama dengan mitra',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                ],
            ],
            [
                'sasaran_kinerja' => 'Meningkatnya kualitas dosen pendidikan tinggi',
                'keterangan'      => null,
                'level'           => 'Prioritas',
                'indikator'       => [
                    [
                        'indikator_sasaran' => 'Dosen di luar kampus',
                        'keterangan'        => 'Persentase dosen yang berkegiatan tridarma di kampus lain, di QS100 berdasarkan bidang ilmu (QS100 by subject), bekerja sebagai praktisi di dunia industri, atau membina mahasiswa yang berhasil meraih prestasi paling rendah tingkat nasional dalam 5 tahun terakhir.',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                    [
                        'indikator_sasaran' => 'Kualifikasi dosen',
                        'keterangan'        => 'Persentase dosen tetap: a. berkualifikasi akademik S3; b. memiliki sertifikat kompetensi/profesi yang diakui oleh industri dan dunia kerja; c. berasal dari kalangan praktisi profesional, dunia industri, atau dunia kerja.',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                    [
                        'indikator_sasaran' => 'Penerapan riset dosen',
                        'keterangan'        => 'Jumlah keluaran penelitian dan pengabdian kepada masyarakat yang berhasil mendapat rekognisi internasional atau diterapkan oleh masyarakat per jumlah dosen.',
                        'volume'            => 0,
                        'satuan'            => 'Hasil Penelitian',
                    ],
                ],
            ],
            [
                'sasaran_kinerja' => 'Meningkatnya kualitas kurikulum dan pembelajaran',
                'keterangan'      => null,
                'level'           => 'Prioritas',
                'indikator'       => [
                    [
                        'indikator_sasaran' => 'Kemitraan program studi',
                        'keterangan'        => 'Persentase program studi S1 dan D4/D3/D2 yang melaksanakan kerja sama dengan mitra.',
                        'volume'            => 0,
                        'satuan'            => 'Kerjasama',
                    ],
                    [
                        'indikator_sasaran' => 'Pembelajaran dalam kelas',
                        'keterangan'        => 'Persentase mata kuliah S1 dan D4/D3/D2 yang menggunakan metode pembelajaran pemecahan kasus (case method) atau pembelajaran kelompok berbasis proyek (team-based project) sebagai sebagian bobot evaluasi.',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                    [
                        'indikator_sasaran' => 'Akreditasi Internasional',
                        'keterangan'        => 'Persentase program studi S1 dan D4/D3/D2 yang memiliki akreditasi atau sertifikat internasional yang diakui pemerintah.',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                ],
            ],
            [
                'sasaran_kinerja' => 'Meningkatnya kualitas lulusan pendidikan tinggi',
                'keterangan'      => null,
                'level'           => 'Prioritas',
                'indikator'       => [
                    [
                        'indikator_sasaran' => 'Lulusan berkegiatan di luar kampus',
                        'keterangan'        => 'Persentase lulusan S1 dan D4/D3/D2 yang berhasil mendapat pekerjaan; melanjutkan studi; atau menjadi wiraswasta.',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                    [
                        'indikator_sasaran' => 'Mahasiswa berprestasi',
                        'keterangan'        => 'Persentase mahasiswa S1 dan D4/D3/D2 yang menghabiskan paling sedikit 20 (dua puluh) SKS di luar kampus; atau meraih prestasi paling rendah tingkat nasional.',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                ],
            ],
            [
                'sasaran_kinerja' => 'Meningkatnya program studi yang berkualitas',
                'keterangan'      => null,
                'level'           => 'Prioritas Kementrian',
                'indikator'       => [
                    [
                        'indikator_sasaran' => 'Program studi terakreditasi internasional',
                        'keterangan'        => 'Jumlah program studi yang terakreditasi atau tersertifikasi internasional.',
                        'volume'            => 0,
                        'satuan'            => null,
                    ],
                ],
            ],
        ];

        foreach ($data as $item) {
            $sasaran = SasaranKinerja::firstOrCreate(
                ['sasaran_kinerja' => $item['sasaran_kinerja']],
                [
                    'keterangan' => $item['keterangan'],
                    'level'      => $item['level'],
                ]
            );

            foreach ($item['indikator'] as $ind) {
                IndikatorSasaran::firstOrCreate(
                    [
                        'sasaran_kinerja_id' => $sasaran->id,
                        'indikator_sasaran'  => $ind['indikator_sasaran'],
                    ],
                    [
                        'keterangan' => $ind['keterangan'],
                        'volume'     => $ind['volume'],
                        'satuan'     => $ind['satuan'],
                    ]
                );
            }
        }

        $totalIndikator = collect($data)->sum(fn($d) => count($d['indikator']));
        $this->command->info('SasaranKinerjaSeeder: ' . count($data) . ' sasaran kinerja, ' . $totalIndikator . ' indikator berhasil di-seed.');
    }
}
