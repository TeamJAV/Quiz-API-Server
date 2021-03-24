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


Route::group(['prefix' => 'login-form'], function(){
    // Open modal Login
    Route::get('/modal', function () {
        return view('includes/modalLogin');
    })->name('login-form.modal');

    // Open login page for student
    Route::get('/student', function () {
        return view('layouts/Student/login');
    })->name('login-form.student');
});

// Route for teacher
Route::group(['prefix' => 'teacher', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', "Teacher\DashboardController@index")->name('teacher.dashboard');
});


// Route for student
Route::group(['prefix' => 'student'], function () {
    Route::get('/', function (){

    })->name('student.dashboard');
});
