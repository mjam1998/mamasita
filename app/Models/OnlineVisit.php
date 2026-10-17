<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OnlineVisit extends Model
{
    protected $fillable = [
        'user_id',
        'mama_id',
        'ticket_id',
        'plan_id',
        'status',
        'total_price',
        'discount_amount',
        'pay_amount',
        'mama_share',
        'site_share',
    ];

    protected $casts = [
        'status' => SubscriptionStatus::class,
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

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
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
}
