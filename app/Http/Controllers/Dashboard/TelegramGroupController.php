<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreTelegramGroupRequest, UpdateTelegramGroupRequest};
use App\Models\{OrderPayment, TelegramGroup};
use App\Services\Notification\Telegram\TelegramService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Str;

class TelegramGroupController extends Controller
{
    public function index(): View
    {
        $shop     = user()->shop();
        $groups   = $shop->telegramGroups()->with('product')->get();
        $products = $shop->products()
            ->whereNull('parent_id')
            ->get();

        return view('dashboard.telegram-groups.index', compact('groups', 'products'));
    }

    public function store(StoreTelegramGroupRequest $request): RedirectResponse
    {
        user()->shop()->telegramGroups()->create($request->validated());

        return back()->with('message', 'Grupo criado com sucesso!');
    }

    public function show(TelegramGroup $telegram): View
    {
        $members = $telegram->members;

        $members->load([
            'order.user',
            'order.payments',
        ]);

        return view('dashboard.telegram-groups.show', compact('members', 'telegram'));
    }

    public function update(UpdateTelegramGroupRequest $request, TelegramGroup $telegram): RedirectResponse
    {
        $telegram->update($request->validated());

        return back()->with('message', 'Grupo atualizado com sucesso!');
    }

    public function destroy(TelegramGroup $telegram): RedirectResponse
    {
        if ($telegram->isActive) {
            (new TelegramService)->removeBotFromGroup($telegram);
        }

        $telegram->delete();

        return back()->with('message', 'Grupo deletado com sucesso!');
    }

    public function getInviteLink(OrderPayment $payment): JsonResponse
    {
        return response()->json($payment->order->telegramGroupMembers->first());
    }

    public function isGroupActive(TelegramGroup $telegram): JsonResponse
    {
        return response()->json([
            'is_active' => $telegram->isActive,
        ]);
    }

    public function webhook(Request $request): JsonResponse
    {
        if ($request->has('message.entities') && $request->get('message')['entities'][0]['type'] == 'mention') {
            $text        = $request->get('message')['text'];
            $botUsername = config('services.telegram.bot_username');

            if (Str::contains($text, $botUsername)) {
                $code = Str::replace(' ', '', Str::replaceFirst($botUsername, '', $text));

                $group = TelegramGroup::where('code', $code)->firstOrFail();

                $group->update([
                    'status'  => StatusEnum::ACTIVE->name,
                    'chat_id' => $request->get('message')['chat']['id'],
                ]);

                (new TelegramService)->sendMessage($group, 'Grupo ativado com sucesso!');

                return response()->json(['message' => 'activate group successfully']);
            }
        }

        if ($request->has('chat_join_request')) {
            $chat_join_request = $request->get('chat_join_request');
            $invite_link       = $chat_join_request['invite_link']['invite_link'];
            $telegram_user_id  = $chat_join_request['user_chat_id'];
            $group             = TelegramGroup::where('chat_id', $chat_join_request['chat']['id'])->firstOrFail();
            $member            = $group->members()->where('invite_link', $invite_link)->firstOrFail();

            if ($member) {
                (new TelegramService)->approveChatJoinRequest($group, $telegram_user_id, $invite_link);

                $member->update([
                    'status'            => StatusEnum::ACTIVE->name,
                    'telegram_user_id'  => $telegram_user_id,
                    'telegram_username' => $chat_join_request['from']['first_name'],
                ]);
            }

            return response()->json(['message' => 'member approved successfully']);
        }

        return response()->json(['message' => 'successfully']);
    }
}
