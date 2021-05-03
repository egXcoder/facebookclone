<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;

class FeedController extends Controller
{
    public function fetch()
    {
        $friends_id = auth('api')->user()->friends->pluck('id')->toArray();
        $posts = Post::whereIn('user_id', array_merge($friends_id, [auth('api')->user()->id]))
            ->where('audience_type', '<>', 'only_me')
            ->with([
                'user',
                'likes',
                'theme',
                'gif',
                'doingable',
                'tagged',
                'comments'=>function ($query) {
                    $query->orderBy('created_at', 'ASC');
                },
                'comments.user',
                'comments.likes',
                'comments.comments'=>function ($query) {
                    $query->orderBy('created_at', 'ASC');
                },
                'comments.comments.user',
                'comments.comments.likes',
            ])
            ->orderBy('updated_at', 'DESC')->get();
        return PostResource::collection($posts);
    }
}
