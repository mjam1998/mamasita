<?php

namespace App\Models;

use App\Enums\TransactionDirection;
use App\Enums\TransactionType;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'amount',
        'direction',
        'type',
        'total_balance_after',
        'available_balance_after',
        'blocked_balance_after',
        'description',
        'referenceable_type',
        'referenceable_id',
    ];

    protected $casts = [
        'direction' => TransactionDirection::class,
        'type' => TransactionType::class,
    ];

    protected static function booted(): void
    {
        static::updating(function (Transaction $transaction) {
            throw new Exception('تراکنش‌های مالی ثبت‌شده غیرقابل ویرایش هستند.');
        });

        static::deleting(function (Transaction $transaction) {
            throw new Exception('تراکنش‌های مالی ثبت‌شده هرگز قابل حذف نیستند.');
        });
    }

    public function referenceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
