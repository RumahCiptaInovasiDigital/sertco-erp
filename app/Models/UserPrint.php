<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrint extends Model
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'id_user_print';
    protected $guarded;
}
