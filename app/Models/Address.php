<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{

    use Auditable;
    use SoftDeletes;

    public $table = 'addresses';

    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'name',
        'zipcode',
        'street_address',
        'neighborhood',
        'number',
        'complement',
        'city',
        'state',
        'position',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getCoordinatesAttribute(): array
    {
        list($longitude, $latitude) = $this->position ? explode(',', $this->position) : [0, 0];

        return [
            'longitude' => $longitude,
            'latitude'  => $latitude,
        ];
    }

    public function getIframeMapAttribute(): string
    {
        return '<iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=' . $this->coordinates['longitude'] . '%2C' . $this->coordinates['latitude'] . '%2C' . $this->coordinates['longitude'] . '%2C' . $this->coordinates['latitude'] . '&amp;layer=mapnik&amp;marker=' . $this->coordinates['latitude'] . '%2C' . $this->coordinates['longitude'] . '"></iframe>';
    }

}
