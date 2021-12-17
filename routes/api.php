<?php

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



Route::group(['middleware' => 'api'], function ($router) {
    Route::post('register', 'JWTAuthController@register');
    Route::post('login', 'JWTAuthController@login');
    Route::post('logout', 'JWTAuthController@logout');
    Route::post('refresh', 'JWTAuthController@refresh');
    Route::get('profile', 'JWTAuthController@profile');

    Route::post('forgot-password', 'JWTAuthController@forgotPassword');
    Route::post('reset-password', 'JWTAuthController@resetPassword');
    Route::post('change-password', 'JWTAuthController@ChangePassword');
});

Route::middleware(['api','jwtmiddleware'])->group(function ($router) {
    Route::get('profile', 'Api\GeneralController@profile');
    Route::get('categories', 'Api\GeneralController@categories');
    Route::get('cms-pages', 'Api\GeneralController@cmsPages');
    
    Route::post('update-profile', 'Api\ProfileController@updateProfile');
    Route::post('email-change', 'Api\ProfileController@EmailChange');
    Route::get('get-plans-list', 'Api\ProfileController@GetPlanList');
    Route::get('get-category-list', 'Api\ProfileController@GetCategoryList');
    Route::post('upload-video', 'Api\ProfileController@UploadVideo');
    Route::get('get-dashboard', 'Api\ProfileController@GetDashboard');
    Route::get('get-videolist-by-category/{id}', 'Api\ProfileController@GetVideoListByCategory');
    Route::post('question-answer', 'Api\ProfileController@QuestionAnswer');
    Route::get('get-subscription-list', 'Api\ProfileController@SubscriptionList');
    Route::get('transaction-history', 'Api\ProfileController@TransactionHistory');
    Route::get('notification-list', 'Api\ProfileController@NotificationList');
    Route::get('question-list', 'Api\ProfileController@QuestionAnswerList');
    Route::post('video-detail', 'Api\ProfileController@VideoDetail');

});
