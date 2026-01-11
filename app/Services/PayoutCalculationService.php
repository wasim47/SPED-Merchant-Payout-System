<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Adjustment;
use App\Models\MerchantPayoutPeriod;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayoutCalculationService
{
    /**
     * Calculate payout for a merchant by period_id
     */
    public function calculateByPeriod(int $merchantId, int $periodId): array
    {
        $period = MerchantPayoutPeriod::findOrFail($periodId);

        // Ensure the period belongs to the merchant
        if ($period->merchant_id !== $merchantId) {
            abort(403, "Merchant not allowed for this period");
        }

        // Delivered orders for this period and not yet locked
        $orders = Order::where('merchant_id', $merchantId)
            ->where('status', Order::DELIVERED)
            ->whereNull('payout_period_id')
            ->whereBetween('delivered_at', [$period->start_date, $period->end_date])
            ->get();

        $foodTotal = 0;
        $nonFoodTotal = 0;
        $deliveryFee = 0;
        $serviceFee = 0;
        $packagingFee = 0;
        $discountTotal = 0;

        foreach ($orders as $order) {
            $foodTotal += $order->food_amount;
            $nonFoodTotal += $order->non_food_amount;
            $deliveryFee += $order->delivery_fee;
            $serviceFee += $order->service_fee;
            $packagingFee += $order->packaging_fee;
            $discountTotal += $order->restaurant_discount;
        }

        $totalFees = $deliveryFee + $serviceFee + $packagingFee;
        $grossAmount = $foodTotal + $nonFoodTotal + $totalFees;

        // Split ratio
        $ratioFood = $foodTotal / max(($foodTotal + $nonFoodTotal), 1);
        $ratioNonFood = 1 - $ratioFood;

        // VAT
        $totalFoodVat = ($foodTotal * 0.14) + (($totalFees * $ratioFood) * 0.14);
        $totalNonFoodVat = ($nonFoodTotal * 0.255) + (($totalFees * $ratioNonFood) * 0.255);
        $totalVat = round($totalFoodVat + $totalNonFoodVat, 2);

        // Admin commission (example 20%)
        $adminCommission = round(($foodTotal + $nonFoodTotal) * 0.20, 2);

        // Adjustments (refunds, penalties, compensation) for this merchant not yet assigned to a period
        $adjustments = Adjustment::where('merchant_id', $merchantId)
            ->whereNull('payout_period_id')
            ->sum('amount');

        $netBeforeCommission = $grossAmount - $discountTotal;
        $finalTransfer = round($netBeforeCommission - $adminCommission + $adjustments, 2);

        return [
            'period_id' => $period->id,
            'total_orders' => $orders->count(),
            'gross_amount' => round($grossAmount, 2),
            'vat' => [
                'food_vat' => round($totalFoodVat, 2),
                'non_food_vat' => round($totalNonFoodVat, 2),
                'total_vat' => $totalVat,
            ],
            'restaurant_discount' => round($discountTotal, 2),
            'admin_commission' => $adminCommission,
            'adjustments' => round($adjustments, 2),
            'final_transfer_amount' => $finalTransfer,
        ];
    }
}
