<?php

namespace App\Http\Controllers;

use App\Models\PostGif;

class GifController extends Controller
{
    public function fetch()
    {
        return response()->json(PostGif::inRandomOrder()->get());
    }
}
