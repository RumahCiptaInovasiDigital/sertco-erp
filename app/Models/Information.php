<?php

namespace App\Models;

use App\Database\CustomBuilder;
use App\Models\Enum\StatusInformation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'information';
    protected $primaryKey = "id";
    protected $fillable = [
        'title',
        'description',
        'status',
        'attachment_path',
        'mime_type',
        'type',
        'start_date',
        'end_date',
        'color',
        'id_user',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => StatusInformation::class
    ];

    public function newEloquentBuilder($query):Builder{
        return new CustomBuilder($query);
    }

    public function user(){
        return $this->belongsTo(User::class,'id_user','id_user');
    }
}
