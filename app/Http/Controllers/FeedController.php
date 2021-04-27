<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    public function fetch()
    {
        $friends_id = auth('api')->user()->friends()->pluck('friend_id')->toArray();
        $posts = Post::whereIn('user_id', array_merge($friends_id, [auth('api')->user()->id]))
            ->orderBy('updated_at', 'DESC')->get();
        return PostResource::collection($posts);
    }
}
