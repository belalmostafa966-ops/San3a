<?php

/**
 * دي الـ routes الخاصة بجزء إسراء بس.
 * انسخي المحتوى ده جوة ملف routes/api.php الأساسي بتاع المشروع
 * (أو include الملف ده من جوه api.php لو حابة تسيبيه منفصل).
 */

use App\Http\Controllers\Admin\VerificationAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CraftsmanProfileController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Support\Facades\Route;

// ============ Auth (مفتوحة، من غير تسجيل دخول) ============
Route::post('/auth/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

// ============ محتاجة تسجيل دخول (Sanctum token) ============
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // بيانات الصنايعي
    Route::post('/craftsman/profile', [CraftsmanProfileController::class, 'store']);
    Route::get('/craftsman/profile', [CraftsmanProfileController::class, 'show']);

    // رفع مستندات التوثيق
    Route::post('/verification/upload', [VerificationController::class, 'upload']);

    // ============ Admin فقط ============
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/verification-requests', [VerificationAdminController::class, 'pending']);
        Route::post('/verification-requests/{document}/approve', [VerificationAdminController::class, 'approve']);
        Route::post('/verification-requests/{document}/reject', [VerificationAdminController::class, 'reject']);
    });
});
