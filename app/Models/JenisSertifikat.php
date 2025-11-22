<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisSertifikat extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = 'id_sertifikat';
    protected $guarded = [];

    public function jabatan()
    {
        return $this->belongsTo(Role::class, 'pic', 'id_role');
    }
}
