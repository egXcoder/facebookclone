<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostActivity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(self::class,"parent_id","id");
    }

    public function children()
    {
        return $this->hasMany(self::class,"parent_id","id");
    }

    public function posts()
    {
        return $this->morphMany(Post::class, 'doingable');
    }
}
