<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanPj extends Model
{
    protected $table = 'kegiatan_pjs';

    protected $fillable = [
        'kegiatan_pihak_id',
        'nama',
        'nip',
        'jabatan',
        'nomor_hp',
        'email',
    ];

    public function kegiatanPihak(): BelongsTo
    {
        return $this->belongsTo(KegiatanPihak::class, 'kegiatan_pihak_id');
    }
}
