<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BentukKegiatan extends Model
{
    protected $table = 'bentuk_kegiatans';

    protected $fillable = [
        'jenis_kegiatan',
        'nama_bentuk_kegiatan',
        'keterangan',
    ];

    public const JENIS = [
        'Pendidikan',
        'Penelitian',
        'Pengabdian',
    ];
}
