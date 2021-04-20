<?php

namespace App\Http\Controllers;

use App\Models\PostFeeling;

class PostFeelingController extends Controller
{
    public function fetch()
    {
        return response()->json(PostFeeling::all());
    }
}
