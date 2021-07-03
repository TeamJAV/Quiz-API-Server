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

//Public route
Route::get('email/verify/{id}/{hash}', 'Api\Teacher\ApiVerificationController@verify')->name('verification.verify.api');
Route::get('email/resend', 'Api\Teacher\ApiVerificationController@resend')->name('verification.resend.api');
Route::get('time', 'Api\ApiTimeServer@index')->name('time_now');

//For teacher
Route::group(['prefix' => 'teacher', 'middleware' => ['cors', 'json.response', 'api'], 'namespace' => 'Api\Teacher'], function () {

    //API Result
    Route::group(['prefix' => 'result'], function () {
        Route::get('all_result', 'ResultController@allHistoryTest')->middleware('auth:api')->name('api.all-result');
        Route::get('detail_result/{id}', 'ResultController@detailHistory')->middleware('auth:api')->name('api.all-result');
        Route::get('detail_result/{result_id}/question/{question_copy_id?}', 'ResultController@getQuestionResultDetail')->middleware('auth:api')->name('api.question-result');
    });

    // API quiz
    Route::group(['prefix' => 'quiz'], function () {
        Route::post('edit_quiz/{id}', 'QuizController@editQuiz')->middleware('auth:api')->name('api.edit-quiz');
        Route::post('create_quiz', 'QuizController@createQuiz')->middleware('auth:api')->name('api.create-quiz');
        Route::get('list_quiz', 'QuizController@showAllQuiz')->middleware('auth:api')->name('api.all-quiz');
        Route::get('view_quiz/{id}', 'QuizController@getQuizById')->middleware('auth:api')->name('api.view-quiz');
        Route::get('search_quiz/{title?}', 'QuizController@searchQuiz')->middleware('auth:api')->name('api.view-quiz');
        Route::post('delete_quiz/{id}', 'QuizController@deleteQuiz')->middleware('auth:api')->name('api.delete-quiz');
    });

    //API question
    Route::group(['prefix' => 'question'], function () {
        Route::get('list_question/{id}', 'QuizController@showAllQuestion')->middleware('auth:api')->name('api.all-question');
        Route::post('create_question/{id}', 'QuizController@createQuestion')->middleware('auth:api')->name('api.create-question');
        Route::post('edit_question/{id}', 'QuizController@editQuestion')->middleware('auth:api')->name('api.edit-question');
        Route::post('delete_question/{id}', 'QuizController@deleteQuestion')->middleware('auth:api')->name('api.delete-question');
    });

    //Route auth
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'ApiAuthController@login')->name('api.login');
        Route::post('signup', 'ApiAuthController@signup')->name('api.signup');
        Route::post('logout', 'ApiAuthController@logout')->name('api.logout');
        Route::post('email/password', 'ApiAuthController@forgot')->name('api.forgot-password');
        Route::get('/', 'ApiUserController@index')->name('api.user-info');
        Route::put('update', 'ApiUserController@update')->name('api.user-update');

    // For student
    Route::group(['middleware' => ['auth:api', 'verified']], function () {
        //Route Room
        Route::group(['prefix' => 'room'], function () {
            Route::get('list/{search?}/{orderBy?}/{type?}', 'ApiRoomController@index')->name('api.room-list');
            Route::post('create', 'ApiRoomController@store')->name('api.room-store');
            Route::post('{room}/share', 'ApiRoomController@share')->name('api.room-share');
            Route::post('launch', 'ApiRoomController@launchRoom')->name('api.room-launch');
            Route::post('{room}/stop-launch', 'ApiRoomController@stopLaunchRoom')->name('api.room-stop');
            Route::post('{room}/delete', 'ApiRoomController@delete')->name('api.room-delete');
            Route::post('{room}/restore', 'ApiRoomController@restore')->name('api.room-restore');
        });
    });


});

// For student
Route::group(['prefix' => 'student', 'middleware' => ['cors', 'json.response', 'api'], 'namespace' => 'Api\Student'], function () {
    // Route user
    Route::get('ip', 'ApiExamController@ip');
    Route::get('join-room/{id}', 'ApiRoomController@joinRoom')->name('api.room-join');
    Route::post('join-room', 'ApiRoomController@joinRoomByName')->name('api.room-join-by-name');
    Route::post('register', 'ApiRoomController@register')->name('api.room-register')->middleware('room-auth');
    Route::group(['middleware' => ['exam-auth']], function () {
        Route::post('submit-answer', 'ApiExamController@store')->name('api.submit-answer');
        Route::post('result-test', 'ApiExamController@result')->name('api.result-test');

    });
});

Route::get('/', function () {
    return response()->json("Welcome to quiz API",200);
});
