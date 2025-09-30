<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Casts\Attribute, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class RevisionsProduct extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'offer_id',
        'orderBump_id',
        'user_id',
        'key',
        'old_value',
        'new_value',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'old_value' => 'array',
            'new_value' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'offer_id');
    }

    public function orderBump(): BelongsTo
    {
        return $this->belongsTo(OrderBump::class, 'orderBump_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusTranslated(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'pending'  => __('Pendente'),
                'approved' => __('Aprovada'),
                'rejected' => __('Rejeitada'),
                'reproved' => __('Reprovada'),
                default    => __('-'),
            }
        )->shouldCache();
    }

    public function isPending(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'pending'
        )->shouldCache();
    }

    public function isApproved(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'approved'
        )->shouldCache();
    }
}
