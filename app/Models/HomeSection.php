<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'image',
        'image_alt',
        'image_title',
        'meta_description',
        'meta_title',
        'h1_text',
        'h2_text',
        'h3_text',
        'btn_text',
        'btn_link',
    ];
}
