<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCartsTracking extends Model
{
    public $table = 'abandoned_carts_tracking';

    protected $fillable = [
        'abandoned_cart_id',
        'utm_source',
        'utm_campaign',
        'utm_medium',
        'utm_term',
        'utm_content'
    ];
}
