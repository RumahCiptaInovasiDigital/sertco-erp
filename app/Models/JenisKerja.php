<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKerja extends Model
{
    //
    protected $table = 'jenis_kerja';
    protected $fillable = [
        'nama_jenis_kerja',
        'keterangan',
        'status',
    ];
}
