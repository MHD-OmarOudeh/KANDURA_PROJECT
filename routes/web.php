<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\AuthController as DashboardAuthController;
use App\Http\Controllers\Dashboard\AddressController as DashboardAddressController;
use App\Http\Controllers\Dashboard\DesignOptionController as DashboardDesignOptionController;
use App\Http\Controllers\Dashboard\DesignController as DashboardDesignController;

/*
|--------------------------------------------------------------------------
| Web Routes - Dashboard (Stage 1 & 2)
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', function () {
    return view('');
});

// Dashboard routes
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    // ========================================
    // Guest routes (Login)
    // ========================================
    Route::middleware('guest')->group(function () {
        Route::get('/login', [DashboardAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [DashboardAuthController::class, 'login'])->name('login.post');
    });

    // ========================================
    // Authenticated routes
    // ========================================
    Route::middleware(['auth', 'permission:access dashboard'])->group(function () {

        // Dashboard Home
        Route::get('/', function () {
            return view('dashboard.index');
        })->name('index');

        // Logout
        Route::post('/logout', [DashboardAuthController::class, 'logout'])->name('logout');

        // ========================================
        // Address Management (Stage 1)
        // ========================================
        Route::middleware('permission:manage all addresses')->group(function () {
            Route::resource('addresses', DashboardAddressController::class)->names('addresses');
        });

        // ========================================
        // Design Options Management (Stage 2 - Admin Only)
        // ========================================
        Route::middleware('permission:manage design options')->group(function () {
            Route::post('design-options/create',[DashboardDesignOptionController::class,'store'])->name("design-options.create");
            Route::resource('design-options', DashboardDesignOptionController::class)->names('design-options');
        });

        // ========================================
        // Designs Management (Stage 2 - Admin View All)
        // ========================================
        Route::middleware('permission:manage all designs')->group(function () {
            Route::get('/designs', [DashboardDesignController::class, 'index'])->name('designs.index');
            Route::get('/designs/{design}', [DashboardDesignController::class, 'show'])->name('designs.show');
        });
    });
});
// Language Switcher Route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');
