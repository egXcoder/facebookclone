<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFeeling extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->morphMany(Post::class, "doingable");
    }
}
