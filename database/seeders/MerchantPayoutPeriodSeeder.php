<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MerchantPayoutPeriod;

class MerchantPayoutPeriodSeeder extends Seeder
{
    public function run()
    {
        // Pizza Place - Period 1 & 2
        MerchantPayoutPeriod::create([
            'merchant_id' => 1,
            'period_start' => '2026-01-01',
            'period_end' => '2026-01-15',
        ]);

        MerchantPayoutPeriod::create([
            'merchant_id' => 1,
            'period_start' => '2026-01-16',
            'period_end' => '2026-01-31',
        ]);

        // Burger House - Period 1 & 2
        MerchantPayoutPeriod::create([
            'merchant_id' => 2,
            'period_start' => '2026-01-01',
            'period_end' => '2026-01-15',
        ]);

        MerchantPayoutPeriod::create([
            'merchant_id' => 2,
            'period_start' => '2026-01-16',
            'period_end' => '2026-01-31',
        ]);
    }
}

