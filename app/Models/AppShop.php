<?php

namespace App\Models;

use App\Traits\{Auditable, HasProduct, HasScopeActive, HasStatusFormatted};
use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use Illuminate\Database\Eloquent\{Builder, Model};

class AppShop extends Model
{
    use Auditable, HasProduct, HasScopeActive, HasStatusFormatted;

    protected $table = 'app_shop';

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'shop_id',
        'app_id',
        'data',
        'status',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeHasApp(Builder $query, string $slug): Builder
    {
        return $query->whereHas('app', fn ($query) => $query->where('slug', $slug));
    }

    public function scopeHasProductById(Builder $query, int $productId): Builder
    {
        return $query->whereHas('products', fn ($query) => $query->where('product_id', $productId));
    }

}
