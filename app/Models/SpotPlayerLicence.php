<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotPlayerLicence extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'course_order_id',
        'spot_licence_id',
        'spot_key',
        'spot_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
