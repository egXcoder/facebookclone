<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email'=>'user@example.com',
            'password'=>Hash::make(('123'))
        ]);

        User::factory()->create([
            'email'=>'user2@example.com',
            'password'=>Hash::make(('123'))
        ]);

        User::factory()->create([
            'email'=>'user3@example.com',
            'password'=>Hash::make(('123'))
        ]);

        User::factory(10)->create();
    }
}
