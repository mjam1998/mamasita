<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLimit extends Model
{
    protected $fillable = [
        'course_id',
        'p0_all_device',
        'p1_windows',
        'p2_macos',
        'p3_linux',
        'p4_android',
        'p5_ios',
        'p6_webapp',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
