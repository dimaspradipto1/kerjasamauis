<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\UnitKerja;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StaffImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Skip header row if necessary
        $firstRow = $rows->first();
        if ($firstRow && (
            str_contains(strtolower($firstRow[0] ?? ''), 'nama') ||
            str_contains(strtolower($firstRow[0] ?? ''), 'staff') ||
            str_contains(strtolower($firstRow[0] ?? ''), 'no')
        )) {
            $rows = $rows->slice(1);
        }

        foreach ($rows as $row) {
            $namaStaff = trim($row[0] ?? '');
            if (empty($namaStaff)) {
                continue;
            }

            $nup = !empty($row[1]) ? trim($row[1]) : null;

            // Unit Kerja
            $unitKerjaText = trim($row[2] ?? '');
            $unitKerjaId = null;
            if (!empty($unitKerjaText)) {
                $unitKerja = UnitKerja::firstOrCreate(['nama_unit_kerja' => $unitKerjaText]);
                $unitKerjaId = $unitKerja->id;
            }

            Staff::create([
                'nama_staff'    => $namaStaff,
                'nup'           => $nup,
                'unit_kerja_id' => $unitKerjaId,
                'jabatan'       => !empty($row[3]) ? trim($row[3]) : null,
                'email'         => !empty($row[4]) ? trim($row[4]) : null,
                'nomor_hp'      => !empty($row[5]) ? trim($row[5]) : null,
                'alamat'        => !empty($row[6]) ? trim($row[6]) : null,
                'status'        => !empty($row[7]) ? trim($row[7]) : 'Aktif',
            ]);
        }
    }
}
