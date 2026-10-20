<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyVideo extends Model
{
    protected $fillable = [
        'monthly_subscription_id',
        'video_id',
        'rate',
        'user_comment',
        'is_seen',
        'show_date',
    ];

    protected $casts = [
        'is_seen' => 'boolean',
        'show_date' => 'datetime',
    ];

    public function monthlySubscription()
    {
        return $this->belongsTo(MonthlySubscription::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
