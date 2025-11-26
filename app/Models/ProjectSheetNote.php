<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSheetNote extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // helper to get nested replies collection (eager load not required)
    public function nestedReplies()
    {
        return $this->replies()->with('user')->get()->map(function ($r) {
            $r->children = $r->nestedReplies();
            return $r;
        });
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }

    public function isLikedBy($userId)
    {
        return $this->likes()->where('id_user', $userId)->exists();
    }

    // human readable time helper (optional)
    public function getTimeHumanAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }
}
