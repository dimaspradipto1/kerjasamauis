<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    protected $table = 'jenis_dokumens';

    protected $fillable = [
        'nama_jenis_dokumen',
        'keterangan',
    ];
}
