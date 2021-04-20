<?php

use App\Http\Controllers\PostActivityController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('posts')->group(function () {
    Route::get('/themes', [PostThemeController::class,'fetch']);
    Route::get('/feelings', [PostFeelingController::class,'fetch']);
    Route::get('/activities', [PostActivityController::class,'fetch']);
});
