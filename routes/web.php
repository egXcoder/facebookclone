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

Auth::routes();

Route::post('recent-logins/{recent}/delete','\App\Http\Controllers\RecentLoginController@delete')->name('recent-logins.delete');
Route::post('recent-logins/{recent}/login','\App\Http\Controllers\RecentLoginController@login')->name('recent-logins.login');

Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->name('home')->where('any','.*');
