<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\TodoController;
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

// * auth
Route::controller(AuthController::class)
    ->middleware('throttle:6,1')
    ->group(function () {
        Route::post('/auth/register', 'register')->name('auth.register');
        Route::post('/auth/send-verify-email', 'sendVerifyEmail')->name('auth.send-verify-email');
        Route::post('/auth/verify-email', 'verifyEmail')->name('auth.verify-email');
        Route::post('/auth/login', 'login')->name('auth.login');
        Route::middleware('auth:sanctum')->post('/auth/logout', 'logout')->name('auth.logout');
    });

// * me
Route::controller(MeController::class)
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->group(function () {
        Route::get('/me', 'show')->name('me.show');
        Route::put('/me', 'update')->name('me.update');
        Route::put('/me/change-password', 'changePassword')->name('me.change-password');
        Route::post('/me/file', 'fileAdd')->name('me.file.add');
    });

// * todo
Route::middleware(['auth:sanctum', 'throttle:6,1'])
    ->apiResource('todo', TodoController::class);

// * post
Route::apiResource('post', PostController::class)
    ->middleware(['auth:sanctum', 'throttle:6,1']);

Route::name('post.')->controller(PostController::class)
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->group(function () {
        // * like
        Route::post('/post/{post}/like', 'like')->name('like');
        // * file
        Route::post('/post/{post}/file', 'fileAdd')->name('file.add');
        Route::delete('/post/{post}/file', 'fileDel')->name('file.del');
        // * comment
        Route::post('/post/{post}/comment', 'storeComment')->name('comment.store');
        Route::get('/post/{post}/comment', 'comment')->withoutMiddleware('auth:sanctum')->name('comment.list');
    });

// * comment
Route::apiResource('comment', CommentController::class)
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->only(['show', 'update', 'destroy']);

Route::name('comment.')
    ->controller(CommentController::class)
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->group(function () {
        // * like
        Route::post('/comment/{comment}/like', 'like')->name('like');
        // * reply list
        Route::get('/comment/{comment}/reply', 'reply')->name('reply');
    });

// * Socialite singin
Route::post('/socialite/singin', [SocialiteController::class, 'singin'])
    ->name('socialite.singin');

// * Temporary file
Route::middleware('auth:sanctum')->post('/file', [FileController::class, 'file'])
    ->name('file.temporary.upload');

// * Demo upload-file
Route::post('/demo/upload-file', function (Request $request) {
    $file = $request->file('file');

    return [
        'name' => $file->getClientOriginalName(),
        'type' => $file->getClientMimeType(),
        'size' => $file->getSize(),
    ];
})->name('demo.upload-file');


// * Firebase Auth
Route::name('firebase.')
    ->controller(FirebaseAuthController::class)
    ->middleware(['throttle:10,1'])
    ->group(function () {
        // * singin
        Route::post('/firebase/auth/singin', 'singin')->name('auth.singin');
    });
