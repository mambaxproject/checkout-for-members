<?php

namespace App\Events;

use App\Models\Coproducer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptedCoproducer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Coproducer $coproducer)
    {}
}
