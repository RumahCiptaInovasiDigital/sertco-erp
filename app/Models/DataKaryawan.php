<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataKaryawan extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notifications', 'karyawan_id', 'notification_id')
            ->withPivot(['is_sent', 'sent_at', 'is_read', 'read_at'])
            ->withTimestamps();
    }

    public function jabatan()
    {
        return $this->belongsTo(Role::class, 'idJabatan', 'id_role');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'idDepartemen', 'id_departemen');
    }

    public function sertifikat()
    {
        return $this->hasMany(MatrixPersonil::class, 'nik_karyawan', 'nik');
    }
}
