<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PollaController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('auth')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/groups', [PollaController::class, 'groups']);
        Route::get('/settings', [PollaController::class, 'settings']);
        Route::get('/predictions', [PollaController::class, 'predictions']);
        Route::post('/predictions', [PollaController::class, 'savePredictions']);
        Route::get('/leaderboard', [PollaController::class, 'leaderboard']);
        Route::get('/results', [PollaController::class, 'results']);

        Route::middleware(EnsureAdmin::class)->group(function (): void {
            Route::post('/register', [AuthController::class, 'register']);
            Route::get('/admin/settings', [AdminController::class, 'settings']);
            Route::post('/admin/settings', [AdminController::class, 'saveSettings']);
            Route::get('/admin/users', [AdminController::class, 'users']);
            Route::put('/admin/users/{user}/payment', [AdminController::class, 'updatePayment']);
            Route::post('/admin/results', [AdminController::class, 'saveResults']);
            Route::post('/admin/calculate', [AdminController::class, 'calculate']);
            Route::get('/admin/results-detail', [AdminController::class, 'resultsDetail']);
        });
    });
});
