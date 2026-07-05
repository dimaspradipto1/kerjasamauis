<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KerjasamaPenanggungJawab extends Model
{
    protected $table = 'kerjasama_penanggung_jawabs';

    protected $fillable = [
        'kerjasama_pihak_id',
        'nama',
        'nip',
        'jabatan',
        'nomor_hp',
        'email',
    ];

    public function kerjasamaPihak(): BelongsTo
    {
        return $this->belongsTo(KerjasamaPihak::class, 'kerjasama_pihak_id');
    }
}
