<?php

use App\Http\Controllers\API\WakafController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::fallback(fn () => response()->json(['message' => 'Not Found!'], 404));

Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\API',
    'middleware' => ['api', 'throttle:30,60', 'json.response']
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\API',
    'middleware' => ['api', 'auth:api', 'json.response']
], function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group([
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\API',
    'middleware' => ['api', 'auth:api', 'json.response']
], function () {
    // Profile
    Route::get('profile', [ProfileController::class, 'index']);
    Route::put('profile/update-password', [ProfileController::class, 'updatePassword']);
    Route::put('profile/update-profile', [ProfileController::class, 'updateProfile']);

    // Wakaf 
    Route::get('wakafs', [WakafController::class, 'index']);

    // Transaction
    Route::post('transaction', [WakafController::class, 'transaction']);
    Route::get('transaction/history', [WakafController::class, 'history']);
});
