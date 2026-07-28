<?php

use App\Http\Controllers\Admin\VerificationAdminController;
use App\Http\Controllers\Api\Admin\PayoutAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CraftsmanProfileController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PayoutController;

Route::post('/auth/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/craftsman/profile', [CraftsmanProfileController::class, 'store']);
    Route::get('/craftsman/profile', [CraftsmanProfileController::class, 'show']);

    Route::post('/verification/upload', [VerificationController::class, 'upload']);

    // ============ المحفظة (بلال) ============
    Route::get('/wallet', [WalletController::class, 'show']);
    Route::post('/wallet/hold', [WalletController::class, 'hold']);
    Route::post('/wallet/confirm', [WalletController::class, 'confirm']);
    Route::post('/wallet/release', [WalletController::class, 'release']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

    // ============ الاشتراكات (بلال) ============
    Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans']);
    Route::get('/subscriptions/current', [SubscriptionController::class, 'current']);
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);

    // ============ المدفوعات (بلال) ============
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::post('/payments/confirm-cash', [PaymentController::class, 'confirmCashPayment']);

    // ============ طلبات السحب (بلال) ============
    Route::get('/payouts', [PayoutController::class, 'index']);
    Route::post('/payouts', [PayoutController::class, 'store']);

    // ============ Admin فقط ============
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/verification-requests', [VerificationAdminController::class, 'pending']);
        Route::post('/verification-requests/{document}/approve', [VerificationAdminController::class, 'approve']);
        Route::post('/verification-requests/{document}/reject', [VerificationAdminController::class, 'reject']);

        // ============ طلبات السحب - Admin (بلال) ============
        Route::get('/payouts', [PayoutAdminController::class, 'pending']);
        Route::post('/payouts/{payout}/approve', [PayoutAdminController::class, 'approve']);
        Route::post('/payouts/{payout}/reject', [PayoutAdminController::class, 'reject']);
    });
});