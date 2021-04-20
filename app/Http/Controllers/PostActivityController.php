<?php

namespace App\Http\Controllers;

use App\Models\PostActivity;

class PostActivityController extends Controller
{
    public function fetch()
    {
        $activities = PostActivity::whereNull('parent_id')->with('children')->get();
        return response()->json($activities);
    }
}
