<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterNotifikasi extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;

    public function karyawans()
    {
        return $this->belongsToMany(DataKaryawan::class, 'notifications', 'notification_id', 'karyawan_id')
            ->withPivot(['is_sent', 'sent_at', 'is_read', 'read_at'])
            ->withTimestamps();
    }

    public function usersNotifikasi()
    {
        return $this->hasMany(Notification::class, 'notification_id', 'id');
    }
}
