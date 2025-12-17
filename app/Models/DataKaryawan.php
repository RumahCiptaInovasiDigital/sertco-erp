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

     public function userCredential()
    {
        return $this->hasOne(UserCredential::class, 'nik', 'nik');
    }

    public function roles()
    {
        return $this->belongsTo(UserHasRole::class, 'nik', 'nik');
    }

    public function jadwalKerja(): HasOne
    {
        return $this->hasOne(JadwalKerja::class, 'id_karyawan', 'id');
    }



    public function shiftKerja(): BelongsTo
    {
        return $this->belongsTo(ShiftKerja::class, 'shift_kerja_id', 'id');
    }

    // Accessor untuk foto URL
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            // Cek apakah file ada di storage
            if (Storage::disk('public')->exists($this->foto)) {
                return asset('storage/' . $this->foto);
            }
            // Fallback jika path langsung
            return asset($this->foto);
        }
        return null;
    }


    // Accessor untuk ijazah URL
    public function getIjazahUrlAttribute()
    {
        if ($this->ijazah) {
            // Cek apakah file ada di storage
            if (Storage::disk('public')->exists($this->ijazah)) {
                return asset('storage/' . $this->ijazah);
            }
            // Fallback jika path langsung
            return asset($this->ijazah);
        }
        return null;
    }
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'data_karyawan_id', 'id');
    }
}
