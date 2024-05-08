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

Route::post('/register', [App\Http\Controllers\api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\api\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\api\AuthController::class, 'logout'])->middleware('auth:sanctum');

// All the routes protected by sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // http: //127.0.0.1:8000/api/tag_colors
    /// Route::resource('/tag-colors', App\Http\Controllers\api\TagColorController::class)
    /// ->middleware(['auth:sanctum', 'ability:check-status,api-access']);
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

// All the public routes
// http: //127.0.0.1:8000/api/boards
Route::resource('/boards', App\Http\Controllers\api\BoardController::class);
Route::resource('/tasks', App\Http\Controllers\api\TaskController::class);
Route::resource('/columns', App\Http\Controllers\api\ColumnController::class);
Route::resource('/task-comments', App\Http\Controllers\api\TaskCommentController::class);
Route::resource('/checklists', App\Http\Controllers\api\ChecklistController::class);
Route::resource('/checklist-items', App\Http\Controllers\api\ChecklistItemController::class);
Route::resource('/tag-colors', App\Http\Controllers\api\TagColorController::class);
Route::resource('/tags', App\Http\Controllers\api\TagController::class);
Route::resource('/translations', App\Http\Controllers\api\TranslationController::class);
