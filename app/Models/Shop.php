<?php

namespace App\Models;

use App\Traits\{Auditable, HasSchemalessAttributes, HasStatusFormatted, HasUserable};
use DateTimeInterface;
use Illuminate\Database\Eloquent\{Casts\Attribute, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasManyThrough};
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Shop extends Model implements HasMedia
{
    use Auditable;
    use HasSchemalessAttributes;
    use HasStatusFormatted;
    use HasUserable;
    use InteractsWithMedia;
    use SoftDeletes;

    public $table = 'shops';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'owner_id',
        'name',
        'username_banking',
        'client_id_banking',
        'client_secret_banking',
        'client_secret_members',
        'link',
        'description',
        'status',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function apps(): HasMany
    {
        return $this->hasMany(AppShop::class);
    }

    public function webHooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }

    public function abandonedCarts(): HasManyThrough
    {
        return $this->hasManyThrough(
            AbandonedCart::class,
            Product::class,
            'shop_id',
            'product_id',
            'id',
            'id'
        );
    }

    public function logRequests(): HasMany
    {
        return $this->hasMany(ShopRequestLog::class);
    }

    public function affiliates(): HasManyThrough
    {
        return $this->hasManyThrough(
            Affiliate::class,
            Product::class,
            'shop_id',
            'product_id',
            'id',
            'id'
        );
    }

    public function telegramGroups(): HasMany
    {
        return $this->hasMany(TelegramGroup::class);
    }

    public function coProducers()
    {
        return Coproducer::whereRelation(
            'products',
            'shop_id',
            $this->id
        );
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function hasCreditCardPaymentEnabled(): Attribute
    {
        return Attribute::make(
            get: fn () => boolval($this->getValueSchemalessAttributes('allowCreditCard')),
        )->shouldCache();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 50, 50);
        $this->addMediaConversion('preview')->fit(Fit::Crop, 120, 120);
        $this->addMediaConversion('webp')->format('webp');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function crmRules(): HasMany
    {
        return $this->hasMany(CRMRule::class);
    }

    public function isCRMActive(): Attribute
    {
        return Attribute::make(
            get: fn () => boolval($this->getValueSchemalessAttributes('suitpay_crm')),
        )->shouldCache();
    }

    public function utmLinks(): HasManyThrough
    {
        return $this->hasManyThrough(
            UtmLink::class,
            Product::class,
            'shop_id',
            'product_id',
            'id',
            'id'
        );
    }
}
