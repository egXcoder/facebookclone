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
        $users = User::all();
        foreach($users as $user){
            $author_meta = ['author_id'=>$user->id];
            Post::factory(8)->create($author_meta);
            Post::factory(4)->withTheme()->create($author_meta);
            Post::factory(3)->withGif()->create($author_meta);
            Post::factory(4)->withFeeling()->create($author_meta);
            Post::factory(5)->withActivity()->create($author_meta);
            Post::factory(4)->withTagged()->create($author_meta);
        }
    }
}
