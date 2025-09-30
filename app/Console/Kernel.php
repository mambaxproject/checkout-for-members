<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $ordersWithPaymentExpired = app(\App\Services\OrderService::class)->getAllOrdersWithPaymentExpired();
            $ordersWithPaymentExpired->each(function ($order) {
                Mail::to($order->user->email)
                    ->send(new \App\Mail\Orders\Customer\OrderFailed($order));

                $order->attributes->set('notifiedOrderFailed', true);
                $order->saveQuietly();
            });
        })->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
