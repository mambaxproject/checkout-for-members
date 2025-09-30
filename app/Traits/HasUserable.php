<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasUserable
{

    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'userable');
    }

}