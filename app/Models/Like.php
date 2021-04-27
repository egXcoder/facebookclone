<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public const TYPES = ['like','love','care','haha','wow','sad','angry'];

    protected $touches = ['likeable'];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    /**
     * like parent can be post or comment
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}
