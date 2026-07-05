<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KerjasamaPihak extends Model
{
    protected $table = 'kerjasama_pihaks';

    protected $fillable = [
        'kerjasama_id',
        'pihak_ke',
        'jenis_pihak',
        'alamat',
    ];

    public function kerjasama(): BelongsTo
    {
        return $this->belongsTo(Kerjasama::class, 'kerjasama_id');
    }

    public function penanggungJawabs(): HasMany
    {
        return $this->hasMany(KerjasamaPenanggungJawab::class, 'kerjasama_pihak_id');
    }
}
