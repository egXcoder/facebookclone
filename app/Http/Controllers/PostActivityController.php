<?php

namespace App\Http\Controllers;

use App\Models\PostActivity;

class PostActivityController extends Controller
{
    public function fetch()
    {
        $activities = PostActivity::whereNull('parent_id')->with('children')->get();
        foreach ($activities as $activity) {
            foreach ($activity->children as $child) {
                $child->parent_name = $activity->name;
            }
        }
        return response()->json($activities);
    }
}
