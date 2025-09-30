<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopRequestLog extends Model
{

    protected $fillable = [
        'url',
        'content',
        'response',
        'status_code',
    ];

    protected $casts = [
        'content' => 'json',
        'response' => 'json',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

}
