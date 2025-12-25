<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class UserCredential extends Authenticatable
{
    use HasUuids,  Notifiable, HasApiTokens, HasFactory;
    protected $fillable = [
        'nik',
        'pass',
    ];

    protected $hidden = [
        'pass',
    ];

     protected $with = ['karyawan'];

    public function karyawan() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(related: DataKaryawan::class, foreignKey: 'nik', ownerKey: 'nik');
    }

    public function createToken(){
        $token = Str::random(16) . md5(time() . Str::random(32));
        PersonalAccessToken::query()->where('name', "mobile:{$this->nik}")->delete();
        PersonalAccessToken::query()->insert([
            'tokenable_type' => "App\Models\UserCredential",
            'tokenable_id' => (int) $this->id,
            'name' => "mobile:{$this->nik}",
            'token' => hash('sha256', $token),
            'abilities' => '["*"]',
            'created_at' => now(),
            'expires_at' => now()->addDays(35),
        ]);
        return $token;
    }
}
