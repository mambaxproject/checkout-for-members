<?php

namespace App\Models;

use App\Traits\{Auditable, HasSchemalessAttributes};
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class UpSell extends Model
{
    use Auditable;
    use HasSchemalessAttributes;
    use SoftDeletes;

    public $table = 'up_sells';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'product_id',
        'product_offer_id',
        'name',
        'description',
        'when_offer',
        'when_accept',
        'when_reject',
        'text_accept',
        'text_reject',
        'color_button_accept',
        'status',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_offer(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_offer_id');
    }

    public function textTranslatedWhenOffer(): Attribute
    {
        return Attribute::make(
            get: fn () => collect(config('products.whenOfferUpSell'))
                ->firstWhere('value', $this->when_offer)['name']
        )->shouldCache();
    }

    public function textTranslatedWhenAccept(): Attribute
    {
        return Attribute::make(
            get: fn () => collect(config('products.whenAcceptUpSell'))
                ->firstWhere('value', $this->when_accept)['name']
        )->shouldCache();
    }

    public function textTranslatedWhenReject(): Attribute
    {
        return Attribute::make(
            get: fn () => collect(config('products.whenRejectUpSell'))
                ->firstWhere('value', $this->when_reject)['name']
        )->shouldCache();
    }

    public function thanksPageRejectLink(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->when_reject == 'REDIRECT_TO_THANKS_PAGE' ? '#' : ($this->getValueSchemalessAttributes('urlDownSell') ?? '#')
        )->shouldCache();
    }
}
