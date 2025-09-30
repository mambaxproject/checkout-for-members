<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    protected $table = 'custom_notifications';
    public $timestamps = true;

    protected $fillable = [
        'type_id',
        'action_id',
        'event_id',
        'text_whatsapp',
        'dispatch_time',
        'url_embed',
        'status'
    ];
}
