<?php

namespace App\Listeners;

use App\Events\UserRoleAssigned;
use App\Models\City;
use App\Models\Province;

class CreateMamaProfileAndWallet
{
    public function handle(UserRoleAssigned $event): void
    {
        $user = $event->user;

        // فقط اگر کاربر در حال حاضر نقش mama دارد اجرا شود
        if ($user->hasRole('mama')) {

            // ۱. ساخت پروفایل مامایی در صورت عدم وجود
            if (! $user->mamaInformation()->exists()) {
                $user->mamaInformation()->create([
                    'province_id' => Province::query()->value('id'),
                    'city_id' => City::query()->value('id'),
                    'status' => 0, // وضعیت اولیه
                ]);
            }

            // ۲. ساخت کیف پول در صورت عدم وجود
            $user->wallet()->firstOrCreate([], [
                'total_balance' => 0,
                'available_balance' => 0,
                'blocked_balance' => 0,
            ]);
        }
    }
}
