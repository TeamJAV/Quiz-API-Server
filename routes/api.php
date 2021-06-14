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

Route::get('email/verify/{id}/{hash}', 'Api\Teacher\ApiVerificationController@verify')->name('verification.verify.api');
Route::get('email/resend', 'Api\Teacher\ApiVerificationController@resend')->name('verification.resend.api');
Route::get('time', 'Api\ApiTimeServer@index')->name('time_now');

Route::group(['prefix' => 'teacher', 'middleware' => ['cors', 'json.response', 'api'], 'namespace' => 'Api\Teacher'], function () {

    // Auth route

    Route::group(['prefix' => 'quiz'], function () {
        Route::post('edit_quiz/{id}', 'Api\QuizController@editQuiz')->middleware('auth:api')->name('api.edit-quiz');
        Route::post('create_quiz', 'Api\QuizController@createQuiz')->middleware('auth:api')->name('api.create-quiz');
        Route::get('list_quiz', 'Api\QuizController@showAllQuiz')->middleware('auth:api')->name('api.all-quiz');
        Route::post('delete_quiz/{id}', 'Api\QuizController@deleteQuiz')->middleware('auth:api')->name('api.delete-quiz');
    });

    Route::group(['prefix' => 'question'], function () {
        Route::get('list_question', 'Api\QuizController@showAllQuestion')->middleware('auth:api')->name('api.all-question');
        Route::post('create_question/{id}', 'Api\QuizController@createQuestion')->middleware('auth:api')->name('api.create-question');
        Route::post('edit_question/{id}', 'Api\QuizController@editQuestion')->middleware('auth:api')->name('api.edit-question');
        Route::post('delete_question/{id}', 'Api\QuizController@deleteQuestion')->middleware('auth:api')->name('api.delete-question');
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Api\ApiAuthController@login')->name('api.login');
        Route::post('signup', 'Api\ApiAuthController@signup')->name('api.signup');
        Route::post('logout', 'Api\ApiAuthController@logout')->middleware('auth:api')
            ->name('api.logout');
        Route::post('email/password', 'Api\ApiAuthController@forgot')->name('api.forgot-password');

        Route::post('login', 'ApiAuthController@login')->name('api.login');
        Route::post('signup', 'ApiAuthController@signup')->name('api.signup');
        Route::post('logout', 'ApiAuthController@logout')->middleware('auth:api')->name('api.logout');
        Route::post('email/password', 'ApiAuthController@forgot')->name('api.forgot-password');
    });

    // For teacher
    Route::group(['middleware' => ['auth:api', 'verified']], function () {
        //User
        Route::get('/', 'ApiUserController@index')->name('api.user-info');
        Route::put('update', 'ApiUserController@update')->name('api.user-update');

        //Room
        Route::group(['prefix' => 'room'], function () {
            Route::get('list/{search?}/{orderBy?}/{type?}', 'ApiRoomController@index')->name('api.room-list');
            Route::post('create', 'ApiRoomController@store')->name('api.room-store');
            Route::post('{room}/share', 'ApiRoomController@share')->name('api.room-share');
            Route::post('launch', 'ApiRoomController@launchRoom')->name('api.room-launch');
            Route::post('{room}/stop-launch', 'ApiRoomController@stopLaunchRoom')->name('api.room-stop');
            Route::post('{room}/delete', 'ApiRoomController@delete')->name('api.room-delete');
        });
    });


});

Route::group(['prefix' => 'student', 'middleware' => ['cors', 'json.response', 'api'], 'namespace' => 'Api\Student'], function () {
    Route::get('join-room/{id}', 'ApiRoomController@joinRoom')->name('api.room-join');
    Route::post('register', 'ApiRoomController@register')->name('api.room-register')->middleware('room-auth');
});



//Route::get("/all-user", function (){
////    return new \App\Http\Resources\UserCollection(\App\User::with("rooms")->where("id", 1)->first());
//    return \App\Http\Resources\UserCollection::collection(\App\User::with("rooms")->get());
//});
