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
            'happy'=>'๐',
            'crush'=>'๐ฅฐ',
            'love'=>'๐ฅฐ',
            'special'=>'๐คฉ',
            'relaxed'=>'โบ',
            'delicious'=>'๐',
            'joke'=>'๐',
            'goofy'=>'๐คช',
            'horrible'=>'๐',
            'irritate'=>'๐',
            'hugging'=>'๐ค',
            'whoops'=>'๐คญ',
            'shush'=>'๐คซ',
            'thinking'=>'๐ค',
            'zipper'=>'๐ค',
            'skeptic'=>'๐คจ',
            'unhappy'=>'๐',
            'meh'=>'๐',
            'smirk'=>'๐',
            'unamused'=>'๐',
            'grimace'=>'๐ฌ',
            'lying'=>'๐คฅ',
        ];
        
        foreach ($emojis as $name=>$unicode) {
            PostFeeling::create([
                'name'=>$name,
                'icon'=>$unicode
            ]);
        }
    }
}
