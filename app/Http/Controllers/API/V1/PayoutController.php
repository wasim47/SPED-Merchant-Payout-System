<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PayoutCalculationService;
use App\Models\MerchantPayoutPeriod;
use App\Models\Order;

class PayoutController extends Controller
{
    protected $service;

    public function __construct(PayoutCalculationService $service)
    {
        $this->service = $service;
    }

    /**
     * Get Payout Summary by period_id
     */
    public function summary(Request $request, $merchantId, $periodId)
    {
        $data = $this->service->calculateByPeriod($merchantId, $periodId);
        return response()->json($data);
    }

    /**
     * Lock Payout Period as paid
     */
    public function lockPeriod(Request $request, $merchantId, $periodId)
    {
        return DB::transaction(function () use ($merchantId, $periodId) {

            $period = MerchantPayoutPeriod::lockForUpdate()->findOrFail($periodId);

            if ($period->merchant_id !== (int)$merchantId) {
                abort(403, "Merchant not allowed");
            }

            if ($period->status === 'paid') {
                return response()->json(['message' => 'Already paid'], 409);
            }

            $period->update([
                'status' => 'paid',
                'locked_at' => now()
            ]);

            // Assign payout_period_id to included orders
            $period->orders()->update(['payout_period_id' => $period->id]);

            return response()->json(['message' => 'Payout locked and paid']);
        });
    }

    /**
     * Apply Adjustment (refund / penalty / compensation)
     */
    public function applyAdjustment(Request $request, $merchantId, $periodId)
    {
        $orderId = $request->order_id ?? null;

        // Validate order_id if provided
        if ($orderId && !Order::where('id', $orderId)->exists()) {
            return response()->json(['message' => "Order with ID {$orderId} not found."], 422);
        }

        // Fetch period
        $period = MerchantPayoutPeriod::findOrFail($periodId);

        if ($period->merchant_id !== (int)$merchantId) {
            abort(403, "Merchant not allowed for this period");
        }

        // If period already paid move to next open period
        if ($period->status === 'paid') {
            $period = MerchantPayoutPeriod::where('merchant_id', $merchantId)
                ->where('status', 'open')
                ->orderBy('start_date')
                ->firstOrFail();
        }

        // Prevent duplicate adjustment
        $exists = $period->adjustments()
            ->where('order_id', $orderId)
            ->where('type', $request->type)
            ->where('amount', $request->amount)
            ->where('reason', $request->reason ?? '')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Duplicate adjustment detected'], 409);
        }

        // Create adjustment
        $period->adjustments()->create([
            'merchant_id' => $merchantId,
            'order_id' => $orderId,
            'type' => $request->type,
            'amount' => $request->amount,
            'vat_amount' => $request->vat_amount ?? 0,
            'reason' => $request->reason ?? null,
        ]);

        return response()->json(['message' => 'Adjustment applied successfully']);
    }
}
