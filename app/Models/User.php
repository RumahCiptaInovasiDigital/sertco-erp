<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasUuids;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'id_user';
    protected $guarded;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'karyawan_id', 'id_user');
    }

    public function notify()
    {
        return $this->notifications()->orderBy('created_at', 'desc')->get();
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'jabatan', 'name');
    }

    public function sessions()
    {
        return $this->belongsTo(Session::class, 'id', 'user_id');
    }

    public function hasRole()
    {
        return $this->hasOne(UserHasRole::class, 'nik', 'nik');
    }
}
