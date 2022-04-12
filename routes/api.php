<?php

use App\Http\Controllers\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->middleware('throttle:6,1')->group(function () {
    Route::post('/auth/register', 'register')->name('auth.register');
    Route::post('/auth/send-verify-email', 'sendVerifyEmail');
    Route::post('/auth/verify-email', 'verifyEmail');
    Route::post('/auth/login', 'login')->name('auth.login');
    Route::middleware('auth:sanctum')->post('/auth/logout', 'logout')->name('auth.logout');
});
