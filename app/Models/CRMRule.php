<?php

namespace App\Models;

use App\Enums\{CRMEventTriggerEnum, CRMOriginEnum, StatusEnum};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CRMRule extends Model
{
    protected $table = 'crm_rules';

    protected $casts = [
        'origin'        => CRMOriginEnum::class,
        'event_trigger' => CRMEventTriggerEnum::class,
    ];

    protected $fillable = [
        'origin',
        'event_trigger',
        'funnel_id',
        'step_id',
        'funnel_name',
        'step_name',
        'status'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status == StatusEnum::ACTIVE->name,
        )->shouldCache();
    }
}
