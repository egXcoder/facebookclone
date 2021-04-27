<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostActivity;
use App\Models\PostFeeling;
use App\Rules\ProhibitedIfExists;
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

        $this->storePost();

        return ['success'=>'Post is saved successfully'];
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

    protected function storePost()
    {
        DB::transaction(function () {
            $post = Post::create(array_merge([
                'text'=>request('text'),
                'audience_type'=>request('audience_type'),
                'theme_id'=>request('theme_id'),
                'gif_id'=>request('gif_id'),
                'user_id'=>auth('api')->user()->id
            ], $this->getPolymorphicArray()));
    
            $post->tagged_users()->sync(request('tagged'));
        });
    }
}
