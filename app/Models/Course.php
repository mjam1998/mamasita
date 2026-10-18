<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
