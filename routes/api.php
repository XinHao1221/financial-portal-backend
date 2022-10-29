<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\ConstantController;
use App\Http\Controllers\Api\Transaction\TransactionController;
use GuzzleHttp\Middleware;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
    Route::get('me', [AuthController::class, 'getCurrentUserDetails'])->middleware(['auth:sanctum']);
    Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Transaction
    Route::get('transactions/summary', [TransactionController::class, 'transactionSummary']);
    Route::resource('transactions', TransactionController::class);

    // Lookup
    Route::get('constant', ConstantController::class);
});


// Route::resource('transaction', TransactionController::class)->middleware(['auth:sanctum']);
// Route::post('transaction', [TransactionController::class, 'store'])->middleware(['auth:sanctum']);
