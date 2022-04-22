<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FileController;
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
Route::controller(AuthController::class)->middleware('throttle:6,1')->group(function () {
    Route::post('/auth/register', 'register')->name('auth.register');
    Route::post('/auth/send-verify-email', 'sendVerifyEmail')->name('auth.send-verify-email');
    Route::post('/auth/verify-email', 'verifyEmail')->name('auth.verify-email');
    Route::post('/auth/login', 'login')->name('auth.login');
    Route::middleware('auth:sanctum')->post('/auth/logout', 'logout')->name('auth.logout');
});

// * me
Route::controller(MeController::class)->middleware(['auth:sanctum', 'throttle:6,1'])->group(function () {
    Route::get('/me', 'show')->name('me.show');
    Route::put('/me', 'update')->name('me.update');
    Route::put('/me/change-password', 'changePassword')->name('me.change-password');
});

// * todo
Route::middleware(['auth:sanctum', 'throttle:6,1'])->apiResource('todo', TodoController::class);

// * post
Route::name('post.')->controller(PostController::class)->middleware(['auth:sanctum', 'throttle:6,1'])->group(function () {
    // * resource
    Route::apiResource('post', PostController::class);
    // * file
    Route::post('/post/{post}/file', 'fileAdd')->name('file.add');
    Route::delete('/post/{post}/file', 'fileDel')->name('file.del');
    // * comment
    Route::post('/post/{post}/comment', 'storeComment')->name('comment.store');
});
Route::name('post.')->controller(PostController::class)->middleware(['throttle:6,1'])->group(function () {
    // * post-comment
    Route::get('/post/{post}/comment', 'comment')->name('comment.list');
});

// * Socialite singin
// Route::post('/socialite/singin', [SocialiteController::class, 'singin']);

// * Temporary file
Route::middleware('auth:sanctum')->post('/file', [FileController::class, 'file'])->name('file.temporary.upload');

// * Demo file
// Route::post('/demo/upload', function (Request $request) {
//     $file = $request->file('file');

//     return [
//         'name' => $file->getClientOriginalName(),
//         'type' => $file->getClientMimeType(),
//         'size' => $file->getSize(),
//     ];
// });
