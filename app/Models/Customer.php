<?php

namespace App\Models;

use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    use HasAddress;

    protected $table = 'customers';

    protected $hidden = [
        'document_number'
    ];

    protected $casts = [
        'birthday'              => 'date',
        'email_verified_at'     => 'datetime',
        'verified_at'           => 'datetime',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'datetime',
    ];

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone_number',
        'document_number',
        'birthday',
        'email_verified_at',
        'verified',
        'verified_at'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
