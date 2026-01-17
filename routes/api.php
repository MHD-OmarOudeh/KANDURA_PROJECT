<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\DesignOptionController;
use App\Http\Controllers\Api\MeasurementController;

/*
|--------------------------------------------------------------------------
| API Routes - Stage 1 & 2
|--------------------------------------------------------------------------
*/

// ==========================================
// Public routes (No authentication)
// ==========================================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ==========================================
// Protected routes (Requires authentication)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // ============ Auth Routes ============
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // ============ User Routes ============
    Route::get('/users/me', [UserController::class, 'show']);
    Route::put('/users/me', [UserController::class, 'update']);
    Route::post('/users/me', [UserController::class, 'update']); // for FormData with image

    // ============ Address Routes ============
    Route::apiResource('addresses', AddressController::class);

    // ============ Measurement Routes (Read Only) ============
    Route::get('/measurements', [MeasurementController::class, 'index']);
    Route::get('/measurements/{measurement}', [MeasurementController::class, 'show']);

    // ============ Design Routes ============
    // filter=my → user's own designs
    // filter=others → other users' designs
    Route::apiResource('designs', DesignController::class);

    // ============ Design Options Routes ============
    // All users can view (for creating designs)
    Route::get('/design-options', [DesignOptionController::class, 'index']);
    Route::get('/design-options/{designOption}', [DesignOptionController::class, 'show']);

    // Admin only routes for Design Options
    Route::middleware('permission:manage design options')->group(function () {
        Route::post('/design-options', [DesignOptionController::class, 'store']);
        Route::put('/design-options/{designOption}', [DesignOptionController::class, 'update']);
        Route::patch('/design-options/{designOption}', [DesignOptionController::class, 'update']);
        Route::delete('/design-options/{designOption}', [DesignOptionController::class, 'destroy']);
    });
});
