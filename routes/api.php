<?php



use App\Http\Controllers\Admin\VerificationAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CraftsmanProfileController;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\WarrantyClaimController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

// Pricing (زياد) — عام، بدون تسجيل دخول
Route::get('/pricing/rules', [PricingController::class, 'rules']);
Route::get('/pricing/standardized-services', [PricingController::class, 'standardizedServices']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

// Ratings (زياد)
Route::post('/ratings', [RatingController::class, 'store']);
Route::get('/ratings/user/{userId}', [RatingController::class, 'userRatings']);

// Warranty Claims (زياد)
Route::get('/warranty-claims', [WarrantyClaimController::class, 'index']);
Route::post('/warranty-claims', [WarrantyClaimController::class, 'store']);
Route::get('/warranty-claims/{claim}', [WarrantyClaimController::class, 'show']);
 
    Route::post('/craftsman/profile', [CraftsmanProfileController::class, 'store']);
    Route::get('/craftsman/profile', [CraftsmanProfileController::class, 'show']);

    
    Route::post('/verification/upload', [VerificationController::class, 'upload']);

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/verification-requests', [VerificationAdminController::class, 'pending']);
        Route::post('/verification-requests/{document}/approve', [VerificationAdminController::class, 'approve']);
        Route::post('/verification-requests/{document}/reject', [VerificationAdminController::class, 'reject']);
    });
});
