<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;

// Onboarding — público (no requiere auth)
Route::prefix('onboarding')->group(function () {
    Route::get('presets',                    [OnboardingController::class, 'presets']);
    Route::get('presets/{preset}/modules',   [OnboardingController::class, 'modules']);
    Route::get('modules',                    [OnboardingController::class, 'allModules']);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Verificación de email — llamada desde el frontend con el token del correo
Route::post('email/verify', [AuthController::class, 'verifyEmail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout',               [AuthController::class, 'logout']);
    Route::get('auth/me',                    [AuthController::class, 'me']);
    Route::post('email/verification-resend', [AuthController::class, 'resendVerification']);

    // Businesses
    Route::get('businesses',        [BusinessController::class, 'index']);
    Route::post('businesses',       [BusinessController::class, 'store']);
    Route::get('businesses/{slug}', [BusinessController::class, 'show']);
});
