<?php

use App\Http\Controllers\FeedController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GifController;
use App\Http\Controllers\PostActivityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostFeelingController;
use App\Http\Controllers\PostThemeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('user')->middleware('auth:api')->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });

    Route::get('/friends', [FriendController::class,'fetch']);
});

Route::prefix('posts')->middleware('auth:api')->group(function () {
    Route::get('/themes', [PostThemeController::class,'fetch']);
    Route::get('/feelings', [PostFeelingController::class,'fetch']);
    Route::get('/activities', [PostActivityController::class,'fetch']);
    Route::get('/gifs', [GifController::class,'fetch']);

    Route::post('/', [PostController::class,'store']);

    Route::get('/feed',[FeedController::class,'fetch']);
});
