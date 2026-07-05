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
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KegiatanImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $firstRow = $rows->first();
        if (
            $firstRow && (
            str_contains(strtolower($firstRow[0] ?? ''), 'kerjasama') ||
            str_contains(strtolower($firstRow[0] ?? ''), 'induk'))
        ) {
            $rows = $rows->slice(1);
        }

        foreach ($rows as $row) {
            if (empty($row[8])) {
                continue; // skip if no judul_kegiatan
            }

            // 0: Induk Kerjasama
            $kerjasamaId = null;
            $kerjasamaText = trim($row[0] ?? '');
            if (!empty($kerjasamaText)) {
                $kerjasama = Kerjasama::where('judul_kerjasama', 'like', '%' . $kerjasamaText . '%')->first();
                $kerjasamaId = $kerjasama ? $kerjasama->id : Kerjasama::first()?->id;
            }
            if (!$kerjasamaId) {
                $kerjasamaId = Kerjasama::first()?->id ?? 1;
            }

            // 1: Unit Kerja
            $unitKerjaText = trim($row[1] ?? '');
            $unitKerjaId = null;
            if (!empty($unitKerjaText)) {
                $unitKerja = UnitKerja::firstOrCreate(['nama_unit_kerja' => $unitKerjaText]);
                $unitKerjaId = $unitKerja->id;
            }
            if (!$unitKerjaId) {
                $unitKerjaId = UnitKerja::first()?->id ?? 1;
            }

            // 2: Mitra
            $mitraText = trim($row[2] ?? '');
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
                    ]
                );
                $mitraId = $mitra->id;
            }
            if (!$mitraId) {
                $mitraId = Mitra::first()?->id ?? 1;
            }

            // 3: Sasaran Kinerja
            $sasaranText = trim($row[3] ?? '');
            $sasaranId = null;
            if (!empty($sasaranText)) {
                $sasaran = SasaranKinerja::firstOrCreate(['sasaran_kinerja' => $sasaranText]);
                $sasaranId = $sasaran->id;
            }
            if (!$sasaranId) {
                $sasaranId = SasaranKinerja::first()?->id ?? 1;
            }

            // 4: Bentuk Kegiatan
            $bentukText = trim($row[4] ?? '');
            $bentukId = null;
            if (!empty($bentukText)) {
                $bentuk = BentukKegiatan::firstOrCreate(
                    ['nama_bentuk_kegiatan' => $bentukText],
                    ['jenis_kegiatan' => 'Pendidikan']
                );
                $bentukId = $bentuk->id;
            }
            if (!$bentukId) {
                $bentukId = BentukKegiatan::first()?->id ?? 1;
            }

            // 5: Indikator Sasaran
            $indikatorText = trim($row[5] ?? '');
            $indikatorId = null;
            if (!empty($indikatorText)) {
                $indikator = IndikatorSasaran::firstOrCreate(
                    ['indikator_sasaran' => $indikatorText],
                    ['sasaran_kinerja_id' => $sasaranId]
                );
                $indikatorId = $indikator->id;
            }
            if (!$indikatorId) {
                $indikatorId = IndikatorSasaran::first()?->id ?? 1;
            }

            // Dates
            $tanggalAwal = $this->parseExcelDate($row[9] ?? null) ?? Carbon::now();
            $tanggalAkhir = $this->parseExcelDate($row[10] ?? null) ?? Carbon::now()->addYear();

            // Nilai Kontrak
            $nilaiKontrak = 0;
            if (isset($row[13])) {
                $clean = preg_replace('/[^0-9]/', '', $row[13]);
                $nilaiKontrak = is_numeric($clean) ? (int) $clean : 0;
            }

            Kegiatan::create([
                'kerjasama_id'            => $kerjasamaId,
                'unit_kerja_id'           => $unitKerjaId,
                'mitra_id'                => $mitraId,
                'sasaran_kinerja_id'      => $sasaranId,
                'bentuk_kegiatan_id'      => $bentukId,
                'indikator_id'            => $indikatorId,
                'nomor_dokumen_kegiatan'  => $row[6] ?? null,
                'nomor_dokumen_mitra'     => $row[7] ?? null,
                'judul_kegiatan'          => $row[8] ?? '-',
                'tanggal_awal_kegiatan'   => $tanggalAwal,
                'tanggal_akhir_kegiatan'  => $tanggalAkhir,
                'ruang_lingkup'           => $row[11] ?? null,
                'hasil_pelakasanaan'      => $row[12] ?? null,
                'nilai_kontrak'           => $nilaiKontrak,
                'link_dokumen_kegiatan'   => $row[14] ?? null,
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
