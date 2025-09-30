<?php

namespace App\Models;

use App\Enums\{SituationTelegramGroupEnum};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class TelegramGroup extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => SituationTelegramGroupEnum::class,
    ];

    protected $fillable = [
        'name',
        'product_id',
        'code',
        'chat_id',
        'shop_id',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(TelegramGroupMember::class, 'telegram_group_id', 'id')->orderBy('id', 'asc');
    }

    public function isActive(): Attribute
    {
        return Attribute::make(function () {
            get: return $this->status === SituationTelegramGroupEnum::ACTIVE;
        })->shouldCache();
    }

    public function situationFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => SituationTelegramGroupEnum::getDescription($this->status),
        )->shouldCache();
    }
}
