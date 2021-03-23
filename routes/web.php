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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'login-form'], function(){

    Route::get('/modal', function () {
        return view('includes/modalLogin');
    })->name('login-form.modal');

    Route::get('/student', function () {
        return view('layouts/Student/login');
    })->name('login-form.student');
});
