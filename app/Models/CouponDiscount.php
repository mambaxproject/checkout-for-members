<?php

namespace App\Models;

use App\Casts\CurrencyCast;
use App\Enums\TypeDiscountEnum;
use App\Traits\{Auditable, HasScopeActive};
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\{Model, Relations\MorphToMany, SoftDeletes};
use Illuminate\Support\Number;

class CouponDiscount extends Model
{
    use Auditable, HasScopeActive, SoftDeletes;

    public $table = 'coupons_discount';

    public static array $searchable = [
        'code',
    ];

    protected $casts = [
        'start_at'            => 'datetime',
        'end_at'              => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'deleted_at'          => 'datetime',
        'payment_methods'     => 'array',
        'amount'              => CurrencyCast::class,
        'minimum_price_order' => CurrencyCast::class,
    ];

    protected $fillable = [
        'code',
        'name',
        'description',
        'amount',
        'type',
        'quantity',
        'start_at',
        'end_at',
        'minimum_price_order',
        'automatic_application',
        'once_per_customer',
        'newsletter_abandoned_carts',
        'only_first_order',
        'payment_methods',
        'status',
        'allow_affiliate_links',
    ];

    public function usage(): HasMany
    {
        return $this->hasMany(DiscountOrder::class, 'coupon_discount_id');
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'productable')->whereNull('parent_id');
    }

    public function offers(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'productable')->whereNotNull('parent_id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(DiscountOrder::class, 'discount_coupon_id', 'id');
    }

    public function typeDiscountTranslated(): Attribute
    {
        return Attribute::make(
            get: fn () => TypeDiscountEnum::getFromName($this->type),
        )->shouldCache();
    }

    public function amountFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === TypeDiscountEnum::PERCENTAGE->name
                ? Number::format($this->amount, 2) . '%'
                : Number::currency($this->amount, 'BRL', 'pt-br'),
        )->shouldCache();
    }

    public function isTypeValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === TypeDiscountEnum::VALUE->name
        )->shouldCache();
    }
}
