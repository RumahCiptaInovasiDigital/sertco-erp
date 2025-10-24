<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSheetDetail extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;
    protected $primaryKey = 'id_detail';

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
}
