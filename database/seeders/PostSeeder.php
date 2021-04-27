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
            $post_factory = Post::factory(5);
            for ($i=0;$i<=4;$i++) {
                $post_factory->chainFeatures()
                    ->create(['user_id'=>$user->id]);
            }
        }
    }
}
