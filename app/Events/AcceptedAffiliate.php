<?php

namespace App\Events;

use App\Models\Affiliate;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptedAffiliate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Affiliate $affiliate)
    {}
}
