<?php

namespace App\Models;

use App\Enums\AdminPayStatus;
use App\Enums\AdminPayType;
use Illuminate\Database\Eloquent\Model;

class AdminPay extends Model
{
    protected $fillable = [
        'user_id',
        'track_number',
        'amount',
        'type',
        'status',
        'bank_name',
        'iban_number',
        'card_number',
        'paid_at',
    ];

    protected $casts = [
        'status' => AdminPayStatus::class,
        'type' => AdminPayType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
