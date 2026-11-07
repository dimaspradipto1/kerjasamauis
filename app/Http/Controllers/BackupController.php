<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;

class BackupController extends Controller
{
    /**
     * Tampilkan halaman utama Backup Database & Storage.
     */
    public function index()
    {
        $connection = config('database.default', 'mysql');
        $databaseName = config("database.connections.{$connection}.database", env('DB_DATABASE', 'kerjasamauis'));
        
        // Hitung info storage public
        $storagePath = storage_path('app/public');
        $storageFileCount = 0;
        $storageTotalSizeBytes = 0;

        if (File::exists($storagePath)) {
            $files = File::allFiles($storagePath);
            $storageFileCount = count($files);
            foreach ($files as $file) {
                $storageTotalSizeBytes += $file->getSize();
            }
        }

        $storageTotalSize = $this->formatBytes($storageTotalSizeBytes);

        return view('pages.backup.index', compact(
            'connection',
            'databaseName',
            'storageFileCount',
            'storageTotalSize'
        ));
    }

    /**
     * Unduh backup database dalam format SQL.
     * Menggunakan PDO PHP murni agar kompatibel di shared hosting tanpa memerlukan fungsi exec().
     */
    public function downloadDatabase()
    {
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');

        $connection = config('database.default', 'mysql');
        $database = config("database.connections.{$connection}.database", env('DB_DATABASE', 'kerjasamauis'));

        $timestamp = date('Y-m-d_H-i-s');
        $fileName = "backup_db_{$database}_{$timestamp}.sql";
        $tempPath = storage_path("app/backup_temp_{$timestamp}_" . uniqid() . ".sql");

        try {
            // Ekspor menggunakan PDO PHP murni (tanpa exec / CLI)
            $this->dumpDatabaseWithPdo($tempPath, $database);

            if (!file_exists($tempPath) || filesize($tempPath) === 0) {
                throw new Exception("Berkas backup SQL kosong atau gagal dibuat.");
            }

            return response()->download($tempPath, $fileName, [
                'Content-Type' => 'application/sql',
            ])->deleteFileAfterSend(true);

        } catch (Exception $e) {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return redirect()->route('backup.index')->with('error', 'Gagal mengekspor database: ' . $e->getMessage());
        }
    }

    /**
     * Pulihkan database dari berkas SQL yang diunggah.
     * Menggunakan DB::unprepared() agar dapat berjalan di lingkungan hosting mana pun tanpa fungsi exec().
     */
    public function importDatabase(Request $request)
    {
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');

        $request->validate([
            'sql_file' => 'required|file',
        ], [
            'sql_file.required' => 'Silakan pilih berkas backup SQL.',
            'sql_file.file'     => 'Berkas yang diunggah tidak valid.',
        ]);

        $file = $request->file('sql_file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        if ($extension !== 'sql') {
            return redirect()->route('backup.index')->with('error', 'Format berkas harus berekstensi .sql');
        }

        $realPath = $file->getRealPath();

        try {
            $sqlContent = file_get_contents($realPath);
            if (empty($sqlContent)) {
                throw new Exception("Berkas SQL yang diunggah kosong.");
            }

            // Nonaktifkan pemeriksaan foreign key sementara saat restore
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::unprepared($sqlContent);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return redirect()->route('backup.index')->with('success', 'Database berhasil dipulihkan secara menyeluruh dari berkas SQL.');

        } catch (Exception $e) {
            // Pastikan foreign key checks diaktifkan kembali jika terjadi kegagalan
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (Exception $ignored) {}

            return redirect()->route('backup.index')->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }

    /**
     * Unduh seluruh isi folder storage/app/public dalam berkas ZIP.
     */
    public function downloadStorage()
    {
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');

        $timestamp = date('Y-m-d_H-i-s');
        $fileName = "backup_storage_public_{$timestamp}.zip";
        $tempPath = storage_path("app/backup_storage_{$timestamp}_" . uniqid() . ".zip");
        $storagePublic = storage_path('app/public');

        try {
            if (!File::exists($storagePublic)) {
                File::makeDirectory($storagePublic, 0755, true);
            }

            $zip = new ZipArchive();
            if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new Exception("Tidak dapat membuat berkas ZIP.");
            }

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($storagePublic, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($storagePublic) + 1);
                    // Pastikan path separator seragam forward slash
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();

            if (!file_exists($tempPath) || filesize($tempPath) === 0) {
                // Buat ZIP kosong jika belum ada file
                $zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                $zip->addFromString('.empty', 'Storage backup placeholder');
                $zip->close();
            }

            return response()->download($tempPath, $fileName, [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);

        } catch (Exception $e) {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return redirect()->route('backup.index')->with('error', 'Gagal mencadangkan storage: ' . $e->getMessage());
        }
    }

    /**
     * Pulihkan folder storage/app/public dari berkas ZIP yang diunggah.
     */
    public function importStorage(Request $request)
    {
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');

        $request->validate([
            'zip_file' => 'required|file',
        ], [
            'zip_file.required' => 'Silakan pilih berkas backup ZIP.',
            'zip_file.file'     => 'Berkas yang diunggah tidak valid.',
        ]);

        $file = $request->file('zip_file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension !== 'zip') {
            return redirect()->route('backup.index')->with('error', 'Format berkas harus berekstensi .zip');
        }

        $tempZipPath = $file->getRealPath();
        $storagePublic = storage_path('app/public');

        try {
            if (!File::exists($storagePublic)) {
                File::makeDirectory($storagePublic, 0755, true);
            }

            $zip = new ZipArchive();
            $res = $zip->open($tempZipPath);

            if ($res !== true) {
                throw new Exception("Gagal membuka berkas ZIP.");
            }

            // Ekstrak ke storage/app/public
            $zip->extractTo($storagePublic);
            $zip->close();

            return redirect()->route('backup.index')->with('success', 'Berkas storage public berhasil dipulihkan dari arsip ZIP.');

        } catch (Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Gagal memulihkan storage: ' . $e->getMessage());
        }
    }

    /**
     * Ekspor database berbasis PDO murni (tanpa exec / shell / mysqldump).
     */
    protected function dumpDatabaseWithPdo(string $filePath, string $database): void
    {
        $handle = fopen($filePath, 'w');
        if (!$handle) {
            throw new Exception("Tidak dapat menulis berkas SQL sementara.");
        }

        fwrite($handle, "-- ========================================================\n");
        fwrite($handle, "-- Backup Database: `{$database}`\n");
        fwrite($handle, "-- Tanggal Pembuatan: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "-- Dibuat oleh Sistem SIM Kerjasama\n");
        fwrite($handle, "-- ========================================================\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n");
        fwrite($handle, "SET NAMES utf8mb4;\n\n");

        $pdo = DB::connection()->getPdo();
        $tables = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")->fetchAll(\PDO::FETCH_NUM);

        foreach ($tables as $tableRow) {
            $tableName = $tableRow[0];

            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Struktur tabel `{$tableName}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTableStmt = $pdo->query("SHOW CREATE TABLE `{$tableName}`")->fetch(\PDO::FETCH_ASSOC);
            if (!empty($createTableStmt['Create Table'])) {
                fwrite($handle, $createTableStmt['Create Table'] . ";\n\n");
            }

            // Dump data baris per baris / chunk
            $rows = $pdo->query("SELECT * FROM `{$tableName}`")->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                fwrite($handle, "-- Data untuk tabel `{$tableName}`\n");
                $columnNames = array_map(fn($col) => "`{$col}`", array_keys($rows[0]));
                $columnsSql = implode(', ', $columnNames);

                foreach (array_chunk($rows, 100) as $chunk) {
                    $insertValues = [];
                    foreach ($chunk as $row) {
                        $values = [];
                        foreach ($row as $val) {
                            if ($val === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = $pdo->quote($val);
                            }
                        }
                        $insertValues[] = "(" . implode(', ', $values) . ")";
                    }
                    fwrite($handle, "INSERT INTO `{$tableName}` ({$columnsSql}) VALUES\n" . implode(",\n", $insertValues) . ";\n");
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    /**
     * Format byte ke satuan yang mudah dibaca (KB, MB, GB).
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
