<?php

namespace App\Models;

use App\Observers\AbandonedCartObserver;
use App\Enums\{PaymentMethodEnum, StatusAbandonedCartEnum};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne, HasOneThrough};
use Illuminate\Database\Eloquent\{Attributes\ObservedBy, Builder, Model, SoftDeletes};
use Illuminate\Support\{Carbon, Number};

#[ObservedBy(AbandonedCartObserver::class)]
class AbandonedCart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'amount',
        'payment_method',
        'status',
        'order_id',
        'product_id',
        'client_abandoned_cart_uuid',
        'email_notification_sent',
        'whatsapp_notification_sent',
        'link_checkout',
        'infosProduct',
        'affiliate_id',
    ];

    protected $casts = [
        'status'       => StatusAbandonedCartEnum::class,
        'infosProduct' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function lastTracking(): HasOne
    {
        return $this->hasOne(AbandonedCartsTracking::class, 'abandoned_cart_id')->oldestOfMany();
    }

    public function traces(): HasMany
    {
        return $this->hasMany(AbandonedCartsTracking::class, 'abandoned_cart_id');
    }

    public function linkCheckout(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value . '?a=' . $this->id . '&utm_source=abandoned_cart&utm_campaign=abandoned_cart',
        )->shouldCache();
    }

    public function scopeSearchPeriod(Builder $query, $period): Builder
    {
        [$start_date, $final_date] = explode(' - ', $period);

        $start_date = Carbon::createFromFormat('d/m/Y', $start_date)->startOfDay();
        $final_date = Carbon::createFromFormat('d/m/Y', $final_date)->endOfDay();

        return $query->whereBetween($this->getTable() . '.created_at', [$start_date, $final_date]);
    }

    public function paymentMethodTranslated(): Attribute
    {
        return Attribute::make(
            get: fn () => PaymentMethodEnum::getFromName($this->payment_method)
        )->shouldCache();
    }

    public function brazilianAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->amount, 'BRL', 'pt-br')
        )->shouldCache();
    }

    public function shop(): HasOneThrough
    {
        return $this->hasOneThrough(
            Shop::class,
            Product::class,
            'id',
            'id',
            'product_id',
            'shop_id'
        )->withTrashedParents();
    }

    public function scopeFilterByUser(Builder $query, string $value): Builder
    {
        return $query->whereAny(
            ['name', 'email', 'phone_number'],
            'LIKE',
            "%$value%"
        );
    }

    public function scopeAllForUser(Builder $builder): Builder
    {
        $shopId            = user()?->shop()?->id;
        $affiliateIdsUser  = user()->affiliates->pluck('id')->toArray();
        $coProducerIdsUser = user()?->coproducers->pluck('id')->toArray();

        return $builder->where(function ($query) use ($affiliateIdsUser, $shopId, $coProducerIdsUser) {
            $query->whereIn('affiliate_id', $affiliateIdsUser)
                ->orWhereHas('shop', function (Builder $builder) use ($shopId) {
                    $builder->where('shops.id', $shopId);
                })
                ->orWhereHas('product.parentProduct', function (Builder $builder) use ($coProducerIdsUser) {
                    $builder->whereHas('coproducers', function (Builder $builder) use ($coProducerIdsUser) {
                        $builder->whereIn('coproducers.id', $coProducerIdsUser);
                    });
                });
        });
    }

    public function scopeFilterByPaymentMethod(Builder $query, string $value): Builder
    {
        return $query->where('payment_method', $value);
    }

    public function scopeFromShop(Builder $builder): Builder
    {
        $shopId = user()?->shop()?->id;

        return $builder->whereHas('shop', function (Builder $builder) use ($shopId) {
            $builder->where('shops.id', $shopId);
        });
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }
}
