<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id_role';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function hasDepartemen()
    {
        return $this->hasOne(RoleHasDepartemen::class, 'id_role', 'id_role');
    }

    public function permission()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }

    public function totalUser()
    {
        return $this->hasMany(UserHasRole::class, 'id_role');
    }
}
