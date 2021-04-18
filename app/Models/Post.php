<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function tagged_users()
    {
        return $this->belongsToMany(User::class, "tagged_users", "post_id", "user_id");
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
}
