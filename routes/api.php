<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\TodoController;
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

Route::controller(AuthController::class)->middleware('throttle:6,1')->group(function () {
    Route::post('/auth/register', 'register')->name('auth.register');
    Route::post('/auth/send-verify-email', 'sendVerifyEmail')->name('auth.send-verify-email');
    Route::post('/auth/verify-email', 'verifyEmail')->name('auth.verify-email');
    Route::post('/auth/login', 'login')->name('auth.login');
    Route::middleware('auth:sanctum')->post('/auth/logout', 'logout')->name('auth.logout');
});

Route::controller(MeController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/me', 'show')->name('me.show');
    Route::put('/me', 'update')->name('me.update');
    Route::put('/me/change-password', 'changePassword')->name('me.change-password');
});

Route::middleware('auth:sanctum')->apiResource('todo', TodoController::class);

//* Socialite singin
Route::post('/socialite/singin', [SocialiteController::class, 'singin']);

//* Temporary File
Route::middleware('auth:sanctum')->post('/file', [FileController::class, 'file'])->name('temporary.file.upload');

//* Demo
// Route::post('/demo/upload', function (Request $request) {
//     $file = $request->file('file');

//     return [
//         'name' => $file->getClientOriginalName(),
//         'type' => $file->getClientMimeType(),
//         'size' => $file->getSize(),
//     ];
// });
