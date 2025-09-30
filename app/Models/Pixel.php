<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasScopeActive;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pixel extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasScopeActive;
    use HasSchemalessAttributes;

    protected $table = 'pixels';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'pixel_service_id',
        'pixel_id',
        'mark_billet',
        'mark_pix',
        'status',
        'user_id'
    ];

    public function pixelService(): BelongsTo
    {
        return $this->belongsTo(PixelService::class);
    }

    public function markBilletText(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->mark_billet ? 'Sim' : 'Não',
        )->shouldCache();
    }

    public function markPixText(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->mark_pix ? 'Sim' : 'Não',
        )->shouldCache();
    }

}
