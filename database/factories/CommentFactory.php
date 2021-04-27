<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>User::inRandomOrder()->first('id'),
            'commentable_type'=>Post::class,
            'commentable_id'=>Post::inRandomOrder()->first('id'),
            'text'=>$this->faker->paragraph,
        ];
    }

    public function forUser($user_id)
    {
        return $this->state(function ($attributes) use ($user_id) {
            return [
                'user_id'=>$user_id ??User::inRandomOrder()->first('id')
            ];
        });
    }

    public function forPost($post_id)
    {
        return $this->state(function ($attributes) use ($post_id) {
            return [
                'commentable_type' => Post::class,
                'commentable_id'=>$post_id ?? Post::inRandomOrder()->first('id'),
            ];
        });
    }

    public function forComment($comment_id)
    {
        return $this->state(function ($attributes) use ($comment_id) {
            return [
                'commentable_type'=>Comment::class,
                'commentable_id'=>$comment_id ?? Comment::inRandomOrder()->first('id'),
            ];
        });
    }
}
