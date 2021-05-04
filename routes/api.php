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

Route::get("/all-user", function (){
//    return new \App\Http\Resources\UserCollection(\App\User::with("rooms")->where("id", 1)->first());
    return \App\Http\Resources\UserCollection::collection(\App\User::with("rooms")->get());
});
