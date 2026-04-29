<?php

use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\DesignOptionController;
use App\Http\Controllers\Api\MeasurementController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\LanguageController;



Route::prefix('language')->group(function () {
    Route::get('/', [LanguageController::class, 'index']);
    Route::get('/current', [LanguageController::class, 'current']);
    Route::post('/switch', [LanguageController::class, 'switch']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/users/me', [UserController::class, 'show']);
    Route::put('/users/me', [UserController::class, 'update']);
    Route::post('/users/me', [UserController::class, 'update']);

    Route::apiResource('addresses', AddressController::class);

    Route::get('/measurements', [MeasurementController::class, 'index']);
    Route::get('/measurements/{measurement}', [MeasurementController::class, 'show']);

    Route::apiResource('designs', DesignController::class);

    Route::post('/designs/{design}/images', [DesignController::class, 'updateImages']);


    Route::get('/design-options', [DesignOptionController::class, 'index']);
    Route::get('/design-options/{designOption}', [DesignOptionController::class, 'show']);

    Route::middleware('permission:manage design options')->group(function () {
        Route::post('/design-options', [DesignOptionController::class, 'store']);
        Route::put('/design-options/{designOption}', [DesignOptionController::class, 'update']);
        Route::patch('/design-options/{designOption}', [DesignOptionController::class, 'update']);
        Route::delete('/design-options/{designOption}', [DesignOptionController::class, 'destroy']);
    });
        Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

    Route::middleware('permission:manage all orders')->group(function () {
        Route::get('/orders/all', [OrderController::class, 'all']);
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    });

    Route::get('/wallet', [WalletController::class, 'show']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

    Route::middleware('permission:manage wallet')->group(function () {
        Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
        Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    });


    Route::post('/orders/{order}/payment', [PaymentController::class, 'processPayment']);

    // Create payment intent (for Stripe - In-App payment)
    Route::post('/orders/{order}/payment-intent', [PaymentController::class, 'createPaymentIntent']);

    // Create checkout session (for Stripe - Redirect to Stripe page)
    Route::post('/orders/{order}/checkout-session', [PaymentController::class, 'createCheckoutSession']);



    Route::get('/coupons/available', [CouponController::class, 'available']);
    Route::post('/coupons/validate', [CouponController::class, 'validate']);

    Route::middleware('permission:manage coupons')->group(function () {
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::post('/coupons', [CouponController::class, 'store']);
        Route::get('/coupons/{coupon}', [CouponController::class, 'show']);
        Route::put('/coupons/{coupon}', [CouponController::class, 'update']);
        Route::patch('/coupons/{coupon}', [CouponController::class, 'update']);
        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy']);
        Route::patch('/coupons/{coupon}/toggle', [CouponController::class, 'toggleStatus']);
        Route::get('/coupons/{coupon}/stats', [CouponController::class, 'stats']);
    });

    Route::post('orders/{order}/review', [App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::get('orders/{order}/review', [App\Http\Controllers\Api\ReviewController::class, 'show']);
    Route::get('reviews', [App\Http\Controllers\Api\ReviewController::class, 'index']);

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
    });

    Route::post('/update-fcm-token', [NotificationController::class, 'updateFCMToken']);

    Route::middleware('permission:manage all orders')->prefix('admin')->group(function () {
        Route::post('/test-notification', [App\Http\Controllers\Api\Admin\TestNotificationController::class, 'sendTestNotification']);
        Route::post('/send-notification', [App\Http\Controllers\Api\Admin\TestNotificationController::class, 'sendCustomNotification']);
        Route::post('/broadcast-notification', [App\Http\Controllers\Api\Admin\TestNotificationController::class, 'broadcastNotification']);
    });
});

Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook']);
