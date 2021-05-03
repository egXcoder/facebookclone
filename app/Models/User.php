<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image_url',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function friendsOfMine()
    {
        return $this->belongsToMany(self::class, "users_friends", "user_id", "friend_id")->withTimestamps();
    }
    
    public function friendOf()
    {
        return $this->belongsToMany(self::class, "users_friends", "friend_id", "user_id")->withTimestamps();
    }

    // accessor allowing you call $user->friends
    public function getFriendsAttribute()
    {
        if (! array_key_exists('friends', $this->relations)) {
            $this->loadFriends();
        }

        return $this->getRelation('friends');
    }

    protected function loadFriends()
    {
        if (! array_key_exists('friends', $this->relations)) {
            $friends = $this->mergeFriends();

            $this->setRelation('friends', $friends);
        }
    }

    protected function mergeFriends()
    {
        return $this->friendsOfMine->merge($this->friendOf);
    }

    public function tagged_posts()
    {
        return $this->belongsToMany(Post::class, "tagged", "user_id", "post_id");
    }
}
