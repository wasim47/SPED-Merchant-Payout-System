<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const DELIVERED = 'delivered';
    public const PENDING   = 'pending';
    public const CANCELLED = 'cancelled';

    protected $fillable = [
        'merchant_id',
        'payout_period_id',
        'food_amount',
        'food_vat',
        'non_food_amount',
        'non_food_vat',
        'delivery_fee',
        'service_fee',
        'packaging_fee',
        'discount',
        'status'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function payoutPeriod()
    {
        return $this->belongsTo(MerchantPayoutPeriod::class, 'payout_period_id');
    }
}
