<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>User::inRandomOrder()->first('id'),
            'type'=>Arr::random(Like::TYPES),
            'likeable_type'=>Post::class,
            'likeable_id'=> $post_id ?? Post::inRandomOrder()->first('id'),
        ];
    }

    public function forPost($post_id = null)
    {
        return $this->state(function ($attributes) use ($post_id) {
            return [
                'likeable_type'=>Post::class,
                'likeable_id'=> $post_id ?? Post::inRandomOrder()->first('id'),
            ];
        });
    }

    public function forComment($comment_id = null)
    {
        return $this->state(function ($attributes) use ($comment_id) {
            return [
                'likeable_type'=>Comment::class,
                'likeable_id'=>$comment_id ?? Comment::inRandomOrder()->first('id'),
            ];
        });
    }

    public function forUser($user_id = null){
        return $this->state(function($attributes) use($user_id){
            return [
                'user_id' => $user_id ?? User::inRandomOrder()->first('id')
            ];
        });
    }
}
