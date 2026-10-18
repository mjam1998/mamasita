<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

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
}
