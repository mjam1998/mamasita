<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'national_code',
        'mobile',
        'password',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function mamaInformation()
    {
        return $this->hasOne(MamaInformation::class);
    }

    public function mamaTypes()
    {
        return $this->hasMany(MamaType::class);
    }

    public function userPaies()
    {
        return $this->hasMany(UserPay::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function userMonthlySubscriptions()
    {
        return $this->hasMany(MonthlySubscription::class, 'user_id');
    }

    public function mamaMonthlySubscriptions()
    {
        return $this->hasMany(MonthlySubscription::class, 'mama_id');
    }

    public function userDoulaSubscriptions()
    {
        return $this->hasMany(DoulaSubscription::class, 'user_id');
    }

    public function mamaDoulaSubscriptions()
    {
        return $this->hasMany(DoulaSubscription::class, 'mama_id');
    }

    public function adminPays()
    {
        return $this->hasMany(AdminPay::class);
    }
}
