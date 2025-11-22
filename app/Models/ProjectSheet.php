<?php

namespace App\Models;

use App\Traits\GenerateProjectNo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSheet extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use GenerateProjectNo;

    protected $guarded;
    protected $primaryKey = 'id_project';

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->project_no)) {
                $model->project_no = $model->generateProjectNo();
            }
        });
    }

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

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'prepared_by', 'id');
    }

    
    public function preparedBy()
    {
        return $this->belongsTo(DataKaryawan::class, 'prepared_by', 'id');
    }
    public function sigantureBy()
    {
        return $this->belongsTo(DataKaryawan::class, 'signature_by', 'id');
    }

    public function approval()
    {
        return $this->belongsTo(ProjectSheetApproval::class, 'id_project', 'id_project');
    }
}
