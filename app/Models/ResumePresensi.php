<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumePresensi extends Model
{
    protected $table = 'resume_presensi';
    protected $primaryKey = "id";

    protected $fillable = [
        'karyawan_id',
        'periode',
        'total_hari',
        'total_good',
        'total_late',
        'total_absent',
        'total_leave',
        'total_sick',
        'total_onduty',
        'total_overtime',
        'total_uncompleted',
    ];

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'karyawan_id', 'id');
    }
}
