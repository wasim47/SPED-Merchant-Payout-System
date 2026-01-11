<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Pizza Place Period 1
        Order::create([
            'merchant_id' => 1,
            'food_amount' => 10,
            'food_vat' => 1.4,
            'non_food_amount' => 10,
            'non_food_vat' => 2.55,
            'delivery_fee' => 2,
            'service_fee' => 2,
            'packaging_fee' => 2,
            'discount' => 2,
            'status' => 'delivered',
            'delivered_at' => '2026-01-03'
        ]);

        Order::create([
            'merchant_id' => 1,
            'food_amount' => 20,
            'food_vat' => 2.8,
            'non_food_amount' => 5,
            'non_food_vat' => 1.275,
            'delivery_fee' => 3,
            'service_fee' => 2,
            'packaging_fee' => 2,
            'discount' => 1,
            'status' => 'delivered',
            'delivered_at' => '2026-01-10'
        ]);

        // Burger House Period 1
        Order::create([
            'merchant_id' => 2,
            'food_amount' => 12,
            'food_vat' => 1.68,
            'non_food_amount' => 8,
            'non_food_vat' => 2.04,
            'delivery_fee' => 2,
            'service_fee' => 1,
            'packaging_fee' => 2,
            'discount' => 2,
            'status' => 'delivered',
            'delivered_at' => '2026-01-05'
        ]);
    }
}
