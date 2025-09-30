<?php

namespace App\Models;

use App\Traits\{Auditable, HasScopeActive, HasStatusFormatted};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class App extends Model
{
    use Auditable, HasScopeActive, HasStatusFormatted;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon_url',
        'status',
    ];

    protected $casts = [
        'dataShopUser' => 'array',
    ];

    public function appShop(): HasOne
    {
        return $this->hasOne(AppShop::class, 'app_id');
    }
}
