<?php

namespace App\Models;

use App\Casts\CurrencyCast;
use App\Traits\{Auditable, HasScopeActive, HasStatusFormatted};
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use Illuminate\Support\Number;

class OrderBump extends Model
{
    use Auditable, HasScopeActive, HasStatusFormatted, SoftDeletes;

    public $table = 'order_bumps';

    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
        'payment_methods'   => 'array',
        'promotional_price' => CurrencyCast::class,
    ];

    protected $fillable = [
        'product_id',
        'product_offer_id',
        'name',
        'description',
        'promotional_price',
        'title_cta',
        'payment_methods',
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

    public function brazilianPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->promotional_price, 'BRL', 'pt-br')
        )->shouldCache();
    }
}
