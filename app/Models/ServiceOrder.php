<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;

    protected $primaryKey = 'id_so';

    public function hasVendor()
    {
        return $this->hasOne(Vendor::class, 'id_vendor', 'id_vendor');
    }
}
