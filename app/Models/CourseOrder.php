<?php

namespace App\Models;

use App\Enums\CourseOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CourseOrder extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'total_price',
        'discount_amount',
        'pay_amount',
        'status',
    ];

    protected $casts = [
        'status' => CourseOrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
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
