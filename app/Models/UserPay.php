<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserPay extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'authority',
        'track_number',
        'bank_ref_number',
        'is_paid',
        'paid_at',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function referenceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adminPay()
    {
        return $this->hasOne(AdminPay::class);
    }
}
