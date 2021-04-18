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
            'happy'=>'\u1F601',
            'crush'=>'\u1F970',
            'love'=>'\u1F60D',
            'special'=>'\u1F929',
            'relaxed'=>'\u263A',
            'delicious'=>'\u1F60B',
            'joke'=>'\u1F61C',
            'goofy'=>'\u1F92A',
            'horrible'=>'\u1F61D',
            'irritate'=>'\u1F61B',
            'hugging'=>'\u1F917',
            'whoops'=>'\u1F92D',
            'shush'=>'\u1F92B',
            'thinking'=>'\u1F914',
            'zipper'=>'\u1F910',
            'skeptic'=>'\u1F928',
            'neutral'=>'\u1F610',
            'meh'=>'\u1F611',
            'smirk'=>'\u1F60F',
            'unamused'=>'\u1F612',
            'grimace'=>'\u1F62C',
            'lying'=>'\u1F925',
        ];
        
        foreach($emojis as $name=>$unicode){
            PostFeeling::create([
                'name'=>$name,
                'unicode'=>$unicode
            ]);
        }
    }
}
