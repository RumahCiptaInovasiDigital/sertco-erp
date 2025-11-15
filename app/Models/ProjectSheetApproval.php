<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSheetApproval extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded;

    public function pes()
    {
        return $this->belongsTo(ProjectSheet::class, 'id_project', 'id_project');
    }

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'request_by', 'id');
    }

    public function userSession()
    {
        return $this->belongsTo(User::class, 'request_by', 'id_user');
    }

    public function responseKaryawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'response_by', 'id');
    }

    public function responseUserSession()
    {
        return $this->belongsTo(User::class, 'response_by', 'id_user');
    }
}
