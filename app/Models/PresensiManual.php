<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiManual extends Model
{
    //
    use HasFactory, HasUuids;
    protected $table = 'presensi_manuals';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'lokasi',
        'alasan',
        'approved_by',
        'catatan_approver',
        'status',
    ];

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'karyawan_id');
    }
}
