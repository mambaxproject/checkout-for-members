<?php

namespace App\Models;

use App\Observers\CheckoutObserver;
use App\Traits\Auditable;
use App\Traits\HasScopeActive;
use App\Traits\HasStatusFormatted;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy(CheckoutObserver::class)]
class Checkout extends Model implements HasMedia
{
    use SoftDeletes;
    use Auditable;
    use HasScopeActive;
    use HasStatusFormatted;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'shop_id',
        'product_id',
        'default',
        'settings',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'default'  => 'boolean',
            'settings' => 'array',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function defaultFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->default ? 'Sim' : 'Não',
        )->shouldCache();
    }

    public function verticalBanner(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFirstMedia('verticalBanner'),
        )->shouldCache();
    }

    public function horizontalBanner(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFirstMedia('horizontalBanner'),
        )->shouldCache();
    }
}
