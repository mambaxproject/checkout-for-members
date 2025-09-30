<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationAction extends Model
{
    protected $table = 'notification_actions';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'product_id',
        'user_id',
        'status'
    ];
}
