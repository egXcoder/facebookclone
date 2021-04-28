<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            Comment::factory(10)->forPost()->forUser($user->id)->atRandomCreatedAt()->create();
        }

        foreach (Comment::all() as $comment) {
            Comment::factory(rand(0, 5))->forComment($comment->id)->atRandomCreatedAt()->create();
        }
    }
}
