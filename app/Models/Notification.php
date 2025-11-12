<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded;

    public function notifikasi()
    {
        return $this->belongsTo(MasterNotifikasi::class, 'notification_id', 'id');
    }

    public function karyawan()
    {
        return $this->hasOne(DataKaryawan::class, 'id', 'karyawan_id');
    }
}
