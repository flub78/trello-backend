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
Route::resource('/boards', App\Http\Controllers\api\BoardController::class);
Route::put('/boards/{id}/update', [App\Http\Controllers\api\BoardController::class, 'update']);

Route::resource('/tasks', App\Http\Controllers\api\TaskController::class);
Route::resource('/columns', App\Http\Controllers\api\ColumnController::class);
Route::resource('/task-comments', App\Http\Controllers\api\TaskCommentController::class);
Route::resource('/checklists', App\Http\Controllers\api\ChecklistController::class);
Route::resource('/checklist-items', App\Http\Controllers\api\ChecklistItemController::class);
Route::resource('/tag-colors', App\Http\Controllers\api\TagColorController::class);
Route::resource('/tags', App\Http\Controllers\api\TagController::class);
