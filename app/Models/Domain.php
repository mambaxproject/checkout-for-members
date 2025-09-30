<?php

namespace App\Models;

use App\Traits\{Auditable, HasSchemalessAttributes};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Support\Carbon;

class Domain extends Model
{
    use Auditable, HasSchemalessAttributes, SoftDeletes;

    protected $fillable = [
        'domain',
        'dns',
        'verified',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'dns'         => 'array',
            'verified_at' => 'datetime',
        ];
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn () => 'https://' . $this->dns['url'],
        )->shouldCache();
    }

    public function verifiedFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $verified_at = Carbon::parse($this->attributes['verified_at']);
                $diffInHours = $verified_at->diffInHours(now());

                return $this->attributes['verified'] && $diffInHours >= 1
                    ? 'verificado'
                    : 'não verificado';
            },
        )->shouldCache();
    }

    public function verifiedClassCss(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->verifiedFormatted === 'verificado'
                    ? 'text-primary'
                    : 'text-gray-500';
            },
        )->shouldCache();
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('verified', true);
    }

}
