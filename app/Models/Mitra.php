<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    protected $table = 'mitras';

    protected $fillable = [
        'jenis_mitra',
        'nama_mitra',
        'kriteria_mitra_id',
        'nomor_izin_usaha',
        'npwp',
        'lingkup_mitra',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'kodepos',
        'alamat',
        'email',
        'no_telp',
        'website',
    ];

    public function kriteriaMitra(): BelongsTo
    {
        return $this->belongsTo(KriteriaMitra::class, 'kriteria_mitra_id');
    }

    public function kontakMitras(): HasMany
    {
        return $this->hasMany(KontakMitra::class, 'mitra_id');
    }
}
