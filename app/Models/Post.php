<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    
    public function tagged_users()
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
}
