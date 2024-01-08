<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/category', CategoryController::class);
Route::apiResource('/post', PostController::class);
Route::get('/post/category/{id}', [PostController::class, 'baseCategory']);
Route::get('/post/user/{id}', [PostController::class, 'baseUser']);
Route::get('/post/user', [PostController::class, 'currentUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/{id}', [AuthController::class, 'detail']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
