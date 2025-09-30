<?php

namespace App\Listeners;

use App\Events\OrderApproved;
use App\Mail\Telegram\TelegramInviteLink;
use App\Services\Notification\Telegram\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\{Log, Mail};

class CreateTelegramInviteLink implements ShouldQueue
{
    public function handle(OrderApproved $event): void
    {
        $generatedLink = false;

        foreach ($event->order->items as $item) {
            $telegramGroup = $item->product
                ?->parentProduct
                ?->telegramGroup;

            try {

                if ($telegramGroup) {
                    $response = (new TelegramService)->createChatInviteLink($telegramGroup, $event->order->id);

                    if ($response->successful()) {
                        $invite_link = $response->json()['result']['invite_link'];

                        $telegramGroup->members()->create([
                            'order_id'    => $event->order->id,
                            'invite_link' => $invite_link,
                        ]);

                        $generatedLink = true;

                    } else {
                        throw new \Exception($response->body());
                    }
                }

            } catch (\Exception $exception) {
                Log::error('CreateTelegramInviteLink: Failed to create Telegram invite link', [
                    'order_id'       => $event->order->id,
                    'telegram group' => $telegramGroup->toArray(),
                    'response'       => $response->body() ?? '',
                    'exception'      => $exception->getMessage(),
                ]);
            }
        }

        if ($generatedLink) {
            Mail::to($event->order->user->email)
                ->send(new TelegramInviteLink($event->order));
        }
    }
}
