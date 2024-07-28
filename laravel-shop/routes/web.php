<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'signIn')->name('signIn')->middleware('throttle:auth');

    Route::get('/sign-up', 'signUp')->name('signUp');
    Route::post('/sign-up', 'store')->name('store')->middleware('throttle:auth');

    Route::delete('/logout', 'logOut')->name('logOut');

    Route::get('/forgot-password', 'forgot')->middleware('guest')->name('password.request');
    Route::post('/forgot-password', 'forgotPassword')->middleware('guest')->name('password.email');

    Route::get('/reset-password/{token}', 'reset')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->middleware('guest')->name('password.update');

    Route::get('/auth/socialite/github', 'github')->name('socialite.github');

    Route::get('/auth/socialite/github/callback', 'githubCallback')->name('socialite.github.callback');
});

Route::get('/', HomeController::class)->name('home');

