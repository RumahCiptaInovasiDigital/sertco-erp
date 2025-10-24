<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHasRole extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'NIK');
    }
}
