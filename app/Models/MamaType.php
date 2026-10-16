<?php

namespace App\Models;

use App\Enums\MamaFiled;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;


class MamaType extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'status',
    ];

    protected $casts = [
        'type' => MamaFiled::class,
        'status' => Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
