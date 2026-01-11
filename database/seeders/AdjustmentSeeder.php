<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adjustment;

class AdjustmentSeeder extends Seeder
{
    public function run()
    {
        // Refund of €5 for Pizza Place first order
        Adjustment::create([
            'merchant_id' => 1,
            'payout_period_id' => 1,
            'order_id' => 1,
            'type' => 'refund',
            'amount' => -5,
            'vat_amount' => -0.7,
            'reason' => 'Customer returned pizza'
        ]);
    }
}

