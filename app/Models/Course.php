<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'spot_player_code',
        'slug',
        'price',
        'discount_amount',
        'description',
        'is_active',
        'image',
        'image_alt',
        'image_title',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function freeVideos()
    {
        return $this->hasMany(Video::class);
    }

    public function courseLimit()
    {
        return $this->hasOne(CourseLimit::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function approvedComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->where('status', Status::Active)
            ->with(['user']);
    }
}
