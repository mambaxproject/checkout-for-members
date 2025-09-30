<?php

namespace App\Services\Notification\Telegram;

use App\Models\TelegramGroup;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    private string $baseURL;

    public function __construct()
    {
        $this->baseURL = config('services.telegram.base_url') . config('services.telegram.token');
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (TelegramGroup::where('code', $code)->exists());

        return $code;
    }

    public function createChatInviteLink(TelegramGroup $group, $orderId): Response
    {
        $response = Http::retry(3, 1000)
            ->post("$this->baseURL/createChatInviteLink", [
                'chat_id'              => $group->chat_id,
                'name'                 => $orderId,
                'creates_join_request' => true,
            ]);

        return $response;
    }

    public function approveChatJoinRequest(TelegramGroup $group, $userId, $inviteLink): Response
    {
        $response = Http::retry(3, 1000)
            ->post("$this->baseURL/approveChatJoinRequest", [
                'chat_id' => $group->chat_id,
                'user_id' => $userId,
            ]);

        if ($response->successful()) {
            $this->revokeChatInviteLink($group, $inviteLink);
        }

        return $response;
    }

    private function revokeChatInviteLink(TelegramGroup $group, string $inviteLink): Response
    {
        $response = Http::retry(3, 1000)
            ->post("$this->baseURL/revokeChatInviteLink", [
                'chat_id'     => $group->chat_id,
                'invite_link' => $inviteLink,
            ]);

        return $response;
    }

    public function removeBotFromGroup(TelegramGroup $group): Response
    {
        $response = Http::retry(3, 1000)
            ->post("$this->baseURL/leaveChat", [
                'chat_id' => $group->chat_id,
            ]);

        return $response;
    }

    public function sendMessage(TelegramGroup $group, string $message): Response
    {
        $response = Http::retry(3, 1000)
            ->post("$this->baseURL/sendMessage", [
                'chat_id' => $group->chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

        return $response;
    }
}
