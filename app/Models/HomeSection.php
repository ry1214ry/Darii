<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'short_description',
        'hero_image',
        'hero_video',
        'hire_me_link',
        'cv_button_text',
        'status',
    ];
}