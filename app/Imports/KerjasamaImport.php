<?php

namespace App\Imports;

use App\Models\Kerjasama;
use App\Models\JenisDokumen;
use App\Models\UnitKerja;
use App\Models\Mitra;
use App\Models\SumberDana;
use App\Models\KriteriaMitra;
use App\Models\KerjasamaPihak;
use App\Models\KerjasamaPenanggungJawab;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KerjasamaImport implements ToCollection, WithHeadingRow
{
    protected int $importedCount = 0;

    /**
     * Return total baris yang berhasil di-import.
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Konversi row collection ke array
            $data = is_array($row) ? $row : (method_exists($row, 'toArray') ? $row->toArray() : (array)$row);

            // Ekstrak nilai berdasarkan slug heading atau fallback index (Format 12 Kolom)
            $nomorDokumenKerjasama = trim($this->getValue($data, ['nomor_dokumen_kerjasama', 'nomor_dokumen', 'no_dokumen', 0]) ?? '');
            $nomorDokumenMitra    = trim($this->getValue($data, ['nomor_dokumen_mitra', 'no_dokumen_mitra', 1]) ?? '');
            $jenisDokumenText     = trim($this->getValue($data, ['jenis_dokumen', 2]) ?? '');
            $unitKerjaText        = trim($this->getValue($data, ['unit_kerja', 3, 4]) ?? '');
            $mitraText            = trim($this->getValue($data, ['mitra', 'nama_mitra', 4, 5]) ?? '');
            $judulKerjasama        = trim($this->getValue($data, ['judul_kerjasama', 'judul', 5, 6]) ?? '');
            $deskripsiKerjasama   = trim($this->getValue($data, ['deskripsi_kerjasama', 'deskripsi', 6, 7]) ?? '');
            $sumberDanaText       = trim($this->getValue($data, ['sumber_dana', 7, 9]) ?? '');
            $anggaranRaw          = $this->getValue($data, ['anggaran', 8, 10]);
            $tanggalAwalRaw       = $this->getValue($data, ['tanggal_awal_berlaku', 'tanggal_awal', 'tgl_awal', 9, 11]);
            $tanggalAkhirRaw      = $this->getValue($data, ['tanggal_akhir_berlaku', 'tanggal_akhir', 'tgl_akhir', 10, 12]);
            $statusKerjasamaText  = trim($this->getValue($data, ['status_kerjasama', 'status', 11, 13]) ?? '');

            // Lewati jika seluruh kolom utama kosong
            if (empty($nomorDokumenKerjasama) && empty($judulKerjasama) && empty($mitraText)) {
                continue;
            }

            // Jika judul kosong, berikan judul default
            if (empty($judulKerjasama)) {
                $judulKerjasama = 'Kerjasama dengan ' . (!empty($mitraText) ? $mitraText : 'Mitra');
            }

            // 1. Resolve Jenis Dokumen (Nullable -> default MoU/first)
            $jenisDokumenId = $this->resolveJenisDokumen($jenisDokumenText);

            // 2. Resolve Mitra (Nullable -> default Mitra Kerjasama)
            $mitraId = $this->resolveMitra($mitraText);

            // 3. Resolve Multi Unit Kerja (Nullable -> default first unit kerja)
            $unitKerjaIds = $this->resolveUnitKerjas($unitKerjaText);

            // 4. Resolve Sumber Dana (Nullable -> default Mandiri/first sumber dana)
            $sumberDanaId = $this->resolveSumberDana($sumberDanaText);

            // 5. Parse Dates (Nullable -> fallback aman dengan objek Carbon)
            $parsedAwal  = $this->parseExcelDate($tanggalAwalRaw);
            $tanggalAwal = $parsedAwal ? Carbon::instance($parsedAwal) : Carbon::now();

            $parsedAkhir  = $this->parseExcelDate($tanggalAkhirRaw);
            $tanggalAkhir = $parsedAkhir ? Carbon::instance($parsedAkhir) : (clone $tanggalAwal)->addYears(5);

            // 6. Parse Anggaran (Nullable -> default 0)
            $anggaran = 0;
            if (!empty($anggaranRaw)) {
                $cleanAnggaran = preg_replace('/[^0-9]/', '', (string)$anggaranRaw);
                $anggaran = is_numeric($cleanAnggaran) ? (int)$cleanAnggaran : 0;
            }

            // 7. Status Kerjasama (Nullable -> default Aktif)
            $statusKerjasama = !empty($statusKerjasamaText) ? $statusKerjasamaText : 'Aktif';

            // 8. Tentukan Nomor Dokumen & Kondisi Pencarian (agar nullable tidak duplikat saat import berulang)
            if (!empty($nomorDokumenKerjasama)) {
                $nomorFinal = $nomorDokumenKerjasama;
                $matchConditions = ['nomor_dokumen_kerjasama' => $nomorFinal];
            } elseif (!empty($nomorDokumenMitra)) {
                $nomorFinal = $nomorDokumenMitra;
                $matchConditions = ['nomor_dokumen_kerjasama' => $nomorFinal];
            } else {
                // Generate nomor unik berdasarkan tahun & judul agar data tetap teridentifikasi rapi
                $nomorFinal = 'DOK-' . $tanggalAwal->format('Y') . '-' . strtoupper(substr(md5($judulKerjasama . $mitraId), 0, 8));
                $matchConditions = [
                    'judul_kerjasama' => $judulKerjasama,
                    'mitra_id'        => $mitraId,
                ];
            }

            // 9. Simpan / Update Kerjasama
            // File upload fisik (url_file) sengaja dibiarkan null / kosong
            $kerjasama = Kerjasama::updateOrCreate(
                $matchConditions,
                [
                    'nomor_dokumen_kerjasama' => $nomorFinal,
                    'nomor_dokumen_mitra'     => !empty($nomorDokumenMitra) ? $nomorDokumenMitra : null,
                    'jenis_dokumen_id'        => $jenisDokumenId,
                    'mitra_id'                => $mitraId,
                    'unit_kerja_id'           => $unitKerjaIds[0] ?? null,
                    'judul_kerjasama'         => $judulKerjasama,
                    'deskripsi_kerjasama'     => !empty($deskripsiKerjasama) ? $deskripsiKerjasama : '-',
                    'sumber_dana_id'          => $sumberDanaId,
                    'anggaran'                => $anggaran,
                    'tanggal_waktu_berlaku'   => $tanggalAwal,
                    'tanggal_akhir_berlaku'   => $tanggalAkhir,
                    'status_kerjasama'        => $statusKerjasama,
                    'url_file'                => null, // Nullable: upload file fisik dapat dilakukan menyusul
                    'status'                  => 'active',
                ]
            );

            // Sync multi unit kerja ke pivot table
            if (!empty($unitKerjaIds)) {
                $kerjasama->unitKerjas()->sync($unitKerjaIds);
            }

            // Pastikan Pihak 1 (Unit UIS) dan Pihak 2 (Mitra) ada untuk kompatibilitas tampilan detail & edit
            $this->ensurePihakDanPenanggungJawab($kerjasama, $mitraText);

            $this->importedCount++;
        }
    }

    /**
     * Ambil nilai dari array dengan beberapa kemungkinan key / index.
     */
    protected function getValue(array $data, array $keys)
    {
        foreach ($keys as $k) {
            if (array_key_exists($k, $data) && $data[$k] !== null && $data[$k] !== '') {
                return $data[$k];
            }
        }
        return null;
    }

    /**
     * Resolve ID Jenis Dokumen (MoU, MoA, IA, atau teks bebas).
     */
    protected function resolveJenisDokumen(string $text): int
    {
        if (empty($text)) {
            return JenisDokumen::first()?->id ?? 1;
        }

        $lower = strtolower($text);

        if (str_contains($lower, 'ia') || str_contains($lower, 'arrangement')) {
            $jd = JenisDokumen::where('nama_jenis_dokumen', 'like', '%Arrangement%')
                ->orWhere('nama_jenis_dokumen', 'like', '%IA%')
                ->first();
            if ($jd) return $jd->id;
        }

        if (str_contains($lower, 'moa') || str_contains($lower, 'agreement')) {
            $jd = JenisDokumen::where('nama_jenis_dokumen', 'like', '%Agreement%')
                ->orWhere('nama_jenis_dokumen', 'like', '%MoA%')
                ->first();
            if ($jd) return $jd->id;
        }

        if (str_contains($lower, 'mou') || str_contains($lower, 'understanding')) {
            $jd = JenisDokumen::where('nama_jenis_dokumen', 'like', '%Understanding%')
                ->orWhere('nama_jenis_dokumen', 'like', '%MoU%')
                ->first();
            if ($jd) return $jd->id;
        }

        $jd = JenisDokumen::firstOrCreate(['nama_jenis_dokumen' => $text]);
        return $jd->id;
    }

    /**
     * Resolve ID Mitra (cari berdasarkan nama atau buat baru).
     */
    protected function resolveMitra(string $text): int
    {
        if (empty($text)) {
            $default = Mitra::first();
            if ($default) return $default->id;
            $text = 'Mitra Kerjasama';
        }

        $mitra = Mitra::whereRaw('LOWER(nama_mitra) = ?', [strtolower($text)])->first();
        if ($mitra) {
            return $mitra->id;
        }

        $kriteriaId = KriteriaMitra::first()?->id ?? 1;
        $mitra = Mitra::create([
            'nama_mitra'        => $text,
            'jenis_mitra'       => 'Lainnya',
            'kriteria_mitra_id' => $kriteriaId,
            'lingkup_mitra'     => 'Nasional',
            'provinsi'          => '-',
            'kabupaten_kota'    => '-',
            'kecamatan'         => '-',
            'alamat'            => '-',
        ]);

        return $mitra->id;
    }

    /**
     * Resolve Multi Unit Kerja (bisa dipisahkan koma, titik koma, atau baris baru).
     * Mendukung nomor urut seperti "1. S1 - Teknik Industri, 2. S1 - Teknik Informatika".
     */
    protected function resolveUnitKerjas(string $text): array
    {
        if (empty($text)) {
            $default = UnitKerja::first();
            return $default ? [$default->id] : [1];
        }

        $parts = preg_split('/[,;\n\r]+/', $text);
        $ids = [];

        foreach ($parts as $part) {
            $clean = trim($part);
            if ($clean === '') continue;

            // Bersihkan nomor urut di awal seperti "1. ", "2) ", "- "
            $clean = preg_replace('/^(\d+[\.\)]\s*|-\s*)/', '', $clean);
            $clean = trim($clean);

            if ($clean === '') continue;

            $unit = UnitKerja::whereRaw('LOWER(nama_unit_kerja) = ?', [strtolower($clean)])->first();
            if (!$unit) {
                $unit = UnitKerja::firstOrCreate(['nama_unit_kerja' => $clean]);
            }

            if (!in_array($unit->id, $ids)) {
                $ids[] = $unit->id;
            }
        }

        return !empty($ids) ? $ids : [UnitKerja::first()?->id ?? 1];
    }

    /**
     * Resolve Sumber Dana.
     */
    protected function resolveSumberDana(string $text): int
    {
        if (empty($text)) {
            $default = SumberDana::first();
            if ($default) return $default->id;
            $text = 'Mandiri';
        }

        $sd = SumberDana::whereRaw('LOWER(nama_sumber_dana) = ?', [strtolower($text)])->first();
        if (!$sd) {
            $sd = SumberDana::firstOrCreate(['nama_sumber_dana' => $text]);
        }

        return $sd->id;
    }

    /**
     * Pastikan Pihak 1 (Unit) dan Pihak 2 (Mitra) ada untuk kerjasama.
     */
    protected function ensurePihakDanPenanggungJawab(Kerjasama $kerjasama, string $mitraName): void
    {
        $hasPihak1 = $kerjasama->kerjasamaPihaks()->where('pihak_ke', '1')->exists();
        if (!$hasPihak1) {
            $pihak1 = KerjasamaPihak::create([
                'kerjasama_id' => $kerjasama->id,
                'pihak_ke'     => '1',
                'jenis_pihak'  => 'Unit',
                'alamat'       => 'Universitas Ibnu Sina Batam',
            ]);
            KerjasamaPenanggungJawab::create([
                'kerjasama_pihak_id' => $pihak1->id,
                'nama'               => 'Pimpinan Unit Kerja UIS',
                'nip'                => '',
                'jabatan'            => 'Penanggung Jawab',
                'nomor_hp'           => '',
                'email'              => '',
            ]);
        }

        $hasPihak2 = $kerjasama->kerjasamaPihaks()->where('pihak_ke', '2')->exists();
        if (!$hasPihak2) {
            $pihak2 = KerjasamaPihak::create([
                'kerjasama_id' => $kerjasama->id,
                'pihak_ke'     => '2',
                'jenis_pihak'  => 'Mitra',
                'alamat'       => $kerjasama->mitra?->alamat ?? '-',
            ]);
            KerjasamaPenanggungJawab::create([
                'kerjasama_pihak_id' => $pihak2->id,
                'nama'               => !empty($mitraName) ? "Perwakilan {$mitraName}" : 'PIC Mitra',
                'nip'                => '',
                'jabatan'            => 'PIC Mitra',
                'nomor_hp'           => '',
                'email'              => '',
            ]);
        }
    }

    /**
     * Parse format tanggal dari Excel ke Carbon instance.
     */
    protected function parseExcelDate($value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            try {
                $dt = Date::excelToDateTimeObject($value);
                return Carbon::instance($dt);
            } catch (\Exception $e) {
                // fall through
            }
        }

        try {
            return Carbon::parse(str_replace('/', '-', (string)$value));
        } catch (\Exception $e) {
            return null;
        }
    }
}
