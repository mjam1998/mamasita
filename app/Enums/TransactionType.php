<?php

namespace App\Enums;

enum TransactionType: int
{
    case Deposit = 1;
    case Withdraw = 2;
    case Block = 3;
    case Unblock = 4;
    case Settle = 5;
    case DepositBlocked = 6;

    public function label(): string
    {
        return match ($this) {
            self::Deposit => 'واریز مستقیم به کیف پول',
            self::Withdraw => 'برداشت مستقیم از کیف پول',
            self::Block => ' مسدودسازی مبلغ کیف پول',
            self::Unblock => 'آزادسازی مبلغ کیف پول',
            self::Settle => 'برداشت از مبلغ مسدود شده کیف پول',
            self::DepositBlocked => 'واریز مسقیم به بلاک',
        };
    }
}
