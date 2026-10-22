<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'rate',
        'body',
        'admin_response',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

}
