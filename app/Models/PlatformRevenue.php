<?php

namespace App\Models;

use App\Enums\PlatformRevenueDirection;
use App\Enums\PlatformRevenueType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PlatformRevenue extends Model
{

    protected $fillable = [
        'amount',
        'direction',
        'type',
        'description',
        'referenceable_type',
        'referenceable_id',
    ];

    protected $casts = [
        'direction' => PlatformRevenueDirection::class,
        'type' => PlatformRevenueType::class,
    ];


    public function referenceable(): MorphTo
    {
        return $this->morphTo();
    }
}
