<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiIzin extends Model
{
    //
    use HasFactory, HasUuids;

    protected $table = 'presensi_izins';

    protected $fillable = [
        'karyawan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_izin',
        'keterangan',
        'file_pendukung',
        'status',
        'catatan_approver',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'approved_at' => 'datetime'
    ];

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'karyawan_id','id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by','id');
    }
}
