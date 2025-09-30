<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationEvent extends Model
{
    protected $table = 'notification_events';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'desc'
    ];
}
