<?php

namespace App\Models;

use App\Traits\HasScopeActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PixelService extends Model
{
    use SoftDeletes;
    use HasScopeActive;

    protected $fillable = [
        'name',
        'status',
        'image_url',
    ];

}
