<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PollaController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// Health check — no auth, no middleware, returns 200 when app is reachable
Route::get('/health', fn() => response()->json([
    'status' => 'ok',
    'timestamp' => now()->toIso8601String(),
]));

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('groups');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/cambiar-contrasena', [AuthController::class, 'passwordForm'])->name('password.form');
    Route::put('/cambiar-contrasena', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/partidos', [PollaController::class, 'groups'])->name('groups');
    Route::get('/configuracion', [PollaController::class, 'settings'])->name('settings');
    Route::get('/predicciones', [PollaController::class, 'predictions'])->name('predictions');
    Route::post('/predicciones', [PollaController::class, 'savePredictions']);
    Route::get('/tabla', [PollaController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/resultados', [PollaController::class, 'results'])->name('results');

    Route::middleware(EnsureAdmin::class)->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/registro', [AuthController::class, 'registerForm'])->name('register');
        Route::post('/registro', [AuthController::class, 'register'])->name('register.store');

        Route::get('/bitacora', [AdminController::class, 'logs'])->name('logs');

        Route::get('/configuracion', [AdminController::class, 'settings'])->name('settings');
        Route::post('/configuracion', [AdminController::class, 'saveSettings'])->name('settings.save');
        Route::get('/usuarios', [AdminController::class, 'users'])->name('users');
        Route::put('/usuarios/{user}/pago', [AdminController::class, 'updatePayment'])->name('users.payment');
        Route::get('/resultados', [AdminController::class, 'results'])->name('results');
        Route::post('/resultados', [AdminController::class, 'saveResults'])->name('results.save');
        Route::post('/calcular', [AdminController::class, 'calculate'])->name('calculate');
        Route::get('/resultados-detalle', [AdminController::class, 'resultsDetail'])->name('results.detail');
    });
});
