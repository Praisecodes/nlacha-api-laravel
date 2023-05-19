<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\CreateUser;
use App\Http\Controllers\Auth\Logout;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::post('/signup', [CreateUser::class, 'index']);
Route::post('/login', [Login::class, 'index']);

Route::middleware('auth:sanctum')->group(function (){
    Route::any('/logout', [Logout::class, 'index']);
});

// Route::any('/logout', [Logout::class, 'index']);
