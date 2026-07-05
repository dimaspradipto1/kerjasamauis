<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaMitra extends Model
{
    protected $table = 'kriteria_mitras';

    protected $fillable = [
        'kriteria_mitra',
        'keterangan',
    ];
}
