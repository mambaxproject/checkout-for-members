<?php

namespace App\Models;

use App\Enums\SituationCoproducerEnum;
use App\Traits\{Auditable, HasProduct, HasSchemalessAttributes};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coproducer extends Model
{
    protected $table = 'coproducers';

    use Auditable;
    use HasProduct;
    use HasSchemalessAttributes;
    use SoftDeletes;

    protected $casts = [
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'deleted_at'     => 'datetime',
        'valid_until_at' => 'datetime',
        'situation'      => SituationCoproducerEnum::class,
    ];

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'allow_affiliate_sales',
        'allow_producer_sales',
        'percentage_commission',
        'valid_until_at',
        'duration',
        'situation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): Attribute
    {
        return Attribute::make(
            get: fn () => $this?->products()?->withTrashed()->first(),
        )->shouldCache();
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where($this->table . '.situation', SituationCoproducerEnum::ACTIVE);
    }

    public function scopeValidPeriod(Builder $builder): Builder
    {
        return $builder->where(function ($query) {
            $query->whereNowOrFuture('valid_until_at')
                ->orWhereNull('valid_until_at');
        });
    }

    public function situationFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => SituationCoproducerEnum::getDescription($this->situation),
        )->shouldCache();
    }

    public function canCancel(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->situation, [SituationCoproducerEnum::ACTIVE, SituationCoproducerEnum::PENDING]),
        )->shouldCache();
    }

    public function isPending(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation == SituationCoproducerEnum::PENDING,
        )->shouldCache();
    }

    public function isCanceled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation == SituationCoproducerEnum::CANCELED,
        )->shouldCache();
    }

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation == SituationCoproducerEnum::ACTIVE,
        )->shouldCache();
    }

}
