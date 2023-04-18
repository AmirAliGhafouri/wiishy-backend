<?php

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
});

Route::controller(userController::class)->group(function(){
    Route::get('/followerlist/{id}', 'user_followers');
    Route::get('/followinglist/{id}', 'user_followings');
    Route::get('/userprofile/{id}', 'user_profile');
});
