<?php

use Illuminate\Support\Facades\Artisan;
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
    Route::group(['prefix' => 'result', 'middleware' => ['auth:api', 'verified']], function () {
        Route::get('all_result', 'ResultController@allHistoryTest')->name('api.all-result');
        Route::get('detail_result/{result_test}', 'ResultController@detailHistory')->name('api.detail-result');
        Route::get('detail_result/{result_id}/question/{question_copy_id?}', 'ResultController@getQuestionResultDetail')->name('api.question-result');
    });

    // API quiz
    Route::group(['prefix' => 'quiz', 'middleware' => ['auth:api', 'verified']], function () {
        Route::post('edit_quiz/{id}', 'QuizController@editQuiz')->name('api.edit-quiz');
        Route::post('create_quiz', 'QuizController@createQuiz')->name('api.create-quiz');
        Route::get('list_quiz', 'QuizController@showAllQuiz')->name('api.all-quiz');
        Route::get('view_quiz/{id}', 'QuizController@getQuizById')->name('api.view-quiz');
        Route::get('search_quiz/{title?}', 'QuizController@searchQuiz')->name('api.search-quiz');
        Route::post('delete_quiz/{id}', 'QuizController@deleteQuiz')->name('api.delete-quiz');
    });

    //API question
    Route::group(['prefix' => 'question', 'middleware' => ['auth:api', 'verified']], function () {
        Route::get('list_question/{id}', 'QuizController@showAllQuestion')->name('api.all-question');
        Route::post('create_question/{id}', 'QuizController@createQuestion')->name('api.create-question');
        Route::post('edit_question/{id}', 'QuizController@editQuestion')->name('api.edit-question');
        Route::post('delete_question/{id}', 'QuizController@deleteQuestion')->name('api.delete-question');
    });

    //Route auth
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'ApiAuthController@login')->name('api.login');
        Route::post('signup', 'ApiAuthController@signup')->name('api.signup');
        Route::post('email/password', 'ApiAuthController@forgot')->name('api.forgot-password');

        Route::group(['middleware' => ['auth:api', 'verified']], function () {
            Route::post('logout', 'ApiAuthController@logout')->name('api.logout');
            Route::get('/', 'ApiUserController@index')->name('api.user-info');
            Route::put('update', 'ApiUserController@update')->name('api.user-update');
        });
    });

    // For teacher
    Route::group(['middleware' => ['auth:api', 'verified']], function () {
        //Route Room
        Route::group(['prefix' => 'room'], function () {
            Route::get('list/{orderBy?}/{type?}', 'ApiRoomController@index')->name('api.room-list-paginate');
            Route::get('list-all/{orderBy?}/{type?}', 'ApiRoomController@indexGetAll')->name('api.room-list-all');
            Route::post('create', 'ApiRoomController@store')->name('api.room-store');
            Route::post('{room}/share', 'ApiRoomController@share')->name('api.room-share');
            Route::post('launch', 'ApiRoomController@launchRoom')->name('api.room-launch');
            Route::post('{room}/stop-launch', 'ApiRoomController@stopLaunchRoom')->name('api.room-stop');
            Route::post('{room}/delete', 'ApiRoomController@delete')->name('api.room-delete');
            Route::post('{room}/restore', 'ApiRoomController@restore')->name('api.room-restore');
        });
        // Route result
        Route::group(['prefix' => 'result-live'], function () {
            Route::get('{room}', 'ApiResultLiveController@getResultRoom')->name('api.result-live');
        });
    });

});

// For student
Route::group(['prefix' => 'student', 'middleware' => ['cors', 'json.response', 'api'], 'namespace' => 'Api\Student'], function () {
    Route::get('ip', 'ApiExamController@ip')->name('api.client-api-student');
    Route::get('join-room/{id}', 'ApiRoomController@joinRoom')->name('api.room-join');
    Route::post('join-room', 'ApiRoomController@joinRoomByName')->name('api.room-join-by-name');
    Route::post('register', 'ApiRoomController@register')->name('api.room-register')->middleware('room-auth');
    Route::group(['middleware' => ['exam-auth', 'room-auth']], function () {
        Route::get('', 'ApiExamController@index')->name('api.content-quiz');
        Route::get('info', 'ApiExamController@infoStudent')->name('api.info-student');
        Route::post('submit-answer', 'ApiExamController@store')->name('api.submit-answer');
        Route::post('finished', 'ApiExamController@finishedExam')->name('api.finished-exam');
    });
    Route::post('result-test/{id}', 'ApiExamController@result')->name('api.result-test');
});

Route::get('/', function () {
    return response()->json([
        "message" => "Welcome to quiz API",
        "status" => 200,
        "data" => [
            'image_path' => asset("storage/image"),
            'users' => \App\Http\Resources\UserCollection::collection(\App\User::all())
        ]
    ], 200);
});

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    dd("Cache clear");
});
