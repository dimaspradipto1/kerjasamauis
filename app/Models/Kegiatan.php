<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    protected $table = 'kegiatans';

    protected $fillable = [
        'kerjasama_id',
        'unit_kerja_id',
        'mitra_id',
        'sasaran_kinerja_id',
        'bentuk_kegiatan_id',
        'indikator_id',
        'nomor_dokumen_kegiatan',
        'nomor_dokumen_mitra',
        'judul_kegiatan',
        'tanggal_awal_kegiatan',
        'tanggal_akhir_kegiatan',
        'ruang_lingkup',
        'hasil_pelakasanaan', // Note: misspelled as in migration: 'hasil_pelakasanaan'
        'nilai_kontrak',
        'link_dokumen_kegiatan',
        'url_file',
    ];

    protected $casts = [
        'tanggal_awal_kegiatan'  => 'date',
        'tanggal_akhir_kegiatan' => 'date',
    ];

    public function kerjasama(): BelongsTo
    {
        return $this->belongsTo(Kerjasama::class, 'kerjasama_id');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function sasaranKinerja(): BelongsTo
    {
        return $this->belongsTo(SasaranKinerja::class, 'sasaran_kinerja_id');
    }

    public function bentukKegiatan(): BelongsTo
    {
        return $this->belongsTo(BentukKegiatan::class, 'bentuk_kegiatan_id');
    }

    public function indikator(): BelongsTo
    {
        return $this->belongsTo(IndikatorSasaran::class, 'indikator_id');
    }

    public function kegiatanPihaks(): HasMany
    {
        return $this->hasMany(KegiatanPihak::class, 'kegiatan_id');
    }
}
