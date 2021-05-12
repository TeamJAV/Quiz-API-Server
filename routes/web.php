<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login-modal', function () {
    return view('includes/modalLogin');
})->name('login-form.modal');

// Route for teacher
Route::group(['prefix' => 'teacher', 'middleware' => ['auth', 'verified']], function () {

    // Route main dashboard
    Route::get('/', "Teacher\DashboardController@index")->name('teacher.dashboard');

    // Resource route rooms
    Route::resource('/rooms', 'Teacher\RoomsController');

    Route::get('/quizzes', 'Teacher\QuizzesController@index')->name('quizzes.index');
    Route::get('/edit-quiz', 'Teacher\QuizzesController@create')->name('quizzes.create');
});


// Routes for student
Route::group(['prefix' => 'student'], function () {

    // Waiting room online
    Route::get('/wait', 'Student\WaitingRoomController@index')->name('student.wait')
        ->middleware(['room-auth']);

    // Login room name
    Route::match(['post', 'get'] , 'login/room-name',  'Student\StudentLoginController@loginRoom')
        ->name('student.login.room');

    Route::group(['middleware' => 'room-auth'], function(){

        // Login name student
        Route::match(['post', 'get'] , 'login/student-name',  'Student\StudentLoginController@loginStudentName')
            ->name('student.login.student-name');

        // Logout room
        Route::post('/logout-room', 'Student\StudentLoginController@logout')->name('student.logout.room');

        // Route actions in room
        Route::group(['middleware' => 'join-room', 'prefix' => '/go/{room}'], function (){
            Route::get('/', function () {
                return view('layouts.Student.dashboard');
            })->name('student.join');
        });
    });
});
