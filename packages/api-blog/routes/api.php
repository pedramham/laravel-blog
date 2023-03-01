<?php

use Admin\ApiBolg\Http\Controllers\Api\AuthorizationController;
use Admin\ApiBolg\Http\Controllers\Api\CategoryController;
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
Route::middleware('auth:sanctum')->group( function () {
    Route::prefix('blog-api/category/v1/')->group(function () {
        Route::post('category-store',[CategoryController::class, 'store']);
        Route::put('category-edit',[CategoryController::class, 'edit']);
        Route::post('category-show',[CategoryController::class, 'show']);
        Route::get('category-list',[CategoryController::class, 'list']);
    })->name('blog-api');
});

Route::post('/blog-api/auth/v1/register', [AuthorizationController::class, 'createUser']);
Route::post('/blog-api/auth/v1/login', [AuthorizationController::class, 'loginUser']);
