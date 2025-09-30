<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasScopeActive;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasScopeActive;

    public $table = 'cities';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public const STATUS_RADIO = [
        'active'   => 'Ativo',
        'inactive' => 'Inativo',
    ];

    protected $fillable = [
        'name',
        'state_id',
        'status',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

}
