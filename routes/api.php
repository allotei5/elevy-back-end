<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\LoadingController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::get('/test', [LoadingController::class, 'loadPosts']);
Route::get('/postsCountByMedia', [ChartsController::class, 'postsCountByMedia']);
Route::get('/avgSentimentCountPerMedia/{id}', [ChartsController::class, 'avgSentimentCountPerMedia']);
Route::get('/avgSentimentCountPerPub/{id}', [ChartsController::class, 'avgSentimentCountPerPub']);
Route::get('/getAllMedia', [ChartsController::class, 'getAllMedia']);
Route::get('/postsCountByPublication', [ChartsController::class, 'postsCountByPublication']);
Route::get('/avgSentiments', [ChartsController::class, 'avgSentiments']);
Route::get('/avgSentimentsByDay', [ChartsController::class, 'avgSentimentsByDay']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);