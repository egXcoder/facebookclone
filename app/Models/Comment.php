<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $touches = ['commentable'];

    /**
     * comment parent can be post or comment
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments()
    {
        return $this->morphMany(self::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
