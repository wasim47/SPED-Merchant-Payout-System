<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;

class MerchantSeeder extends Seeder
{
    public function run()
    {
        Merchant::create([
            'name' => 'Pizza Place',
            'email' => 'pizza@sped.com',
            'phone' => '01710000000',
            'bank_account_number' => '123456789',
            'bank_name' => 'DBBL'
        ]);

        Merchant::create([
            'name' => 'Burger House',
            'email' => 'burger@sped.com',
            'phone' => '01810000000',
            'bank_account_number' => '987654321',
            'bank_name' => 'City Bank'
        ]);
    }
}
