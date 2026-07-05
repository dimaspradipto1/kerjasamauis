<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KontakMitra extends Model
{
    protected $table = 'kontak_mitras';

    protected $fillable = [
        'mitra_id',
        'nama_kontak',
        'jabatan',
        'nomor_handphone',
        'email',
    ];

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
}
