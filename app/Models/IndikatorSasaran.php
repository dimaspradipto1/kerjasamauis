<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndikatorSasaran extends Model
{
    protected $table = 'indikator_sasarans';

    protected $fillable = [
        'sasaran_kinerja_id',
        'indikator_sasaran',
        'keterangan',
        'volume',
        'satuan',
    ];

    public function sasaranKinerja(): BelongsTo
    {
        return $this->belongsTo(SasaranKinerja::class, 'sasaran_kinerja_id');
    }
}
