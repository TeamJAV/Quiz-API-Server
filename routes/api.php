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

Route::get('email/verify/{id}/{hash}', 'Api\ApiVerificationController@verify')->name('verification.verify.api');
Route::get('email/resend', 'Api\ApiVerificationController@resend')->name('verification.resend.api');


Route::group(['prefix' => 'teacher', 'middleware' => ['cors', 'json.response', 'api'], 'namespace' => 'Api'], function () {

    // Auth route
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'ApiAuthController@login')->name('api.login');
        Route::post('signup', 'ApiAuthController@signup')->name('api.signup');
        Route::post('logout', 'ApiAuthController@logout')->middleware('auth:api')->name('api.logout');
        Route::post('email/password', 'ApiAuthController@forgot')->name('api.forgot-password');
    });

    // For teacher
    Route::group(['middleware' => ['auth:api', 'verified']], function (){
        //User
        Route::get('/', 'ApiUserController@index')->name('api.user-info');
        Route::put('/update', 'ApiUserController@update')->name('api.user-update');

        //Room
        Route::get('/room/{search?}', 'ApiRoomController@index')->name('api.room-list');
        Route::post('/room-create', 'ApiRoomController@store')->name('api.room-store');
    });
});




//Route::get("/all-user", function (){
////    return new \App\Http\Resources\UserCollection(\App\User::with("rooms")->where("id", 1)->first());
//    return \App\Http\Resources\UserCollection::collection(\App\User::with("rooms")->get());
//});
