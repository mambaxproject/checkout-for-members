<?php

namespace App\Models;

use App\Casts\CurrencyCast;
use App\Enums\{SituationAffiliateEnum, SituationCoproducerEnum};
use App\Observers\AffiliateObserver;
use App\Traits\{Auditable, HasScopeActive, HasStatusFormatted};
use DateTimeInterface;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

#[ObservedBy(AffiliateObserver::class)]
class Affiliate extends Model
{
    use Auditable;
    use HasScopeActive;
    use HasStatusFormatted;
    use SoftDeletes;

    public $table = 'affiliates';

    public const TYPES = [
        'percentage' => 'Porcentagem',
        'value'      => 'Valor',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'situation'  => SituationAffiliateEnum::class,
        'value'      => CurrencyCast::class,
    ];

    protected $fillable = [
        'code',
        'email',
        'user_id',
        'type',
        'value',
        'description',
        'situation',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where($this->table . '.situation', SituationCoproducerEnum::ACTIVE);
    }

    public function redirectAffiliateExternalSalesLink(): Attribute
    {
        return Attribute::make(
            get: function () {
                $domainVerified = $this->product->domains()?->verified()->latest()->first();

                return $domainVerified
                    ? $domainVerified->url . route('affiliate.redirectExternalSalesLink', ['code' => $this->code], false)
                    : route('affiliate.redirectExternalSalesLink', ['code' => $this->code]);
            }
        )->shouldCache();
    }

    public function formattedValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === 'percentage'
                ? Number::percentage($this->value)
                : Number::currency($this->value, 'BRL', 'pt-BR'),
        )->shouldCache();
    }

    public function situationFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => SituationAffiliateEnum::getDescription($this->situation),
        )->shouldCache();
    }

    public function canCancel(): bool
    {
        return in_array($this->situation, [SituationAffiliateEnum::ACTIVE, SituationAffiliateEnum::PENDING]);
    }

    public function isPending(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation == SituationAffiliateEnum::PENDING,
        )->shouldCache();
    }

    public function isCanceled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation == SituationAffiliateEnum::CANCELED,
        )->shouldCache();
    }

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->situation == SituationAffiliateEnum::ACTIVE,
        )->shouldCache();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

}
