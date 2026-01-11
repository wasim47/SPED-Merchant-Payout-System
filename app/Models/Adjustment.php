<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $fillable = [
        'merchant_id',
        'payout_period_id',
        'order_id',
        'type',
        'amount',
        'vat_amount',
        'reason'
    ];

    public function payoutPeriod()
    {
        return $this->belongsTo(MerchantPayoutPeriod::class, 'payout_period_id');
    }
}

