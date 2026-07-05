<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kerjasama extends Model
{
    protected $table = 'kerjasamas';

    protected $fillable = [
        'nomor_dokumen_kerjasama',
        'nomor_dokumen_mitra',
        'jenis_dokumen_id',
        'mitra_id',
        'unit_kerja_id',
        'judul_kerjasama',
        'deskripsi_kerjasama',
        'sumber_dana_id',
        'anggaran',
        'tanggal_waktu_berlaku',
        'tanggal_akhir_berlaku',
        'status_kerjasama',
        'url_file',
        'hasil_pelaksanaan',
        'status',
    ];

    protected $casts = [
        'tanggal_waktu_berlaku' => 'date',
        'tanggal_akhir_berlaku' => 'date',
    ];

    public function jenisDokumen(): BelongsTo
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function sumberDana(): BelongsTo
    {
        return $this->belongsTo(SumberDana::class, 'sumber_dana_id');
    }

    public function kerjasamaPihaks(): HasMany
    {
        return $this->hasMany(KerjasamaPihak::class, 'kerjasama_id');
    }
}
