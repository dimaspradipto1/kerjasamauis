<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /**
     * Daftar semua modul yang dikontrol oleh sistem permission.
     */
    const MODULES = [
        'user'             => 'Manajemen Pengguna',
        'mitra'            => 'Data Mitra',
        'kerjasama'        => 'Data Kerjasama',
        'kegiatan'         => 'Data Kegiatan',
        'unit_kerja'       => 'Unit Kerja',
        'bentuk_kegiatan'  => 'Bentuk Kegiatan',
        'sasaran_kinerja'  => 'Sasaran Kinerja',
        'kriteria_mitra'   => 'Kriteria Mitra',
        'sumber_dana'      => 'Sumber Dana',
        'jenis_dokumen'    => 'Jenis Dokumen',
        'laporan'          => 'Laporan',
    ];

    /**
     * Default permissions berdasarkan role pengguna.
     */
    const ROLE_DEFAULTS = [
        'superadmin' => [
            'can_create' => true,
            'can_read'   => true,
            'can_update' => true,
            'can_delete' => true,
        ],
        'admin' => [
            'can_create' => true,
            'can_read'   => true,
            'can_update' => true,
            'can_delete' => true,
        ],
        'pimpinan' => [
            'can_create' => false,
            'can_read'   => true,
            'can_update' => false,
            'can_delete' => false,
        ],
        'user' => [
            'can_create' => false,
            'can_read'   => true,
            'can_update' => false,
            'can_delete' => false,
        ],
    ];

    /**
     * Modul yang hanya bisa diakses superadmin (role lain mendapat akses false semua).
     * Kosong — admin kini bisa akses modul user dengan batasan di UI/controller.
     */
    const SUPERADMIN_ONLY_MODULES = [];

    /**
     * Override permissions per-role per-modul.
     * Digunakan untuk kasus khusus di luar ROLE_DEFAULTS.
     * Format: [role => [module => [action => bool, ...]]]
     */
    const MODULE_OVERRIDES = [
        // Admin bisa CRUD pengguna, tapi TIDAK bisa mengubah role/hak akses.
        // Pembatasan role field dihandle di UI dan controller, bukan di sini.
        'admin' => [
            'user' => [
                'can_create' => true,
                'can_read'   => true,
                'can_update' => true,
                'can_delete' => true,
            ],
        ],
    ];

    protected $fillable = [
        'user_id',
        'module',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    protected function casts(): array
    {
        return [
            'can_create' => 'boolean',
            'can_read'   => 'boolean',
            'can_update' => 'boolean',
            'can_delete' => 'boolean',
        ];
    }

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
