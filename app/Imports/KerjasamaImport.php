<?php

namespace App\Imports;

use App\Models\Kerjasama;
use App\Models\JenisDokumen;
use App\Models\UnitKerja;
use App\Models\Mitra;
use App\Models\SumberDana;
use App\Models\KriteriaMitra;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KerjasamaImport implements ToCollection
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
            if (empty($row[0]) && empty($row[5])) {
                continue;
            }

            // 1. Jenis Dokumen
            $jenisDokumenText = trim($row[2] ?? '');
            $jenisDokumenId = null;
            if (!empty($jenisDokumenText)) {
                $jenisDokumen = JenisDokumen::firstOrCreate(
                    ['nama_jenis_dokumen' => $jenisDokumenText]
                );
                $jenisDokumenId = $jenisDokumen->id;
            }

            // 2. Unit Kerja
            $unitKerjaText = trim($row[3] ?? '');
            $unitKerjaId = null;
            if (!empty($unitKerjaText)) {
                $unitKerja = UnitKerja::firstOrCreate(
                    ['nama_unit_kerja' => $unitKerjaText]
                );
                $unitKerjaId = $unitKerja->id;
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
            }

            // 4. Sumber Dana
            $sumberDanaText = trim($row[7] ?? '');
            $sumberDanaId = null;
            if (!empty($sumberDanaText)) {
                $sumberDana = SumberDana::firstOrCreate(
                    ['nama_sumber_dana' => $sumberDanaText]
                );
                $sumberDanaId = $sumberDana->id;
            }

            // Parse Dates
            $tanggalAwal = $this->parseExcelDate($row[9]);
            $tanggalAkhir = $this->parseExcelDate($row[10]);

            // Default fallback dates if parsing failed
            if (!$tanggalAwal) {
                $tanggalAwal = Carbon::now();
            }
            if (!$tanggalAkhir) {
                $tanggalAkhir = Carbon::now()->addYears(5);
            }

            // Clean Budget / Anggaran
            $anggaran = 0;
            if (isset($row[8])) {
                $cleanAnggaran = preg_replace('/[^0-9]/', '', $row[8]);
                $anggaran = is_numeric($cleanAnggaran) ? (int)$cleanAnggaran : 0;
            }

            // Insert Kerjasama
            Kerjasama::create([
                'nomor_dokumen_kerjasama' => $row[0] ?? '-',
                'nomor_dokumen_mitra'     => $row[1] ?? null,
                'jenis_dokumen_id'        => $jenisDokumenId ?? 1,
                'mitra_id'                => $mitraId ?? 1,
                'unit_kerja_id'           => $unitKerjaId ?? 1,
                'judul_kerjasama'         => $row[5] ?? '-',
                'deskripsi_kerjasama'     => $row[6] ?? '-',
                'sumber_dana_id'          => $sumberDanaId ?? 1,
                'anggaran'                => $anggaran,
                'tanggal_waktu_berlaku'   => $tanggalAwal,
                'tanggal_akhir_berlaku'   => $tanggalAkhir,
                'status_kerjasama'        => !empty($row[11]) ? trim($row[11]) : 'Aktif',
                'status'                  => 'Aktif', // Required field
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
