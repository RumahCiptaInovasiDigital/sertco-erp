<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceMode extends Model
{
    use HasFactory;use HasUuids;

    protected $primaryKey = 'id_maintenance';
    protected $guarded;
}
