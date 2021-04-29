<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            $post_factory = Post::factory();
            for ($i=0;$i<=5;$i++) {
                $post_factory->chainFeatures()
                    ->forUser($user->id)
                    ->afterCreating(function (Post $post) {
                        if (rand(0, 1)) {
                            $post->tagged()->sync(
                                $post->user->friends()->inRandomOrder()->take(rand(1,4))->get()
                            );
                        }
                    })
                    ->atRandomCreatedAt()
                    ->create();
            }
        }
    }
}
