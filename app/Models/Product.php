<?php

namespace App\Models;

use App\Enums\{CyclePaymentProductEnum, PaymentTypeProductEnum, SituationProductEnum};
use App\Observers\ProductObserver;
use App\Traits\{Auditable, HasSchemalessAttributes, HasScopeActive, HasStatusFormatted};
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne, MorphToMany};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Support\Number;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

#[ObservedBy(ProductObserver::class)]
class Product extends Model implements HasMedia
{
    use Auditable;
    use HasFactory;
    use HasSchemalessAttributes;
    use HasScopeActive;
    use HasStatusFormatted;
    use InteractsWithMedia;
    use SoftDeletes;

    public $table = 'products';

    public static array $searchable = [
        'name',
    ];

    protected $casts = [
        'end_at'     => 'datetime',
        'start_at'   => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'code',
        'type_id',
        'shop_id',
        'parent_id',
        'description',
        'category_id',
        'checkout_id',
        'price',
        'infos',
        'client_product_uuid',
        'end_at',
        'start_at',
        'shop_id',
        'situation',
        'status',
        'guarantee',
        'paymentType',
        'cyclePayment',
        'priceFirstPayment',
        'renewsRecurringPayment',
        'numberPaymentsRecurringPayment',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'item_orders', 'product_id', 'order_id');
    }

    public function affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class, 'product_id');
    }

    public function couponsDiscount(): MorphToMany
    {
        return $this->morphedByMany(CouponDiscount::class, 'productable');
    }

    public function pixels(): MorphToMany
    {
        return $this->morphedByMany(Pixel::class, 'productable');
    }

    public function orderBumps(): MorphToMany
    {
        return $this->morphedByMany(OrderBump::class, 'productable');
    }

    public function upSells(): MorphToMany
    {
        return $this->morphedByMany(UpSell::class, 'productable');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id')
            ->whereNotNull('parent_id');
    }

    public function offersPaymentUnique(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id')
            ->whereColumn('products.parent_id', '!=', 'products.id')
            ->where('paymentType', PaymentTypeProductEnum::UNIQUE->name);
    }

    public function offersPaymentRecurring(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id')
            ->whereColumn('products.parent_id', '!=', 'products.id')
            ->where('paymentType', PaymentTypeProductEnum::RECURRING->name);
    }

    public function activeOffers(string $paymentType): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id')
            ->whereNotNull('parent_id')
            ->where('paymentType', $paymentType);
    }

    public function parentProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'parent_id')->withTrashed();
    }

    public function domains(): MorphToMany
    {
        return $this->morphedByMany(Domain::class, 'productable');
    }

    public function domainVerified(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('parentProduct.domains');

                return $this->parentProduct->domains()->verified()->latest()->first();
            }
        )->shouldCache();
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }

    public function checkout(): HasOne
    {
        return $this->hasOne(Checkout::class, 'id', 'checkout_id')
            ->where('default', true)
            ->withDefault([
                'settings' => [],
            ]);
    }

    public function webhooks(): MorphToMany
    {
        return $this->morphedByMany(Webhook::class, 'productable');
    }

    public function coproducers(): MorphToMany
    {
        return $this->morphedByMany(Coproducer::class, 'productable');
    }

    public function abandonedCarts(): HasMany
    {
        return $this->hasMany(AbandonedCart::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(RevisionsProduct::class);
    }

    public function brazilianPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::currency($this->price, 'BRL', 'pt-br')
        )->shouldCache();
    }

    public function brazilianPriceFirstPayment(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->priceFirstPayment > 0
                ? Number::currency($this->priceFirstPayment, 'BRL', 'pt-br')
                : '-'
        )->shouldCache();
    }

    public function hasPaymentMethod(string $paymentMethod): bool
    {
        return in_array($paymentMethod, $this->getValueSchemalessAttributes('paymentMethods') ?? []);
    }

    public function hasCustomThanksPage(): bool
    {
        return $this->getValueSchemalessAttributes('linkThanksForOrderInCREDIT_CARD')
            || $this->getValueSchemalessAttributes('linkThanksForOrderInBILLET')
            || $this->getValueSchemalessAttributes('linkThanksForOrderInPIX');
    }

    public function thanksPageByPaymentMethod($payment_method): ?string
    {
        return $this->getValueSchemalessAttributes('linkThanksForOrderIn' . $payment_method);
    }

    public function scopeIsProduct(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeIsOffer(Builder $builder): Builder
    {
        return $builder->whereNotNull('parent_id');
    }

    public function scopeIsPublished(Builder $builder): Builder
    {
        return $builder->where('situation', SituationProductEnum::PUBLISHED->name);
    }

    public function scopeShowProductInMarketplace(Builder $builder): Builder
    {
        return $builder->where('attributes->affiliate->enabled', '1')
            ->where('attributes->affiliate->showProductInMarketplace', '1');
    }

    public function scopeRangePrice(Builder $builder, $min, $max): Builder
    {
        return $builder->whereHas('offers', function ($query) use ($min, $max) {
            $query->whereBetween('price', [$min, $max]);
        });
    }

    public function scopeIsPaymentUnique(Builder $builder): Builder
    {
        return $builder->where('paymentType', PaymentTypeProductEnum::UNIQUE->name);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 50, 50);
        $this->addMediaConversion('preview')->fit(Fit::Crop, 120, 120);
        $this->addMediaConversion('webp')->format('webp');
    }

    public function price(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(',', '.', str_replace('.', '', $value))
        );
    }

    public function priceFirstPayment(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(',', '.', str_replace('.', '', $value))
        );
    }

    public function cyclePaymentTranslated(): Attribute
    {
        return Attribute::make(
            get: fn () => CyclePaymentProductEnum::getFromName($this->cyclePayment)
        )->shouldCache();
    }

    public function paymentTypeTranslated(): Attribute
    {
        return Attribute::make(
            get: fn () => PaymentTypeProductEnum::getFromName($this->paymentType)
        )->shouldCache();
    }

    public function numberPaymentsRecurringPaymentFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->renewsRecurringPayment == 'FIXED_QTY_CHARGES' && ! empty($this->numberPaymentsRecurringPayment)
                ? $this->numberPaymentsRecurringPayment
                : 'Até o cliente cancelar'
        )->shouldCache();
    }

    public function featuredImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('featuredImage') ?: 'https://placehold.co/600x400?text=Sem imagem'
        )->shouldCache();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->domainVerified
                    ? $this->domainVerified->url . route('checkout.checkout.product', ['product' => $this->code], false)
                    : route('checkout.checkout.product', ['product' => $this->code]);
            }
        )->shouldCache();
    }

    public function linkJoinAffiliate(): Attribute
    {
        return Attribute::make(
            get: fn () => route('affiliate.join', ['product' => $this->code])
        )->shouldCache();
    }

    public function situationTranslated(): Attribute
    {
        return Attribute::make(
            get: fn () => SituationProductEnum::getDescription($this->situation)
        )->shouldCache();
    }

    public function hasFirstPayment(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->priceFirstPayment)
        )->shouldCache();
    }

    public function isRecurring(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->paymentType == PaymentTypeProductEnum::RECURRING->name
        )->shouldCache();
    }

    public function nextCharge(): Carbon
    {
        return match ($this->cyclePayment) {
            CyclePaymentProductEnum::FORTNIGHTLY->name => Carbon::now()->addDays(15),
            CyclePaymentProductEnum::MONTHLY->name     => Carbon::now()->addMonth(),
            CyclePaymentProductEnum::QUARTERLY->name   => Carbon::now()->addQuarter(),
            CyclePaymentProductEnum::SEMI_ANNUAL->name => Carbon::now()->addMonths(6),
        };
    }

    public function isDisable(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation === SituationProductEnum::DISABLE->name,
        )->shouldCache();
    }

    public function isDraft(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation === SituationProductEnum::DRAFT->name,
        )->shouldCache();
    }

    public function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation === SituationProductEnum::PUBLISHED->name,
        )->shouldCache();
    }

    public function isReproved(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation === SituationProductEnum::REPROVED->name,
        )->shouldCache();
    }

    public function isInAnalysis(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation === SituationProductEnum::IN_ANALYSIS->name,
        )->shouldCache();
    }

    public function isOffer(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->parent_id),
        )->shouldCache();
    }

    public function canSubmitForApproval(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->situation === SituationProductEnum::DRAFT->name
                    && (! empty($this->name) && ! empty($this->category_id) && ! empty($this->description) && $this->getMedia('featuredImage')->isNotEmpty())
                    && $this->offers()->exists()
                    && $this->getValueSchemalessAttributes('paymentMethods') ?? false
                    && $this->getValueSchemalessAttributes('externalSalesLink')
                    && $this->getValueSchemalessAttributes('emailSupport')
                    && $this->getValueSchemalessAttributes('nameShop');
            }
        )->shouldCache();
    }

    public function canSubmitForNewApproval(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ($this->situation === SituationProductEnum::REPROVED->name || $this->isDisable)
                    && (! empty($this->name) && ! empty($this->category_id) && ! empty($this->description) && $this->getMedia('featuredImage')->isNotEmpty())
                    && $this->offers()->exists()
                    && $this->getValueSchemalessAttributes('paymentMethods')
                    && $this->getValueSchemalessAttributes('externalSalesLink')
                    && $this->getValueSchemalessAttributes('emailSupport')
                    && $this->getValueSchemalessAttributes('nameShop');
            }
        )->shouldCache();
    }

    public function affiliateExternalSalesLink(string $affiliateCode): ?string
    {
        $link = $this->getValueSchemalessAttributes('externalSalesLink');

        if (empty($link)) {
            return null;
        }

        $urlParts = explode('#', $link);
        $baseUrl  = $urlParts[0];
        $fragment = isset($urlParts[1]) ? '#' . $urlParts[1] : '';

        $separator = str_contains($baseUrl, '?') ? '&' : '?';

        return $baseUrl . $separator . 'suit_afflt=' . $affiliateCode . $fragment;
    }

    public function maxInstallments(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getValueSchemalessAttributes('maxInstallments') ?? 12
        )->shouldCache();
    }

    public function minPriceOffers(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->offers->min('price') ?? 0
        )->shouldCache();
    }

    public function maxPriceOffers(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->offers->max('price') ?? 0
        )->shouldCache();
    }

    public function valueAffiliateEarning(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getValueSchemalessAttributes('affiliate.defaultTypeValue') === 'VALUE'
                    ? Number::currency((float) str_replace(',', '.', $this->getValueSchemalessAttributes('affiliate.defaultValue')), 'BRL', 'pt-br')
                    : Number::currency(
                        (float) (($this->maxPriceOffers * ($this->getValueSchemalessAttributes('affiliate.defaultValue') ?? 0)) / 100),
                        'BRL',
                        'pt-br'
                    );
            }
        )->shouldCache();
    }

    public function rejectReasonTranslated(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->isReproved) {
                    $rejectReasonProduct = $this->getValueSchemalessAttributes('rejectReasons');
                    $rejectReasons       = config('products.rejectReasons');
                    $reasonFiltered      = array_filter($rejectReasons, fn ($reason) => $reason['value'] === $rejectReasonProduct);
                    $reasonFiltered      = current($reasonFiltered)['name'];

                    return $reasonFiltered;
                }

                return '';
            }
        )->shouldCache();
    }

    public function unvailableForSales(): Attribute
    {
        return Attribute::make(
            get: fn () => ! $this?->parentProduct->isActive || ! $this?->parentProduct->isPublished || ! $this->isPublished
        )->shouldCache();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    public function isTypeDefault(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type_id == 1
        )->shouldCache();
    }

    public function isTypeSuitMembers(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type_id == 2
        )->shouldCache();
    }

    public function telegramGroup(): HasOne
    {
        return $this->hasOne(TelegramGroup::class);
    }

    public function utmLinks(): HasMany
    {
        return $this->hasMany(UtmLink::class, 'product_id');
    }
}
