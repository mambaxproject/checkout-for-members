<?php

namespace App\Actions;

use App\Models\Order;
use App\Services\MemberKit\Endpoints\User;

class SendDataToMemberKit
{
    public function __construct(
        public Order $order
    ) {}

    public function handle(): array
    {
        $this->order->load('shop', 'user');

        $appMemberKit = $this->order->shop
            ->apps()
            ->active()
            ->hasApp('member-kit')
            ->first();

        if (! $appMemberKit) {
            return ['message' => 'App Member Kit não configurado.'];
        }

        $classroomIds = is_string($appMemberKit->data['class_id'] ?? null)
            ? explode(',', $appMemberKit->data['class_id'])
            : ($appMemberKit->data['class_id'] ?? []);

        $dataMemberKit = [
            'full_name'     => $this->order->user->name,
            'email'         => $this->order->user->email,
            'status'        => 'active',
            'classroom_ids' => $classroomIds,
        ];

        $userMemberKit = (new User)->newUser($appMemberKit->data['secret_key'], $dataMemberKit);

        return [
            'url'      => config('services.memberKit.base_url') . '/users',
            'data'     => $dataMemberKit,
            'response' => $userMemberKit,
        ];
    }

}
