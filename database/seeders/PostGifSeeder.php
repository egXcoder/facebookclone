<?php

namespace Database\Seeders;

use App\Models\PostGif;
use Illuminate\Database\Seeder;

class PostGifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gifs = [
            'https://media.giphy.com/media/B7o99rIuystY4/giphy.gif',
            'https://media.giphy.com/media/MBmTzBFNbKF2tzikTY/giphy.gif',
            'https://media.giphy.com/media/ZBL02G9KUEw4ptmrYd/giphy.gif',
            'https://media.giphy.com/media/4xWGyVKoXqg2eVCiq9/giphy.gif',
            'https://media.giphy.com/media/F0QWePzwQRewM/giphy.gif',
            'https://media.giphy.com/media/gJ3ho4vLHJ5rW/giphy.gif',
            'https://media.giphy.com/media/SRkVigbErPKC5KW2VG/giphy.gif',
            'https://media.giphy.com/media/ozf26DV8FqaCpkYt6n/giphy.gif',
            'https://media.giphy.com/media/mPxtCNTbEsTuZecKLN/giphy.gif',
            'https://media.giphy.com/media/J1jmN9HW07HoRTe9X5/giphy.gif',
            'https://media.giphy.com/media/5kFaYe1VpXVLmrqerH/giphy.gif',
            'https://media.giphy.com/media/5h29CaTCj9UyB5SveE/giphy.gif',
            'https://media.giphy.com/media/Ey5kkecYKdaN2/giphy.gif',
            'https://media.giphy.com/media/7KEVdgJpEL5w4/giphy.gif',
            'https://media.giphy.com/media/SvgmMm20VGHzq/giphy.gif',
            'https://media.giphy.com/media/EfG9yOodwJQOs/giphy.gif',
            'https://media.giphy.com/media/6153LXqgbBkBO/giphy.gif',
            'https://media.giphy.com/media/vq5xdyN7McPbxaW1Qa/giphy.gif',
            'https://media.giphy.com/media/j5IkNaSYWOGsn1ihqo/giphy.gif',
            'https://media.giphy.com/media/dBT7mACpkW8Fy/giphy.gif',
            'https://media.giphy.com/media/icD0ZbSWCiUIWoF2Xp/giphy.gif',
            'https://media.giphy.com/media/aHBt6xM8CfOMM/giphy.gif',
            'https://media.giphy.com/media/hds923o2jMwLK/giphy.gif',
            'https://media.giphy.com/media/g0gtihsbzj5pSWgcml/giphy.gif',
            'https://media.giphy.com/media/3H1LTLh2cftUQ/giphy.gif'
        ];

        foreach($gifs as $url){
            PostGif::create([
                'gif_url'=>$url
            ]);
        }
    }
}
