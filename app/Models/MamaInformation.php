<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MamaInformation extends Model
{
    protected $fillable = [
        'user_id',
        'province_id',
        'city_id',
        'education_file',
        'national_card_file',
        'bank_name',
        'card_number',
        'iban_number',
        'status',
        'image',
        'image_alt',
        'image_title',
        'doula_price',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
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
