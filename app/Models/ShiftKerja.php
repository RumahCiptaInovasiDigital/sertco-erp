<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShiftKerja extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'shift_kerja';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_shift',
        'jam_masuk_min',
        'jam_masuk_max',
        'jam_pulang_min',
        'jam_pulang_max',
        'tipe',
        'berlaku_untuk',
        'status',
    ];

    public function jenis_kerja()
    {
        return $this->belongsTo(JenisKerja::class, 'tipe', 'id');
    }

    public function jadwalKerja(): HasMany
    {
        return $this->hasMany(JadwalKerja::class, 'id_shift_kerja');
    }

    // Accessor untuk mendapatkan nama jenis kerja
    public function getTipeNamaAttribute()
    {
        return $this->jenis_kerja ? $this->jenis_kerja->nama_jenis_kerja : '-';
    }
}
