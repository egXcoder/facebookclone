<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserFriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $all_users_ids = $users->pluck('id')->toArray();

        foreach($users as $user){
            $user->friends()->attach(array_slice($all_users_ids,rand(0,$users->count())));
        }
    }
}
