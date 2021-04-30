<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;

class FriendController extends Controller
{
    public function fetch()
    {
        return UserResource::collection(auth('api')->user()->friends);
    }
}
