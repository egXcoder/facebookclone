<?php

namespace Database\Seeders;

use App\Models\PostTheme;
use Illuminate\Database\Seeder;

class PostThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostTheme::factory(20)->create();
    }
}
