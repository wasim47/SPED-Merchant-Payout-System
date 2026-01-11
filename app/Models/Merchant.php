<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = ['name','email','phone', 'bank_account_number','bank_name','status'];
    public function orders() {
        return $this->hasMany(Order::class);
    }
    public function payoutPeriods() {
        return $this->hasMany(MerchantPayoutPeriod::class);
    }
}
