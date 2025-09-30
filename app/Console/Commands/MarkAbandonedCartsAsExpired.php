<?php

namespace App\Console\Commands;

use App\Models\AbandonedCart;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbandonedCartsAsExpired extends Command
{
    protected $signature = 'app:mark-abandoned-carts-as-expired';
    protected $description = 'Sets the abandoned cart status to expired when the purchase is not completed.';

    public function handle()
    {
        AbandonedCart::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->update(
                [
                    'status' => 'expired'
                ]
            );
    }
}
