<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Wallet $wallet) {
            throw new Exception('کیف پول مالی هرگز قابل حذف نیست.');
        });
    }

    /**
     * واریز و شارژ کیف پول
     */
    public function deposit(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('مبلغ واریز باید بزرگتر از صفر باشد.');
        }

        // افزایش هم‌زمان کل موجودی و موجودی در دسترس
        $this->increment('total_balance', $amount);
        $this->increment('available_balance', $amount);
    }

    /**
     * برداشت یا خرید از کیف پول (از موجودی آزاد)
     */
    public function withdraw(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('مبلغ برداشت باید بزرگتر از صفر باشد.');
        }

        if ($this->available_balance < $amount) {
            throw new Exception('موجودی قابل برداشت کیف پول کافی نیست.');
        }

        // کسر هم‌زمان از کل موجودی و موجودی در دسترس
        $this->decrement('available_balance', $amount);
        $this->decrement('total_balance', $amount);
    }

    /**
     * مسدود کردن بخشی از موجودی (مثلاً برای درخواست تسویه حساب یا گرویی)
     */
    public function blockFunds(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('مبلغ مسدودی باید بزرگتر از صفر باشد.');
        }

        if ($this->available_balance < $amount) {
            throw new Exception('موجودی در دسترس برای مسدودسازی کافی نیست.');
        }

        $this->decrement('available_balance', $amount);
        $this->increment('blocked_balance', $amount);
    }

    /**
     * آزادسازی وجه مسدود شده (برگشت به موجودی در دسترس)
     */
    public function unblockFunds(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('مبلغ آزادسازی باید بزرگتر از صفر باشد.');
        }

        if ($this->blocked_balance < $amount) {
            throw new Exception('مبلغ درخواستی بیشتر از کل موجودی مسدود شده است.');
        }

        $this->decrement('blocked_balance', $amount);
        $this->increment('available_balance', $amount);
    }

    /**
     * واریز مستقیم وجه به حالت مسدود (مثلاً پول امن / اسکرو / ودیعه سفارش)
     */
    public function depositBlocked(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('مبلغ واریزی باید بزرگتر از صفر باشد.');
        }

        // افزایش هم‌زمان کل موجودی و موجودی مسدود شده
        // available_balance بدون تغییر می‌ماند
        $this->increment('total_balance', $amount);
        $this->increment('blocked_balance', $amount);
    }

    /**
     * کسر مستقیم از وجه مسدود شده
     */
    public function settleBlocked(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('مبلغ تسویه باید بزرگتر از صفر باشد.');
        }

        if ($this->blocked_balance < $amount) {
            throw new Exception('مبلغ تسویه بیشتر از موجودی مسدود شده است.');
        }

        $this->decrement('blocked_balance', $amount);
        $this->decrement('total_balance', $amount);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
