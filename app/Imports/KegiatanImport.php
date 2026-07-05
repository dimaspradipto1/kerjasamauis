<?php

namespace App\Imports;

use App\Models\Kegiatan;
use App\Models\Kerjasama;
use App\Models\UnitKerja;
use App\Models\Mitra;
use App\Models\SasaranKinerja;
use App\Models\BentukKegiatan;
use App\Models\IndikatorSasaran;
use App\Models\KriteriaMitra;
use App\Models\JenisDokumen;
use App\Models\SumberDana;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KegiatanImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Skip header row if it contains column labels
        $firstRow = $rows->first();
        if ($firstRow && (
            str_contains(strtolower($firstRow[0] ?? ''), 'dokumen') || 
            str_contains(strtolower($firstRow[0] ?? ''), 'nomor') ||
            str_contains(strtolower($firstRow[0] ?? ''), 'no')
        )) {
            $rows = $rows->slice(1);
        }

        foreach ($rows as $row) {
            // Skip empty rows
            if (empty($row[0]) && empty($row[8])) {
                continue;
            }

            // 1. Induk Kerjasama
            $indukKerjasamaText = trim($row[2] ?? '');
            $kerjasamaId = null;
            if (!empty($indukKerjasamaText)) {
                $kerjasama = Kerjasama::where('nomor_dokumen_kerjasama', $indukKerjasamaText)
                    ->orWhere('judul_kerjasama', 'LIKE', '%' . $indukKerjasamaText . '%')
                    ->first();
                
                if (!$kerjasama) {
                    // Create stub Kerjasama if not found
                    $jenisDokumenId = \App\Models\JenisDokumen::first()?->id ?? 1;
                    $mitraId = \App\Models\Mitra::first()?->id ?? 1;
                    $unitKerjaId = \App\Models\UnitKerja::first()?->id ?? 1;
                    $sumberDanaId = \App\Models\SumberDana::first()?->id ?? 1;
                    
                    $kerjasama = Kerjasama::create([
                        'nomor_dokumen_kerjasama' => $indukKerjasamaText,
                        'nomor_dokumen_mitra'     => '-',
                        'jenis_dokumen_id'        => $jenisDokumenId,
                        'mitra_id'                => $mitraId,
                        'unit_kerja_id'           => $unitKerjaId,
                        'judul_kerjasama'         => 'Kerjasama dari Import Kegiatan: ' . $indukKerjasamaText,
                        'deskripsi_kerjasama'     => 'Auto generated Kerjasama from Kegiatan Import.',
                        'sumber_dana_id'          => $sumberDanaId,
                        'anggaran'                => 0,
                        'tanggal_waktu_berlaku'   => Carbon::now(),
                        'tanggal_akhir_berlaku'   => Carbon::now()->addYears(5),
                        'status_kerjasama'        => 'Aktif',
                        'status'                  => 'Aktif',
                    ]);
                }
                $kerjasamaId = $kerjasama->id;
            } else {
                // fallback to first kerjasama
                $kerjasamaId = Kerjasama::first()?->id;
                if (!$kerjasamaId) {
                    // if no kerjasama exists at all
                    $jenisDokumenId = \App\Models\JenisDokumen::first()?->id ?? 1;
                    $mitraId = \App\Models\Mitra::first()?->id ?? 1;
                    $unitKerjaId = \App\Models\UnitKerja::first()?->id ?? 1;
                    $sumberDanaId = \App\Models\SumberDana::first()?->id ?? 1;
                    
                    $kerjasama = Kerjasama::create([
                        'nomor_dokumen_kerjasama' => 'KS-IMPORT-' . time(),
                        'nomor_dokumen_mitra'     => '-',
                        'jenis_dokumen_id'        => $jenisDokumenId,
                        'mitra_id'                => $mitraId,
                        'unit_kerja_id'           => $unitKerjaId,
                        'judul_kerjasama'         => 'Default Kerjasama Import',
                        'deskripsi_kerjasama'     => 'Auto generated default Kerjasama from Kegiatan Import.',
                        'sumber_dana_id'          => $sumberDanaId,
                        'anggaran'                => 0,
                        'tanggal_waktu_berlaku'   => Carbon::now(),
                        'tanggal_akhir_berlaku'   => Carbon::now()->addYears(5),
                        'status_kerjasama'        => 'Aktif',
                        'status'                  => 'Aktif',
                    ]);
                    $kerjasamaId = $kerjasama->id;
                }
            }

            // 2. Unit Kerja Pengusul
            $unitKerjaText = trim($row[3] ?? '');
            $unitKerjaId = null;
            if (!empty($unitKerjaText)) {
                $unitKerja = UnitKerja::firstOrCreate(
                    ['nama_unit_kerja' => $unitKerjaText]
                );
                $unitKerjaId = $unitKerja->id;
            } else {
                $unitKerjaId = UnitKerja::first()?->id ?? 1;
            }

            // 3. Mitra
            $mitraText = trim($row[4] ?? '');
            $mitraId = null;
            if (!empty($mitraText)) {
                $kriteriaId = KriteriaMitra::first()?->id ?? 1;
                $mitra = Mitra::firstOrCreate(
                    ['nama_mitra' => $mitraText],
                    [
                        'jenis_mitra' => 'Lainnya',
                        'kriteria_mitra_id' => $kriteriaId,
                        'lingkup_mitra' => 'Lokal',
                        'provinsi' => '-',
                        'kabupaten_kota' => '-',
                        'kecamatan' => '-',
                        'alamat' => '-'
                    ]
                );
                $mitraId = $mitra->id;
            } else {
                $mitraId = Mitra::first()?->id ?? 1;
            }

            // 4. Sasaran Kinerja
            $sasaranText = trim($row[5] ?? '');
            $sasaranId = null;
            if (!empty($sasaranText)) {
                $sasaran = SasaranKinerja::firstOrCreate(
                    ['sasaran_kinerja' => $sasaranText],
                    ['level' => 'Prioritas', 'keterangan' => '-']
                );
                $sasaranId = $sasaran->id;
            } else {
                $sasaranId = SasaranKinerja::first()?->id ?? 1;
            }

            // 5. Bentuk Kegiatan
            $bentukText = trim($row[6] ?? '');
            $bentukId = null;
            if (!empty($bentukText)) {
                $bentuk = BentukKegiatan::firstOrCreate(
                    ['nama_bentuk_kegiatan' => $bentukText],
                    ['jenis_kegiatan' => 'Pendidikan', 'keterangan' => '-']
                );
                $bentukId = $bentuk->id;
            } else {
                $bentukId = BentukKegiatan::first()?->id ?? 1;
            }

            // 6. Indikator Sasaran
            $indikatorText = trim($row[7] ?? '');
            $indikatorId = null;
            if (!empty($indikatorText)) {
                $indikator = IndikatorSasaran::firstOrCreate(
                    [
                        'indikator_sasaran' => $indikatorText,
                        'sasaran_kinerja_id' => $sasaranId
                    ],
                    [
                        'keterangan' => '-',
                        'volume' => 1,
                        'satuan' => 'Dokumen'
                    ]
                );
                $indikatorId = $indikator->id;
            } else {
                // Fetch first indicator under the sasaran, or create a default one
                $indikator = IndikatorSasaran::where('sasaran_kinerja_id', $sasaranId)->first();
                if (!$indikator) {
                    $indikator = IndikatorSasaran::create([
                        'sasaran_kinerja_id' => $sasaranId,
                        'indikator_sasaran' => 'Indikator Default ' . $sasaranText,
                        'keterangan' => '-',
                        'volume' => 1,
                        'satuan' => 'Dokumen'
                    ]);
                }
                $indikatorId = $indikator->id;
            }

            // Parse Dates
            $tanggalAwal = $this->parseExcelDate($row[9]);
            $tanggalAkhir = $this->parseExcelDate($row[10]);

            // Default fallback dates if parsing failed
            if (!$tanggalAwal) {
                $tanggalAwal = Carbon::now();
            }
            if (!$tanggalAkhir) {
                $tanggalAkhir = Carbon::now()->addMonths(12);
            }

            // Clean Budget / Nilai Kontrak
            $nilaiKontrak = 0;
            if (isset($row[11])) {
                $cleanNilai = preg_replace('/[^0-9]/', '', $row[11]);
                $nilaiKontrak = is_numeric($cleanNilai) ? (int)$cleanNilai : 0;
            }

            // Insert Kegiatan
            Kegiatan::create([
                'kerjasama_id'           => $kerjasamaId,
                'unit_kerja_id'          => $unitKerjaId,
                'mitra_id'               => $mitraId,
                'sasaran_kinerja_id'     => $sasaranId,
                'bentuk_kegiatan_id'     => $bentukId,
                'indikator_id'           => $indikatorId,
                'nomor_dokumen_kegiatan' => $row[0] ?? '-',
                'nomor_dokumen_mitra'    => $row[1] ?? null,
                'judul_kegiatan'         => $row[8] ?? '-',
                'tanggal_awal_kegiatan'  => $tanggalAwal,
                'tanggal_akhir_kegiatan' => $tanggalAkhir,
                'ruang_lingkup'          => $row[12] ?? null,
                'hasil_pelakasanaan'     => $row[13] ?? null,
                'nilai_kontrak'          => $nilaiKontrak,
            ]);
        }
    }

    private function parseExcelDate($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value);
            } catch (\Exception $e) {
                // fall through
            }
        }

        try {
            return Carbon::parse(str_replace('/', '-', $value));
        } catch (\Exception $e) {
            return null;
        }
    }
}
