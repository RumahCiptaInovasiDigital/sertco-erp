<?php

namespace App\Models;

use App\Database\CustomBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CalendarEvent extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $fillable = [
        'title',
        'start',
        'end',
        'description',
        'color',
        'all_day',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'all_day' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function newEloquentBuilder($query)
    {
        return new CustomBuilder($query);
    }
}
