<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/login-of-recent-login',)

Auth::routes();

Route::post('recent-logins/{recent}/delete','\App\Http\Controllers\RecentLoginController@delete')->name('recent-logins.delete');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
