<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{

    protected $table = 'notification_logs';

    protected $fillable = [
        'level',
        'message',
        'context'
    ];

    protected $casts = [
        'context' => 'array'
    ];
}
