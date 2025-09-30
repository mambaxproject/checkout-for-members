<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    public $table = 'product_types';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'label'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'type_id');
    }
}
