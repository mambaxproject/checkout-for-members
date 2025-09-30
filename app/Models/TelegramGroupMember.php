<?php

namespace App\Models;

use App\Enums\SituationTelegramGroupMemberEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramGroupMember extends Model
{
    use HasFactory;

    protected $table = 'telegram_group_members';

    protected $casts = [
        'status' => SituationTelegramGroupMemberEnum::class,
    ];

    protected $fillable = [
        'telegram_group_id',
        'order_id',
        'invite_link',
        'status',
        'telegram_user_id',
        'telegram_username',
    ];

    public function telegramGroup(): BelongsTo
    {
        return $this->belongsTo(TelegramGroup::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function situationFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => SituationTelegramGroupMemberEnum::getDescription($this->status),
        )->shouldCache();
    }
}
