<?php

use App\Http\Controllers\followersController;
use App\Http\Controllers\followingsController;
use App\Http\Controllers\giftsController;
use App\Http\Controllers\userController;
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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::controller(giftsController::class)->group(function(){
    Route::get('/usergifts/{id}', 'user_gifts');
    Route::get('/giftdetail/{giftid}/{userid}', 'gift_detail');
    Route::get('/followingsgifts/{id}', 'followings_gift');
    Route::get('/gift-view/{giftid}', 'view');
    Route::get('/gift-like/{giftid}/{userid}', 'like');
    Route::get('/islike/{giftid}/{userid}', 'islike');
    Route::delete('/gift-dislike/{giftid}/{userid}', 'dislike');
    Route::post('/gift-add/{id}', 'add_gift');
});

Route::controller(userController::class)->group(function(){
    Route::get('/userprofile/{id}', 'user_profile');
    Route::get('/isfollow/{userid}/{followid}', 'isfollow');
    Route::get('/follow/{userid}/{followid}', 'follow');
    Route::get('/unfollow/{userid}/{followid}', 'unfollow');
});

Route::controller(followersController::class)->group(function(){
    Route::get('/followerlist/{id}', 'user_followers');
});

Route::controller(followingsController::class)->group(function(){
    Route::get('/followinglist/{id}', 'user_followings');
});