<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'feedbacks';
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'page',
        'status',
        'browser',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function typeLabel()
    {
        return [
            'bug' => 'Bug / Error',
            'ui' => 'UI / UX',
            'feature' => 'Feature Request',
            'performance' => 'Performance',
            'other' => 'Other'
        ][$this->type];
    }
}

