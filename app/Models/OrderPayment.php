<?php

namespace App\Models;

use App\Enums\PaymentMethodEnum;
use App\Traits\{Auditable, HasSchemalessAttributes};
use DateTimeInterface;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    use Auditable, HasSchemalessAttributes, SoftDeletes;

    public $table = 'order_payments';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'paid_at'    => 'datetime',
        'due_date'   => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'payment_status',
        'payment_gateway_response',
        'external_identification',
        'external_url',
        'external_content',
        'installments',
        'installment_amount',
        'paid_at',
        'due_date',
        'recurrency_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function recurrencyId(): Attribute
    {
        return Attribute::make(
            get: fn () => json_decode($this->payment_gateway_response, true)['recurrencyId'] ?? null,
        )->shouldCache();
    }

    public function reasonRefused(): Attribute
    {
        return Attribute::make(
            get: function () {
                preg_match('/^(\{.*?\})/', $this->payment_gateway_response, $matches);
                $json     = $matches[1];
                $response = json_decode($json, true);

                return $response['acquirerMessage'] ?? null;
            },
        )->shouldCache();
    }

    public function scopeByRecurrencyId($query, string $recurrencyId): void
    {
        $query->where('recurrency_id', $recurrencyId);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function isFailed(): bool
    {
        return in_array(strtolower($this->payment_status), array_map('strtolower', Order::$statusForFailed));
    }

    public function isCanceled(): bool
    {
        return in_array(strtolower($this->payment_status), array_map('strtolower', Order::$statusForCanceled));
    }

    public function isPending(): bool
    {
        return in_array(strtolower($this->payment_status), array_map('strtolower', Order::$statusForPending));
    }

    public function isPaid(): bool
    {
        return in_array(strtolower($this->payment_status), array_map('strtolower', Order::$statusForPaid));
    }

    public function isPix(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->payment_method == PaymentMethodEnum::PIX->name
        )->shouldCache();
    }

    public function isCreditCard(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->payment_method == PaymentMethodEnum::CREDIT_CARD->name
        )->shouldCache();
    }

    public function isBillet(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->payment_method == PaymentMethodEnum::BILLET->name
        )->shouldCache();
    }
}
