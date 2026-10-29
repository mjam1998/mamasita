<?php

namespace App\Models;

use App\Enums\MamaFiled;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'image_alt',
        'image_title',
        'meta_description',
        'meta_title',
        'price',
        'discount_amount',
        'mama_share',
        'type',
        'month_period',
        'is_active',
        'description',
    ];

    protected $casts = [
        'type' => MamaFiled::class,
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function ($plan) {
            $plan->update([
                'slug' => $plan->slug . '_deleted_' . time()
            ]);
        });

    }
}
