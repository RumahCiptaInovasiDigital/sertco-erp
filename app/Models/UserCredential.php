<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserCredential extends Model
{
    use HasUuids;
    protected $fillable = [
        'nik',
        'pass',
    ];
}
