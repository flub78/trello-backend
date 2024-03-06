<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// http: //127.0.0.1:8000/api/boards
Route::get('/boards', 'App\Http\Controllers\api\BoardController@index');
Route::resource('/boards', App\Http\Controllers\api\BoardController::class);
// Route::post('/boards', 'App\Http\Controllers\api\BoardController@store');
