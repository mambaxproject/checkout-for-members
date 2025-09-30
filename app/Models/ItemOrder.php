<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

class ItemOrder extends Model
{
    use Auditable, SoftDeletes;

    public $table = 'item_orders';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'type_product_id',
        'amount',
        'quantity',
        'name',
        'document_number',
        'type',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function brazilianPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->amount, 'BRL', 'pt-br')
        )->shouldCache();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
