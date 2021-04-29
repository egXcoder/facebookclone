<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const AUDIENCE_TYPE = ['public','friends','only_me'];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    
    public function tagged()
    {
        return $this->belongsToMany(User::class, "tagged_users", "post_id", "user_id")->withTimestamps();
    }

    public function doingable()
    {
        return $this->morphTo();
    }

    public function gif()
    {
        return $this->belongsTo(PostGif::class, "gif_id", "id");
    }

    public function theme()
    {
        return $this->belongsTo(PostTheme::class, "theme_id", "id");
    }

    public function likes()
    {
        return $this->morphMany(Like::class, "likeable");
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getCreatedAtAttribute($created_at)
    {
        $created_at = Carbon::parse($created_at);

        if ($created_at->isSameYear() && !$created_at->isCurrentMonth()) {
            return $created_at->format('F t \a\t h:i');
        }

        return $created_at->diffForHumans();
    }
}
