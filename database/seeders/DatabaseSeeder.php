<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PostThemeSeeder::class);
        $this->call(PostFeelingSeeder::class);
        $this->call(PostActivitySeeder::class);
        $this->call(PostGifSeeder::class);
    }
}
