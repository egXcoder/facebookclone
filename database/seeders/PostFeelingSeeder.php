<?php

namespace Database\Seeders;

use App\Models\PostFeeling;
use Illuminate\Database\Seeder;

class PostFeelingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emojis = [
            'happy'=>'😁',
            'crush'=>'🥰',
            'love'=>'🥰',
            'special'=>'🤩',
            'relaxed'=>'☺',
            'delicious'=>'😋',
            'joke'=>'😜',
            'goofy'=>'🤪',
            'horrible'=>'😝',
            'irritate'=>'😛',
            'hugging'=>'🤗',
            'whoops'=>'🤭',
            'shush'=>'🤫',
            'thinking'=>'🤔',
            'zipper'=>'🤐',
            'skeptic'=>'🤨',
            'unhappy'=>'😒',
            'meh'=>'😑',
            'smirk'=>'😏',
            'unamused'=>'😒',
            'grimace'=>'😬',
            'lying'=>'🤥',
        ];
        
        foreach ($emojis as $name=>$unicode) {
            PostFeeling::create([
                'name'=>$name,
                'icon'=>$unicode
            ]);
        }
    }
}
