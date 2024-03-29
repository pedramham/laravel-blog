<?php

use Admin\ApiBolg\Http\Controllers\Api\AuthorizationController;
use Admin\ApiBolg\Http\Controllers\Api\CategoryController;
use Admin\ApiBolg\Http\Controllers\Api\CommentController;
use Admin\ApiBolg\Http\Controllers\Api\PostController;
use Admin\ApiBolg\Http\Controllers\Api\SettingController;

use Admin\ApiBolg\Http\Controllers\Api\VideoCategoryController;
use Admin\ApiBolg\Http\Controllers\Api\VideoController;
use Admin\ApiBolg\Http\Controllers\Api\VideoCourseController;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('blog-api/category/v1/')->group(function () {
        Route::post('store', [CategoryController::class, 'store']);
        Route::put('edit', [CategoryController::class, 'edit']);
        Route::post('show', [CategoryController::class, 'show']);
        Route::post('list', [CategoryController::class, 'list']);
        Route::delete('soft-delete', [CategoryController::class, 'softDelete']);
        Route::delete('delete', [CategoryController::class, 'delete']);
        Route::put('restore-delete', [CategoryController::class, 'restoreDelete']);
    });

    Route::prefix('blog-api/post/v1/')->group(function () {
        Route::post('store', [PostController::class, 'store']);
        Route::put('edit', [PostController::class, 'edit']);
        Route::post('list', [PostController::class, 'list']);
        Route::post('show', [PostController::class, 'show']);
        Route::delete('soft-delete', [PostController::class, 'softDelete']);
        Route::delete('delete', [PostController::class, 'delete']);
        Route::put('restore-delete', [PostController::class, 'restoreDelete']);
    });

    Route::prefix('blog-api/comment/v1/')->group(function () {
        Route::post('store', [CommentController::class, 'store']);
        Route::put('edit', [CommentController::class, 'edit']);
        Route::post('list', [CommentController::class, 'list']);
        Route::delete('soft-delete', [CommentController::class, 'softDelete']);
        Route::put('restore-delete', [CommentController::class, 'restoreDelete']);
    });

    Route::prefix('blog-api/setting/v1/')->group(function () {
        Route::post('store', [SettingController::class, 'store']);
        Route::post('show', [SettingController::class, 'show']);
    });
    //Video Category
    Route::prefix('blog-api/video-category/v1/')->group(function () {
        Route::post('store', [VideoCategoryController::class, 'store']);
        Route::put('edit', [VideoCategoryController::class, 'edit']);
        Route::post('show', [VideoCategoryController::class, 'show']);
        Route::post('list', [VideoCategoryController::class, 'list']);
        Route::delete('soft-delete', [VideoCategoryController::class, 'softDelete']);
        Route::delete('delete', [VideoCategoryController::class, 'delete']);
        Route::put('restore-delete', [VideoCategoryController::class, 'restoreDelete']);
    });

    //Video Course
    Route::prefix('blog-api/video-course/v1/')->group(function () {
        Route::post('store', [VideoCourseController::class, 'store']);
        Route::put('edit', [VideoCourseController::class, 'edit']);
        Route::post('show', [VideoCourseController::class, 'show']);
        Route::post('list', [VideoCourseController::class, 'list']);
        Route::delete('soft-delete', [VideoCourseController::class, 'softDelete']);
        Route::delete('delete', [VideoCourseController::class, 'delete']);
        Route::put('restore-delete', [VideoCourseController::class, 'restoreDelete']);
    });

    //Video
    Route::prefix('blog-api/video/v1/')->group(function () {
        Route::post('store', [VideoController::class, 'store']);
        Route::put('edit', [VideoController::class, 'edit']);
        Route::post('show', [VideoController::class, 'show']);
        Route::post('list', [VideoController::class, 'list']);
        Route::delete('soft-delete', [VideoController::class, 'softDelete']);
        Route::delete('delete', [VideoController::class, 'delete']);
        Route::put('restore-delete', [VideoController::class, 'restoreDelete']);
    });

});

Route::post('/blog-api/auth/v1/register', [AuthorizationController::class, 'createUser']);
Route::post('/blog-api/auth/v1/login', [AuthorizationController::class, 'loginUser']);
