<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SasaranKinerja extends Model
{
    protected $table = 'sasaran_kinerjas';

    protected $fillable = [
        'sasaran_kinerja',
        'keterangan',
        'level',
    ];

    public function indikatorSasarans(): HasMany
    {
        return $this->hasMany(IndikatorSasaran::class, 'sasaran_kinerja_id');
    }
}
