<?php

namespace App\Models;

use App\Enums\{PaymentMethodEnum, PaymentTypeProductEnum};
use App\Events\OrderUpdated;
use App\Traits\{Auditable, HasSchemalessAttributes};
use BeyondCode\Comments\Traits\HasComments;
use DateTimeInterface;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Support\{Carbon, Number};
use Illuminate\Support\Facades\Crypt;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class Order extends Model implements HasMedia
{
    use Auditable;
    use HasComments;
    use HasSchemalessAttributes;
    use InteractsWithMedia;
    use SoftDeletes;

    public $table = 'orders';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'shop_id',
        'user_id',
        'client_orders_uuid',
        'affiliate_id',
        'coproducer_id',
        'amount',
        'affiliate_amount',
        'net_amount',
        'card_token_customer',
        'first_amount',
        'utm_link_id'
    ];

    public static array $statusForPaid = ['captured', 'paid', 'paid_out', 'payment_accept', 'received', 'active', 'confirmed', 'ok'];

    public static array $statusForPending = ['pending', 'authorized_pending_capture', 'partial_capture', 'waiting_capture', 'waiting_for_approval'];

    public static array $statusForFailed = ['not_authorized', 'failed', 'error_on_voiding', 'error_on_refunding', 'with_error', 'card_previously_rejected'];

    public static array $statusForUnpaid = ['unpaid'];
    public static array $statusForCanceled = ['canceled', 'voided', 'partial_void', 'waiting_cancellation'];

    public static array $statusForOverdue = ['overdue'];

    public static array $statusForRequestRefund = ['refunded_requested'];

    public static array $statusForRefunded = ['refunded', 'partial_refunded'];

    public static array $statusForChargeback = ['chargeback_requested', 'chargeback'];

    protected static function booted(): void
    {
        static::updated(function ($order) {
            event(new OrderUpdated($order)); // Dispara um evento sempre que o modelo for atualizado
        });
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class, 'order_id', 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class, 'order_id', 'id')->oldestOfMany();
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemOrder::class, 'order_id', 'id');
    }

    public function item(): HasOne
    {
        return $this->hasOne(ItemOrder::class, 'order_id', 'id')->oldestOfMany();
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(DiscountOrder::class, 'order_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id')->withTrashed();
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function coproducer(): BelongsTo
    {
        return $this->belongsTo(Coproducer::class, 'coproducer_id');
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(WebhookLog::class, 'order_id', 'id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(CommissioningOrder::class, 'order_id');
    }

    public function abandonedCarts(): HasMany
    {
        return $this->hasMany(AbandonedCart::class, 'order_id');
    }

    public function paymentMethod(): Attribute
    {
        return Attribute::make(function () {
            get:
            return PaymentMethodEnum::getFromName($this->payments?->last()?->payment_method);
        })->shouldCache();
    }

    public function paymentStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match (true) {
                $this->isPaid()          => 'Pago',
                $this->isPending()       => 'Pendente',
                $this->isCanceled()      => 'Cancelado',
                $this->isFailed()        => 'Falha',
                $this->isRequestRefund() => 'Solicitação de reembolso',
                $this->isRefunded()      => 'Reembolso',
                $this->isChargeback()    => 'Chargeback',
                $this->isUnpaid()        => 'Não pago',
                $this->isOverdue()       => 'Vencido',
                default                  => 'Não identificado',
            }
        )->shouldCache();
    }

    public function classCssPaymentStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match (true) {
                $this->isPaid()          => 'text-primary',
                $this->isPending()       => 'text-amber-400',
                $this->isCanceled()      => 'text-red-800',
                $this->isFailed()        => 'text-red-800',
                $this->isRefunded()      => 'text-red-800',
                $this->isRequestRefund() => 'text-red-800',
                $this->isChargeback()    => 'text-red-800',
                $this->isUnpaid()        => 'text-red-800',
                $this->isOverdue()       => 'text-red-800',
                default                  => 'text-amber-400',
            }
        )->shouldCache();
    }

    public function paymentStatusOriginal(): Attribute
    {
        return Attribute::make(function () {
            get: return $this->payments?->last()?->payment_status ?? 'Não identificado';
        })->shouldCache();
    }

    public static function getTextByPaymentStatus(string $status): string
    {
        $status = strtolower($status);

        return match (true) {
            in_array($status, self::$statusForFailed)        => 'Falha',
            in_array($status, self::$statusForCanceled)      => 'Cancelado',
            in_array($status, self::$statusForPaid)          => 'Pago',
            in_array($status, self::$statusForPending)       => 'Pendente',
            in_array($status, self::$statusForRequestRefund) => 'Solicitação de reembolso',
            in_array($status, self::$statusForRefunded)      => 'Reembolso',
            in_array($status, self::$statusForChargeback)    => 'Chargeback',
            in_array($status, self::$statusForUnpaid)        => 'Não pago',
            in_array($status, self::$statusForOverdue)       => 'Vencido',
            default                                          => '',
        };
    }

    public function isPaid(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForPaid));
    }

    public function isPending(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForPending));
    }

    public function isCanceled(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForCanceled));
    }

    public function isFailed(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForFailed));
    }

    public function isRequestRefund(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForRequestRefund));
    }

    public function isRefunded(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForRefunded));
    }

    public function isChargeback(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForChargeback));
    }

    public function isUnpaid(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForUnpaid));
    }

    public function isOverdue(): bool
    {
        return in_array(strtolower($this->paymentStatusOriginal), array_map('strtolower', self::$statusForOverdue));
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeFilterByPaymentStatus(Builder $query, string $value): Builder
    {
        return $query->whereHas('payments', function ($query) use ($value) {
            $values = [
                'paid'           => self::$statusForPaid,
                'pending'        => self::$statusForPending,
                'failed'         => self::$statusForFailed,
                'canceled'       => self::$statusForCanceled,
                'request_refund' => self::$statusForRequestRefund,
                'refunded'       => self::$statusForRefunded,
                'chargeback'     => self::$statusForChargeback,
                'unpaid'         => self::$statusForUnpaid,
                'overdue'        => self::$statusForOverdue,
            ];

            $value = strtolower($value);

            $query->whereIn('payment_status', $values[$value]);
        });
    }

    public function scopeFilterByPaymentMethod(Builder $query, string $value): Builder
    {
        return $query->whereHas('payments', fn ($query) => $query->where('payment_method', $value));
    }

    public function scopeFilterByUser(Builder $query, string $value): Builder
    {
        return $query->whereHas('user', function ($query) use ($value) {
            $query->whereAny(
                ['name', 'email', 'phone_number', 'document_number'],
                'LIKE',
                "%$value%"
            );
        });
    }

    public function scopeFromShop(Builder $builder): Builder
    {
        $shopId = user()?->shop()?->id;

        return $builder->where($this->getTable() . '.shop_id', $shopId);
    }

    public function scopeFromAffiliate(Builder $builder): Builder
    {
        $affiliateIdsUser = user()->affiliates->pluck('id')->toArray();

        return $builder->whereIntegerInRaw('affiliate_id', $affiliateIdsUser);
    }

    public function scopeFromCoproducer(Builder $builder): Builder
    {
        $coproducerUsernameBanking = user()?->shop()?->username_banking;
        $coProducerIdsUser         = user()?->coproducers->pluck('id')->toArray();

        return $builder->whereNotNull('attributes->splitGateway')
            ->whereJsonContains('attributes->splitGateway', [
                'username'        => $coproducerUsernameBanking,
                'splitTypePerson' => 'CO_PRODUCER',
            ])->when($coProducerIdsUser, function ($query) use ($coProducerIdsUser) {
                $query->orWhereIntegerInRaw('coproducer_id', $coProducerIdsUser);
            });
    }

    public function scopeFromType(Builder $builder, string $type): Builder
    {
        return match ($type) {
            'shop'       => $builder->fromShop(),
            'affiliate'  => $builder->fromAffiliate(),
            'coproducer' => $builder->fromCoproducer(),
            default      => $builder,
        };
    }

    public function scopeAllForUser(Builder $builder): Builder
    {
        $shopId                    = user()?->shop()?->id;
        $affiliateIdsUser          = user()->affiliates->pluck('id')->toArray();
        $coproducerUsernameBanking = user()?->shop()?->username_banking;
        $coProducerIdsUser         = user()?->coproducers->pluck('id')->toArray();

        return $builder->where(function ($query) use ($shopId, $affiliateIdsUser, $coproducerUsernameBanking, $coProducerIdsUser) {
            $query->where($this->getTable() . '.' . 'shop_id', $shopId)
                ->when($affiliateIdsUser, function ($query) use ($affiliateIdsUser) {
                    $query->orWhereIntegerInRaw('affiliate_id', $affiliateIdsUser);
                })
                ->when($coproducerUsernameBanking || $coProducerIdsUser, function ($query) use ($coproducerUsernameBanking, $coProducerIdsUser) {
                    $query->orWhere(function (Builder $query) use ($coproducerUsernameBanking, $coProducerIdsUser) {
                        $query->whereNotNull('orders.attributes->splitGateway')
                            ->whereJsonContains('orders.attributes->splitGateway', [
                                'username'        => $coproducerUsernameBanking,
                                'splitTypePerson' => 'CO_PRODUCER',
                            ])->when($coProducerIdsUser, function ($query) use ($coProducerIdsUser) {
                                $query->orWhereIntegerInRaw('coproducer_id', $coProducerIdsUser);
                            });
                    });
                });
        });
    }

    public function scopeAllByUser(Builder $builder, int $userId): Builder
    {
        $user                  = User::find($userId);
        $shopId                = $user?->shop()?->id;
        $affiliateIdsUser      = $user?->affiliates->pluck('id')->toArray();
        $coproducerIdsProducts = $user?->coproducers->pluck('id')->toArray();

        return $builder->where(function ($query) use ($shopId, $affiliateIdsUser, $coproducerIdsProducts) {
            $query->where($this->getTable() . '.' . 'shop_id', $shopId)
                ->orWhereIntegerInRaw('affiliate_id', $affiliateIdsUser)
                ->orWhereIntegerInRaw("$this->table.id", $coproducerIdsProducts);
        });
    }

    public function scopeAllAffiliatesByUser(Builder $builder, int $userId): Builder
    {
        $user             = User::find($userId);
        $shopId           = $user?->shop()?->id;
        $affiliateIdsUser = $user?->affiliates->pluck('id')->toArray();

        return $builder->where(function ($query) use ($shopId, $affiliateIdsUser) {
            $query->where($this->getTable() . '.' . 'shop_id', $shopId)
                ->whereIntegerInRaw('affiliate_id', $affiliateIdsUser);
        });
    }

    public function scopeIsOrder(Builder $builder): Builder
    {
        return $builder->whereHas('items.product', fn ($query) => $query->where('paymentType', PaymentTypeProductEnum::UNIQUE->name));
    }

    public function scopeIsSubscription(Builder $builder): Builder
    {
        return $builder->whereHas('items.product', fn ($query) => $query->where('paymentType', PaymentTypeProductEnum::RECURRING->name));
    }

    public function scopeSearchPeriod(Builder $query, $period): Builder
    {
        [$start_date, $final_date] = explode(' - ', $period);

        $start_date = Carbon::createFromFormat('d/m/Y', $start_date)->startOfDay();
        $final_date = Carbon::createFromFormat('d/m/Y', $final_date)->endOfDay();

        return $query->whereBetween($this->getTable() . '.created_at', [$start_date, $final_date]);
    }

    public function totalAmountItems(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->loadMissing('items')->items->sum('amount')
        )->shouldCache();
    }

    public function brazilianTotalAmountItems(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->loadMissing('items')->items->sum('amount'), 'BRL', 'pt-br')
        )->shouldCache();
    }

    public function brazilianPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->amount, 'BRL', 'pt-br')
        )->shouldCache();
    }

    public function brazilianFirstPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->first_amount ?? 0, 'BRL', 'pt-br')
        )->shouldCache();
    }

    public function hasAffiliate(): Attribute
    {
        return Attribute::make(
            get: fn () => ! is_null($this->affiliate_id)
        )->shouldCache();
    }

    public function affiliateAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->affiliate and $this->affiliate->type === 'percentage')
                ? ($this->amount / 100) * $this->affiliate->value
                : ($this->affiliate->value ?? 0)
        )->shouldCache();
    }

    public function splitAffiliateAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $amount = 0;

                if ($this->hasAffiliate) {
                    $amount = collect($this->getValueSchemalessAttributes('splitGateway'))
                        ->where('splitTypePerson', 'AFFILIATE')
                        ->sum('valueSplit');

                }

                return $amount;
            }
        )->shouldCache();
    }

    public function brazilianAffiliateAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Number::currency($this->splitAffiliateAmount, 'BRL', 'pt-br');
            }
        )->shouldCache();
    }

    public function coproducerAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return collect($this->getValueSchemalessAttributes('splitGateway'))
                    ->where('splitTypePerson', 'CO_PRODUCER')
                    ->sum('valueSplit');
            }
        );
    }

    public function brazilianShopAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->net_amount, 'BRL', 'pt-br')
        )->shouldCache();
    }

    public function brazilianAmountByTypeUser(User $user, ?Shop $shop)
    {
        $amount = $this->amountByTypeUser($user, $shop);

        return $amount ? Number::currency($amount, 'BRL', 'pt-br') : '-';
    }

    public function amountByTypeUser(User $user, ?Shop $shop)
    {
        if (! $this->isPaid()) {
            return 0;
        }
        $shopUser   = $shop;
        $shopIdUser = $shopUser?->id;

        if ($shopIdUser === $this->shop_id) {
            return $this->invoicingShop;
        }

        $splitGateway = collect($this->getValueSchemalessAttributes('splitGateway'));

        $coProducerIdsUser = $user?->coproducers->pluck('id')->toArray();

        if (in_array($this->coproducer_id, $coProducerIdsUser)
            || $splitGateway->contains(fn ($value) => $value['username'] === $shopUser?->username_banking && $value['splitTypePerson'] === 'CO_PRODUCER')
        ) {
            return $this->coproducerAmount;
        }

        $affiliatesIdUser = $user?->affiliates->pluck('id')->toArray();

        if (in_array($this->affiliate_id, $affiliatesIdUser)
            || $splitGateway->contains(fn ($value) => $value['username'] === $shopUser?->username_banking && $value['splitTypePerson'] === 'AFFILIATE')
        ) {
            return $this->splitAffiliateAmount;
        }

        return 0;
    }

    public function invoicingShop(): Attribute
    {
        return Attribute::make(
            get: function () {
                $amount = $this->net_amount;

                if ($this->hasAffiliate) {
                    $amount -= collect($this->getValueSchemalessAttributes('splitGateway'))
                        ->where('splitTypePerson', 'AFFILIATE')
                        ->sum('valueSplit');
                }

                if ($this->coproducerAmount > 0) {
                    $amount -= $this->coproducerAmount;
                }

                return $amount;
            }
        )->shouldCache();
    }

    public function brazilianInvoicingShop(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Number::currency($this->invoicingShop, 'BRL', 'pt-br');
            }
        )->shouldCache();
    }

    public function orderHash(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        )->shouldCache();
    }

    public function hasCustomThanksPage(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->item->product->parentProduct->thanksPageByPaymentMethod($this->paymentMethod))
        )->shouldCache();
    }

    public function thanksPage(): string
    {
        $paymentMethodOriginal = $this->payment->payment_method;
        $customPage            = $this->item->product->parentProduct->thanksPageByPaymentMethod($paymentMethodOriginal);

        if (! $customPage) {
            return route('checkout.checkout.thanks', ['order_hash' => $this->order_hash]);
        }

        $customPage .= '?payment_code=' . $this->payment->external_content . '&order_id=' . $this->id;

        return $customPage;
    }

    public function paymentExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isPending() && $this->payment_method == PaymentMethodEnum::PIX->name && $this->created_at->addMinutes(15)->isPast()
        )->shouldCache();
    }

    public function scopeIsPaymentExpired(Builder $query): Builder
    {
        return $query->filterByPaymentStatus('pending')
            ->filterByPaymentMethod(PaymentMethodEnum::PIX->name)
            ->where('created_at', '<', now()->subMinutes(15));
    }

    public function belongsToShop(): Attribute
    {
        return Attribute::make(
            get: fn () => auth()->check() && auth()->user()->shop()?->id == $this->shop_id
        )->shouldCache();
    }

    public function belongsToCoProducer(): Attribute
    {
        return Attribute::make(
            get: function () {
                $coproducerUsernameBanking = auth()->user()?->shop()?->username_banking;
                $coProducerIdsUser         = auth()->user()?->coproducers->pluck('id')->toArray();

                return collect($this->getValueSchemalessAttributes('splitGateway'))
                    ->contains(function ($value) use ($coproducerUsernameBanking) {
                        return $value['username'] === $coproducerUsernameBanking && $value['splitTypePerson'] === 'CO_PRODUCER';
                    }) || in_array($this->coproducer_id, $coProducerIdsUser);
            }
        )->shouldCache();
    }

    public function telegramGroupMembers(): HasMany
    {
        return $this->hasMany(TelegramGroupMember::class);
    }

    public function isSubscriptionCharge(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->items()
                ->whereHas(
                    'product',
                    fn($query) =>
                    $query->where('paymentType', PaymentTypeProductEnum::RECURRING->name)
                )
                ->exists()
        );
    }

    public function utmLink(): BelongsTo
    {
        return $this->belongsTo(UtmLink::class);
    }
}
