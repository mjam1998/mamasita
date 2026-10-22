<?php

namespace App\Models;

use App\Enums\DoulaSubscriptionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class DoulaSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'mama_id',
        'plan_id',
        'province_id',
        'city_id',
        'status',
        'total_price',
        'discount_amount',
        'pay_amount',
        'mama_share',
        'site_share',
        'mom_mobile_necessary',
        'user_description',
        'mama_description',
        'address',
        'doula_date',
    ];

    protected $casts = [
        'status' => DoulaSubscriptionStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mama()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function userPay(): MorphMany
    {
        return $this->morphMany(UserPay::class, 'referenceable');
    }

    public function successfulUserPay(): MorphOne
    {
        return $this->morphOne(UserPay::class, 'referenceable')
            ->where('is_paid', true)
            ->whereNotNull('paid_at')
            ->latestOfMany();
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
