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
            // Konversi row collection ke array untuk fleksibilitas akses
            $data = is_array($row) ? $row : (method_exists($row, 'toArray') ? $row->toArray() : (array)$row);

            // Ekstrak nilai berdasarkan slug heading atau fallback index
            $nomorDokumenKerjasama = trim($this->getValue($data, ['nomor_dokumen_kerjasama', 'nomor_dokumen', 'no_dokumen', 0]) ?? '');
            $judulKerjasama        = trim($this->getValue($data, ['judul_kerjasama', 'judul', 6, 5]) ?? '');

            // Lewati baris kosong atau baris contoh jika identik
            if (empty($nomorDokumenKerjasama) && empty($judulKerjasama)) {
                continue;
            }

            $nomorDokumenMitra    = trim($this->getValue($data, ['nomor_dokumen_mitra', 'no_dokumen_mitra', 1]) ?? '');
            $jenisDokumenText     = trim($this->getValue($data, ['jenis_dokumen', 2]) ?? '');
            $bidangImplementasi   = trim($this->getValue($data, ['bidang_implementasi_ia', 'bidang_implementasi', 3]) ?? '');
            $unitKerjaText        = trim($this->getValue($data, ['unit_kerja', 4, 3]) ?? '');
            $mitraText            = trim($this->getValue($data, ['mitra', 'nama_mitra', 5, 4]) ?? '');
            $deskripsiKerjasama   = trim($this->getValue($data, ['deskripsi_kerjasama', 'deskripsi', 7, 6]) ?? '');
            $skalaKerjasamaText   = trim($this->getValue($data, ['skala_kerjasama', 'skala', 8]) ?? '');
            $sumberDanaText       = trim($this->getValue($data, ['sumber_dana', 9, 7]) ?? '');
            $anggaranRaw          = $this->getValue($data, ['anggaran', 10, 8]);
            $tanggalAwalRaw       = $this->getValue($data, ['tanggal_awal_berlaku', 'tanggal_awal', 'tgl_awal', 11, 9]);
            $tanggalAkhirRaw      = $this->getValue($data, ['tanggal_akhir_berlaku', 'tanggal_akhir', 'tgl_akhir', 12, 10]);
            $statusKerjasamaText  = trim($this->getValue($data, ['status_kerjasama', 'status', 13, 11]) ?? '');
            $hasilPelaksanaan     = trim($this->getValue($data, ['hasil_pelaksanaan', 14]) ?? '');

            // 1. Resolve Jenis Dokumen
            $jenisDokumenId = $this->resolveJenisDokumen($jenisDokumenText);

            // 2. Resolve Mitra
            $mitraId = $this->resolveMitra($mitraText);

            // 3. Resolve Multi Unit Kerja
            $unitKerjaIds = $this->resolveUnitKerjas($unitKerjaText);

            // 4. Resolve Sumber Dana
            $sumberDanaId = $this->resolveSumberDana($sumberDanaText);

            // 5. Parse Dates
            $parsedAwal  = $this->parseExcelDate($tanggalAwalRaw);
            $tanggalAwal = $parsedAwal ? Carbon::instance($parsedAwal) : Carbon::now();

            $parsedAkhir  = $this->parseExcelDate($tanggalAkhirRaw);
            $tanggalAkhir = $parsedAkhir ? Carbon::instance($parsedAkhir) : (clone $tanggalAwal)->addYears(5);

            // 6. Parse Anggaran
            $anggaran = 0;
            if (!empty($anggaranRaw)) {
                $cleanAnggaran = preg_replace('/[^0-9]/', '', (string)$anggaranRaw);
                $anggaran = is_numeric($cleanAnggaran) ? (int)$cleanAnggaran : 0;
            }

            // 7. Parse Skala Kerjasama
            $skalaKerjasama = null;
            if (!empty($skalaKerjasamaText)) {
                $parts = array_map('trim', explode(',', $skalaKerjasamaText));
                $normalized = [];
                foreach ($parts as $p) {
                    if (strcasecmp($p, 'Internasional') === 0) {
                        $normalized[] = 'Internasional';
                    } elseif (strcasecmp($p, 'Nasional') === 0) {
                        $normalized[] = 'Nasional';
                    }
                }
                $skalaKerjasama = !empty($normalized) ? $normalized : ['Nasional'];
            }

            // 8. Tentukan Status Kerjasama
            $statusKerjasama = !empty($statusKerjasamaText) ? $statusKerjasamaText : 'Aktif';

            // 9. Simpan / Perbarui Kerjasama
            // Tentukan nomor dokumen & kondisi pencarian agar tidak duplikat saat import ulang
            if (!empty($nomorDokumenKerjasama)) {
                $nomorFinal = $nomorDokumenKerjasama;
                $matchConditions = ['nomor_dokumen_kerjasama' => $nomorFinal];
            } elseif (!empty($nomorDokumenMitra)) {
                $nomorFinal = $nomorDokumenMitra;
                $matchConditions = ['nomor_dokumen_kerjasama' => $nomorFinal];
            } else {
                // Jika nomor dokumen kosong di Excel, buat nomor teratur dan cocokkan judul + mitra
                $nomorFinal = 'DOK-' . $tanggalAwal->format('Y') . '-' . strtoupper(substr(md5($judulKerjasama . $mitraId), 0, 8));
                $matchConditions = [
                    'judul_kerjasama' => $judulKerjasama,
                    'mitra_id'        => $mitraId,
                ];
            }

            // File upload fisik (url_file) sengaja dibiarkan null / kosong sesuai kebutuhan pengguna
            $kerjasama = Kerjasama::updateOrCreate(
                $matchConditions,
                [
                    'nomor_dokumen_kerjasama' => $nomorFinal,
                    'nomor_dokumen_mitra'     => !empty($nomorDokumenMitra) ? $nomorDokumenMitra : null,
                    'jenis_dokumen_id'        => $jenisDokumenId,
                    'bidang_implementasi'     => !empty($bidangImplementasi) ? $bidangImplementasi : null,
                    'mitra_id'                => $mitraId,
                    'unit_kerja_id'           => $unitKerjaIds[0] ?? null,
                    'judul_kerjasama'         => !empty($judulKerjasama) ? $judulKerjasama : '-',
                    'deskripsi_kerjasama'     => !empty($deskripsiKerjasama) ? $deskripsiKerjasama : '-',
                    'skala_kerjasama'         => $skalaKerjasama,
                    'sumber_dana_id'          => $sumberDanaId,
                    'anggaran'                => $anggaran,
                    'tanggal_waktu_berlaku'   => $tanggalAwal,
                    'tanggal_akhir_berlaku'   => $tanggalAkhir,
                    'status_kerjasama'        => $statusKerjasama,
                    'hasil_pelaksanaan'       => !empty($hasilPelaksanaan) ? $hasilPelaksanaan : null,
                    'url_file'                => null, // Boleh dikosongkan (upload file fisik dilakukan menyusul)
                    'status'                  => 'active',
                ]
            );

            // Sync multi unit kerja ke pivot table kerjasama_unit_kerja
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
            return Mitra::first()?->id ?? 1;
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
            return SumberDana::first()?->id ?? 1;
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
     * Parse format tanggal dari Excel.
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
