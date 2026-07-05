<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KegiatanPihak extends Model
{
    protected $table = 'kegiatan_pihaks';

    protected $fillable = [
        'kegiatan_id',
        'pihak_ke',
        'jenis_pihak',
        'nomor_surat_izin',
        'penanggung_jawab',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function penanggungJawabs(): HasMany
    {
        return $this->hasMany(KegiatanPj::class, 'kegiatan_pihak_id');
    }
}
