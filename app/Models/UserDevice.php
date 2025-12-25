<?php

namespace App\Models;

use App\Models\Enum\StatusDevice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserDevice extends Model
{
    use HasFactory;
    protected $table = 'user_devices';
    protected $fillable = [
        'id',
        'user_credential_nik',
        'device_id',
        'device_name',
        'device_type',
        'fcm_token',
        'ip_address',
        'coordinate',
        'status',
        'history',
        'register_new',
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'coordinate' => 'array',
        'history' => 'array',
        'register_new' => 'array',
        'status' => StatusDevice::class,
    ];

    protected $hidden = [
        'fcm_token',
        'history',
    ];


    public function isDeviceValid($nik, $deviceId){
        return $this->where('user_credential_nik', $nik)
                    ->where('device_id', $deviceId)
                    ->where('status', StatusDevice::ACTIVE->value)
                    ->exists();
    }

    public function user(){
        return $this->belongsTo(UserCredential::class, 'user_credential_nik', 'nik');
    }
}
