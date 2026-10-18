<?php

namespace App\Models;

use App\Enums\VideoType;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'file_path',
        'is_active',
        'description',
        'type',
        'sort',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => VideoType::class,
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
