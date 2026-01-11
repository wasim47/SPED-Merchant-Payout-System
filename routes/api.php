<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PayoutController;

Route::prefix('v1')->group(function () {
    // Merchant Payout Summary
    Route::get('/merchants/{merchantId}/payout-periods/{periodId}', [PayoutController::class, 'summary']);

    // Lock Payout
    Route::post('/merchants/{merchantId}/payout-periods/{periodId}/lock', [PayoutController::class, 'lockPeriod']);

    // Apply Adjustment
    Route::post('/merchants/{merchantId}/payout-periods/{periodId}/adjustment', [PayoutController::class, 'applyAdjustment']);
});

