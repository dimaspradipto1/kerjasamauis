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
     */
    public function downloadDatabase()
    {
        $connection = config('database.default', 'mysql');
        $database = config("database.connections.{$connection}.database", env('DB_DATABASE', 'kerjasamauis'));
        $username = config("database.connections.{$connection}.username", env('DB_USERNAME', 'root'));
        $password = config("database.connections.{$connection}.password", env('DB_PASSWORD', ''));
        $host     = config("database.connections.{$connection}.host", env('DB_HOST', '127.0.0.1'));
        $port     = config("database.connections.{$connection}.port", env('DB_PORT', '3306'));

        $timestamp = date('Y-m-d_H-i-s');
        $fileName = "backup_db_{$database}_{$timestamp}.sql";
        $tempPath = storage_path("app/backup_temp_{$timestamp}_" . uniqid() . ".sql");

        try {
            // Coba ekspor menggunakan mysqldump jika tersedia
            $mysqldumpSuccess = false;
            $passwordPart = $password !== '' && $password !== null ? " --password=" . escapeshellarg($password) : "";
            $cmd = "mysqldump --host=" . escapeshellarg($host) . 
                   " --port=" . escapeshellarg($port) . 
                   " --user=" . escapeshellarg($username) . 
                   $passwordPart . 
                   " --routines --triggers --single-transaction --quick " . 
                   escapeshellarg($database) . " > " . escapeshellarg($tempPath);

            @exec($cmd, $output, $returnVar);

            if ($returnVar === 0 && file_exists($tempPath) && filesize($tempPath) > 0) {
                $mysqldumpSuccess = true;
            }

            // Fallback ke generator PHP PDO murni jika mysqldump gagal
            if (!$mysqldumpSuccess) {
                $this->dumpDatabaseWithPdo($tempPath, $database);
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
     */
    public function importDatabase(Request $request)
    {
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

        $connection = config('database.default', 'mysql');
        $database = config("database.connections.{$connection}.database", env('DB_DATABASE', 'kerjasamauis'));
        $username = config("database.connections.{$connection}.username", env('DB_USERNAME', 'root'));
        $password = config("database.connections.{$connection}.password", env('DB_PASSWORD', ''));
        $host     = config("database.connections.{$connection}.host", env('DB_HOST', '127.0.0.1'));
        $port     = config("database.connections.{$connection}.port", env('DB_PORT', '3306'));

        $realPath = $file->getRealPath();

        try {
            $mysqlSuccess = false;
            $passwordPart = $password !== '' && $password !== null ? " --password=" . escapeshellarg($password) : "";
            $cmd = "mysql --host=" . escapeshellarg($host) . 
                   " --port=" . escapeshellarg($port) . 
                   " --user=" . escapeshellarg($username) . 
                   $passwordPart . " " . 
                   escapeshellarg($database) . " < " . escapeshellarg($realPath);

            @exec($cmd, $output, $returnVar);

            if ($returnVar === 0) {
                $mysqlSuccess = true;
            }

            // Fallback: DB::unprepared jika mysql command line gagal
            if (!$mysqlSuccess) {
                $sqlContent = file_get_contents($realPath);
                DB::unprepared($sqlContent);
            }

            return redirect()->route('backup.index')->with('success', 'Database berhasil dipulihkan secara menyeluruh dari berkas SQL.');

        } catch (Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }

    /**
     * Unduh seluruh isi folder storage/app/public dalam berkas ZIP.
     */
    public function downloadStorage()
    {
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
     * Fallback ekspor database berbasis PDO murni (jika mysqldump tidak ada).
     */
    protected function dumpDatabaseWithPdo(string $filePath, string $database): void
    {
        $handle = fopen($filePath, 'w');
        if (!$handle) {
            throw new Exception("Tidak dapat menulis berkas SQL sementara.");
        }

        fwrite($handle, "-- Backup Database: `{$database}`\n");
        fwrite($handle, "-- Tanggal Pembuatan: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "-- --------------------------------------------------------\n\n");
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
            fwrite($handle, $createTableStmt['Create Table'] . ";\n\n");

            // Dump data
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

