<?php

namespace App\Http\Controllers;

use App\Models\PostActivity;

class PostActivityController extends Controller
{
    public function fetch()
    {
        return response()->json(PostActivity::all());
    }
}
