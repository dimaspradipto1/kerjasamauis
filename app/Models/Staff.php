<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'nama_staff',
        'nup',
        'unit_kerja_id',
        'jabatan',
        'email',
        'nomor_hp',
        'alamat',
        'status',
    ];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
}
