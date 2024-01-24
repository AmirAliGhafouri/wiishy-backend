<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\followController;
use App\Http\Controllers\giftsController;
use App\Http\Controllers\likeController;
use App\Http\Controllers\userController;
use App\Http\Controllers\eventsController;
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

Route::post('/auth/{provider}',[userController::class,'auth']);
Route::get('/Unauthenticated',[userController::class,'Unauthenticated'])->name('login');


Route::group(['middleware' => 'auth:sanctum'],function (){
    Route::controller(giftsController::class)->group(function(){
        Route::get('/usergifts/{userid}', 'userGifts');
        Route::get('/user-products/{userid}/{my_product}', 'userProducts');
        Route::get('/giftdetail/{giftid}', 'giftDetail');
        Route::get('/user-home', 'followingsGift');
        Route::get('/gift-remove/{giftid}/{user_id}','remove');
        Route::get('/gift-view/{giftid}', 'view');
        Route::get('/gift-share/{giftid}', 'share');
        Route::get('/gift-explore', 'explore');
        Route::get('/price-units', 'priceUnits');
        Route::post('/gift-search', 'search');
        Route::post('/gift-add', 'create');
        Route::post('/gift-update/{giftid}/{userid}', 'update');
    });

    Route::controller(likeController::class)->group(function(){
        Route::get('/gift-likeslist/{giftid}', 'likeslist');
        Route::get('/gift-like/{giftid}/{userid}', 'like');
        Route::get('/gift-islike/{giftid}/{userid}', 'islike');
        Route::delete('/gift-dislike/{giftid}/{userid}', 'dislike');
    });

    Route::controller(CategoryController::class)->group(function(){
        Route::get('/categories', 'categories');
        Route::get('/subcategories/{id}', 'subCategories');
        Route::get('/gift-add-category/{giftid}/{categoryid}', 'addGiftCategory');
        Route::get('/user-add-category/{userid}/{categoryid}', 'addUserCategory');
        Route::post('/category-add', 'addParent');
        Route::post('/subcategory-add', 'addSubCategoryt');
    });

    Route::controller(userController::class)->group(function(){
        Route::get('/userprofile/{id}', 'profile');
        Route::get('/user-list', 'list');
        Route::get('/user-remove/{id}', 'remove');
        Route::post('/user-update', 'update');
        Route::post('/user-search', 'search');
    });

    Route::controller(followController::class)->group(function(){
        Route::get('/followerlist/{id}', 'followers');
        Route::get('/followinglist/{id}', 'followings');
        Route::get('/follow-suggestion', 'suggestion');
        Route::get('/isfollow/{userid}/{followid}', 'isollow');
        Route::get('/follow/{userid}/{followid}', 'follow');
        Route::get('/unfollow/{userid}/{followid}', 'unfollow');
    });

    Route::controller(eventsController::class)->group(function(){
        Route::post('/event-add', 'create');
        Route::post('/event-update/{event_id}', 'update');
        Route::get('/event-remove/{event_id}', 'remove');
        Route::get('/event-user', 'list');
        Route::get('/event-followings_birthday', 'followingsBirthday');
        Route::get('/event-near-user', 'userNearEvents');
        Route::get('/event-detail/{event_id}', 'eventDetail');
        Route::get('/event-list', 'eventList');
        Route::get('/relationship-list', 'relationshipList');
    });
});
