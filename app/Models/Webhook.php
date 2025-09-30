<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasScopeActive;
use App\Traits\HasStatusFormatted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Webhook extends Model
{
    use Auditable, SoftDeletes, HasScopeActive, HasStatusFormatted;

    protected $fillable = [
        'name',
        'url',
        'status'
    ];

    protected $with = [
        'events'
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(WebhookEvent::class, 'event_webhook');
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'productable');
    }

}
