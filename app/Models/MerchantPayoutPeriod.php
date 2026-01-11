<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantPayoutPeriod extends Model
{
    protected $fillable = [
        'merchant_id',
        'start_date',
        'end_date',
        'status',
        'locked_at'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'payout_period_id'
        );
    }


    public function adjustments()
    {
        return $this->hasMany(Adjustment::class, 'payout_period_id');
    }
}
