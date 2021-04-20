<?php

namespace App\Http\Controllers;

use App\Models\PostTheme;
use Illuminate\Http\Request;

class PostThemeController extends Controller
{
    public function fetch(){
        $themes = PostTheme::all();
        return response()->json($themes);
    }
}
