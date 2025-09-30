<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountOrder extends Model
{
    use Auditable, SoftDeletes;

    public $table = 'discount_orders';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'coupon_discount_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function discount_coupon(): BelongsTo
    {
        return $this->belongsTo(CouponDiscount::class, 'coupon_discount_id');
    }

    public function amountDiscount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->discount_coupon->type) {
                    'PERCENTAGE' => $this->order->totalAmountItems * ($this->discount_coupon->amount / 100),
                    'VALUE'      => $this->discount_coupon->amount,
                    default      => 0,
                };
            }
        )->shouldCache();
    }
}
