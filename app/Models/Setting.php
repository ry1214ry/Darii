<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'favicon',
        'footer_text',
        'contact_email',
        'contact_phone',
        'address',
        'google_map_embed',
        'whatsapp_link',
        'telegram_link',
    ];
}