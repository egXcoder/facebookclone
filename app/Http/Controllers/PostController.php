<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostActivity;
use App\Models\PostFeeling;
use App\Rules\ProhibitedIfExists;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'text'=>'required|string|max:1024',
            'audience_type'=>'required|in:public,friends,only_me',
            'theme_id'=>['numeric','exists:post_themes,id',new ProhibitedIfExists('gif_id')],
            'feeling_id'=>['numeric','exists:post_feelings,id',new ProhibitedIfExists('activity_id')],
            'activity_id'=>['numeric','exists:post_activities,id',new ProhibitedIfExists('feeling_id')],
            'gif_id'=>['numeric','exists:post_gifs,id',new ProhibitedIfExists('theme_id')],
            'tagged'=>['array',function ($attribute, $tagged, $fail) {
                //make sure all tagged_ids in db
                foreach ($tagged as $tagged_id) {
                    if (auth('api')->user()->friends()->where('friend_id', $tagged_id)->doesntExist()) {
                        $fail('Tagged friends are invalid');
                    }
                }
            }]
        ]);

        $post = $this->storePost();
        
        return PostResource::make($post)->additional(['success'=>'Post is saved successfully']);
    }

    protected function storePost()
    {
        $post = null;
        DB::transaction(function () use (&$post) {
            $post = Post::create(array_merge([
                'text'=>request('text'),
                'audience_type'=>request('audience_type'),
                'theme_id'=>request('theme_id'),
                'gif_id'=>request('gif_id'),
                'user_id'=>auth('api')->user()->id
            ], $this->getPolymorphicArray()));
    
            $post->tagged()->sync(request('tagged'));
        });
        return $post;
    }

    
    protected function getPolymorphicArray()
    {
        if (request('feeling_id')) {
            return [
                'doingable_type'=>PostFeeling::class,
                'doingable_id'=>request('feeling_id')
            ];
        }

        if (request('activity_id')) {
            return [
                'doingable_type'=>PostActivity::class,
                'doingable_id'=>request('activity_id')
            ];
        }

        return [];
    }

    public function like(Post $post)
    {
        request()->validate([
            'type'=>['required',function ($attribute, $type, $fail) {
                if (!in_array($type, Like::TYPES)) {
                    $fail('type is invalid');
                }
            }]
        ]);

        $post->likes()->updateOrCreate(['user_id'=>auth('api')->user()->id], ['type'=>request('type')]);
        return response()->json(['success'=>'Liked successfully']);
    }

    public function unlike(Post $post)
    {
        $post->likes()->where('user_id', '=', auth('api')->user()->id)->delete();
        return response()->json(['success'=>'Unliked successfully']);
    }

    public function comment(Post $post)
    {
        request()->validate([
            'text'=>'required|string'
        ]);

        $comment = $post->comments()->create([
            'text'=>request('text'),
            'user_id'=>auth('api')->user()->id
        ]);

        return CommentResource::make($comment)->additional(['success'=>'Commented Successfully']);
    }
}
