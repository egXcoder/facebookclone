<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            foreach (Post::all() as $post) {
                Like::factory()->forPost($post->id)->forUser($user->id)->create();
            }

            foreach (Comment::All() as $comment) {
                Like::factory()->forComment($comment->id)->forUser($user->id)->create();
            }
        }
    }
}
