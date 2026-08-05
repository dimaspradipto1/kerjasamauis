<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    protected $table = 'unit_kerjas';

    protected $fillable = [
        'nama_unit_kerja',
        'keterangan',
    ];

    public function kerjasamas(): BelongsToMany
    {
        return $this->belongsToMany(Kerjasama::class, 'kerjasama_unit_kerja', 'unit_kerja_id', 'kerjasama_id');
    }

    public function primaryKerjasamas(): HasMany
    {
        return $this->hasMany(Kerjasama::class, 'unit_kerja_id');
    }
}
