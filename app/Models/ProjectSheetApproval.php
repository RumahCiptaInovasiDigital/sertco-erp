<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSheetApproval extends Model
{
    use HasFactory, HasUuids;

    // allow mass assignment for explicit fields
    protected $guarded = [];

    protected $table = 'project_sheet_approvals';
    protected $casts = [
        'disetujui_mkt' => 'boolean',
        'ditolak_mkt' => 'boolean',
        'disetujui_to' => 'boolean',
        'ditolak_to' => 'boolean',
    ];

    public function pes()
    {
        return $this->belongsTo(ProjectSheet::class, 'id_project', 'id_project');
    }

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'request_by', 'id');
    }

    public function responseMkt()
    {
        return $this->belongsTo(DataKaryawan::class, 'response_mkt_by', 'id');
    }

    public function responseTo()
    {
        return $this->belongsTo(DataKaryawan::class, 'response_to_by', 'id');
    }
}
