<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Dashboard\AuthController as DashboardAuthController;
use App\Http\Controllers\Dashboard\AddressController as DashboardAddressController;
use App\Http\Controllers\Dashboard\DesignOptionController as DashboardDesignOptionController;
use App\Http\Controllers\Dashboard\DesignController as DashboardDesignController;
use App\Http\Controllers\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Dashboard\CouponController as DashboardCouponController;
use App\Http\Controllers\Dashboard\WalletController as DashboardWalletController;
use App\Http\Controllers\Dashboard\AdminController as DashboardAdminController;
use App\Http\Controllers\Dashboard\NotificationController as DashboardNotificationController;
use App\Http\Controllers\StripeRedirectController;


Route::get('/', function () {
    return view('');
});

Route::prefix('payment')->group(function () {
    Route::get('/success', [StripeRedirectController::class, 'showSuccess'])->name('payment.success');
    Route::get('/cancel', [StripeRedirectController::class, 'showCancel'])->name('payment.cancel');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [DashboardAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [DashboardAuthController::class, 'login'])->name('login.post');
    });

    Route::middleware(['auth', 'permission:access dashboard'])->group(function () {

        Route::get('/', function () {
            return view('dashboard.index');
        })->name('index');

        Route::post('/logout', [DashboardAuthController::class, 'logout'])->name('logout');

        Route::middleware('permission:manage all addresses')->group(function () {
            Route::resource('addresses', DashboardAddressController::class)->names('addresses');
        });

        Route::middleware('permission:manage design options')->group(function () {
            Route::resource('design-options', DashboardDesignOptionController::class)->names('design-options');
        });

        Route::middleware('permission:manage all designs')->group(function () {
            Route::get('/designs', [DashboardDesignController::class, 'index'])->name('designs.index');
            Route::get('/designs/{design}', [DashboardDesignController::class, 'show'])->name('designs.show');
        });

        Route::middleware('permission:manage all orders')->group(function () {
            Route::get('/orders', [DashboardOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [DashboardOrderController::class, 'show'])->name('orders.show');
            Route::put('/orders/{order}/status', [DashboardOrderController::class, 'updateStatus'])->name('orders.update-status');
            Route::post('/orders/{order}/cancel', [DashboardOrderController::class, 'cancel'])->name('orders.cancel');
        });

        Route::middleware('permission:manage coupons')->group(function () {
            Route::resource('coupons', DashboardCouponController::class)->names('coupons');
            Route::patch('/coupons/{coupon}/toggle', [DashboardCouponController::class, 'toggleStatus'])->name('coupons.toggle');
        });
        Route::middleware('permission:manage wallet')->group(function () {
            Route::get('/wallet', [DashboardWalletController::class, 'index'])->name('wallet.index');
            Route::get('/wallet/users/{user}', [DashboardWalletController::class, 'show'])->name('wallet.show');
            Route::post('/wallet/deposit', [DashboardWalletController::class, 'deposit'])->name('wallet.deposit');
            Route::post('/wallet/withdraw', [DashboardWalletController::class, 'withdraw'])->name('wallet.withdraw');
        });

        Route::middleware('role:super_admin')->group(function () {
            Route::resource('admins', DashboardAdminController::class)->names('admins');
            Route::patch('/admins/{admin}/toggle', [DashboardAdminController::class, 'toggleStatus'])->name('admins.toggle');
        });

        // Notifications Routes
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [DashboardNotificationController::class, 'index'])->name('index');
            Route::get('/unread-count', [DashboardNotificationController::class, 'unreadCount'])->name('unread-count');
            Route::post('/{id}/mark-as-read', [DashboardNotificationController::class, 'markAsRead'])->name('mark-as-read');
            Route::post('/mark-all-as-read', [DashboardNotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        });

        Route::post('/update-fcm-token', function(\Illuminate\Http\Request $request) {
            $request->validate(['fcm_token' => 'required|string']);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
            }

            if (!$user->hasAnyRole(['admin', 'super_admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'FCM notifications are only available for administrators'
                ], 403);
            }

            $user->fcm_token = $request->fcm_token;
            $user->save();
            return response()->json(['success' => true, 'message' => 'FCM token updated']);
        })->name('update-fcm-token');

    });
});

// Language Switcher Route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        // Save to session
        session(['locale' => $locale]);

        // If user is authenticated, update their preference in database
        if (auth()->check()) {
            auth()->user()->update(['preferred_locale' => $locale]);
        }
    }
    return redirect()->back();
})->name('language.switch');
