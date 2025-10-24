<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSheet extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;
    protected $primaryKey = 'id_project';

    public $incrementing = false;
    protected $keyType = 'string';

    public function project_sheet_detail()
    {
        return $this->hasOne(ProjectSheetDetail::class, 'id_project', 'id_project');
    }

    public function service()
    {
        return $this->hasMany(ServiceFormData::class, 'project_no', 'project_no');
    }

    public function project_service()
    {
        return $this->service()->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'prepared_by', 'id_user');
    }

    public function toRole()
    {
        return $this->belongsTo(Role::class, 'to', 'id_role');
    }

    public function attnRole()
    {
        return $this->belongsTo(Role::class, 'attn', 'id_role');
    }
}
