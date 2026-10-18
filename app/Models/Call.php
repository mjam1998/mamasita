<?php

namespace App\Models;

use App\Enums\CallStatus;
use App\Enums\Rate;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = [
        'monthly_subscription_id',
        'status',
        'pay_amount',
        'rate',
        'user_comment',
        'called_at',
        'start_at',
        'expire_at',
    ];

    protected $casts = [
        'status' => CallStatus::class,
        'rate' => Rate::class,
        'start_at' => 'datetime',
        'expire_at' => 'datetime',
    ];

    public function monthlySubscription()
    {
        return $this->belongsTo(MonthlySubscription::class);
    }
}
