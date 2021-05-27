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

Route::group(['prefix' => 'teacher', 'middleware' => ['cors', 'json.response', 'api']], function () {

    // Auth route
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Api\ApiAuthController@login')->name('api.login');
        Route::post('signup', 'Api\ApiAuthController@signup')->name('api.signup');
        Route::post('logout', 'Api\ApiAuthController@logout')->middleware('auth:api')
            ->name('api.logout');
        Route::post('email/password', 'Api\ApiAuthController@forgot')->name('api.forgot-password');
        Route::post('edit_quiz/{id}', 'Api\QuizController@editQuiz')->middleware('auth:api')->name('api.edit-quiz');
        Route::post('create_quiz', 'Api\QuizController@createQuiz')->middleware('auth:api')->name('api.create-quiz');
        Route::get('list_quiz', 'Api\QuizController@showAllQuiz')->middleware('auth:api')->name('api.all-quiz');
        Route::post('delete_quiz/{id}', 'Api\QuizController@deleteQuiz')->middleware('auth:api')->name('api.delete-quiz');
        Route::get('list_question', 'Api\QuizController@showAllQuestion')->middleware('auth:api')->name('api.all-question');
        Route::post('create_question/{id}', 'Api\QuizController@createQuestion')->middleware('auth:api')->name('api.create-question');
        Route::post('edit_question/{id}', 'Api\QuizController@editQuestion')->middleware('auth:api')->name('api.edit-question');
        Route::post('delete_question/{id}', 'Api\QuizController@deleteQuestion')->middleware('auth:api')->name('api.delete-question');
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:api', 'verified']);


//Route::get("/all-user", function (){
////    return new \App\Http\Resources\UserCollection(\App\User::with("rooms")->where("id", 1)->first());
//    return \App\Http\Resources\UserCollection::collection(\App\User::with("rooms")->get());
//});
