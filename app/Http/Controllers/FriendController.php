<?php

namespace App\Http\Controllers;

class FriendController extends Controller
{
    public function fetch()
    {
        return response()->json(auth()->user()->friends);
    }
}
