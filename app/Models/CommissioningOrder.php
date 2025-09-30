<?php

namespace App\Models;

use App\Traits\{Auditable, HasSchemalessAttributes};
use Illuminate\Database\Eloquent\{Casts\Attribute, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

class CommissioningOrder extends Model
{
    use Auditable;
    use HasSchemalessAttributes;
    use SoftDeletes;

    protected $table = 'commissioning_orders';

    protected $fillable = [
        'commissioned_id',
        'type',
        'order_id',
        'value',
        'type_commission',
        'value_commission',
    ];

    public function commissioned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commissioned_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function typeTranslated(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === 'AFFILIATE' ? 'Afiliado' : 'Co-produtor'
        )->shouldCache();
    }

    public function valueCommissionFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->type_commission) {
                    'percentage' => Number::percentage($this->value_commission),
                    'value'      => Number::currency($this->value_commission, 'BRL', 'pt-BR'),
                    default      => '-',
                };
            }
        )->shouldCache();
    }
}
