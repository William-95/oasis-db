<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// user
Route::get('/users',[UserController::class,'readUser']);
Route::post('/users',[UserController::class,'createUser']);
Route::put('/users/{id}',[UserController::class,'updateUser']);
Route::delete('/users/{id}',[UserController::class,'deleteUser']);

// find user
Route::post('/user',[UserController::class,'findUser']);


// dog
Route::get('/dogs',[DogController::class,'readDog']);
Route::post('/dogs',[DogController::class,'createDog']);
Route::put('/dogs/{id}',[DogController::class,'updateDog']);
Route::delete('/dogs/{id}',[DogController::class,'deleteDog']);

//find dog
Route::post('/dog',[DogController::class,'findDog']);
 
//oneDog
Route::get('/dog/{id}',[DogController::class,'oneDog']);
