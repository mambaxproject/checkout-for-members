<?php

namespace App\Models;

use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UtmLink extends Model implements Viewable
{
    use InteractsWithViews;

    protected $fillable = [
        'product_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                $params = [
                    'utm_source' => $this->utm_source,
                    'utm_medium' => $this->utm_medium,
                ];

                if ($this->utm_campaign) {
                    $params['utm_campaign'] = $this->utm_campaign;
                }

                if ($this->utm_content) {
                    $params['utm_content'] = $this->utm_content;
                }

                if ($this->utm_term) {
                    $params['utm_term'] = $this->utm_term;
                }

                return $this->product->url . '?' . http_build_query(array_filter($params));
            }
        )->shouldCache();
    }
}
