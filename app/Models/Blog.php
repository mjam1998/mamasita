<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'image_alt',
        'image_title',
        'meta_description',
        'meta_title',
        'keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getKeywordsArrayAttribute(): array
    {
        $decoded = json_decode($this->keywords, true);

        if (! is_array($decoded)) {
            return [];
        }

        return array_column($decoded, 'value');
    }
}
