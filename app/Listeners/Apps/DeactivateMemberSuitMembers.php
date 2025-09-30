<?php

namespace App\Listeners\Apps;

use App\Events\OrderCanceled;
use App\Events\OrderFailed;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Product;
use App\Services\Members\SuitMembersApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeactivateMemberSuitMembers implements ShouldQueue
{
    public function handle(OrderFailed|OrderCanceled $event): void
    {
        foreach ($event->order->items as $item) {
            try {
                $this->stepsSendSuitMembers($item,);
            } catch (\Throwable $th) {
                Log::channel('members')->error(
                    'Erro ao enviar para api de membros para inativar usúario.',
                    [
                        'error' => $th->getMessage(),
                        'function' => 'DeactivateMemberSuitMembers.handle',
                        'trace' => $th->getTraceAsString()
                    ]
                );
                throw $th;
            }
        }
    }

    private function stepsSendSuitMembers(ItemOrder $itemOrder): void
    {
        $product = $itemOrder->product;
        $order = $itemOrder->order;

        if (!$product->isTypeSuitMembers) {
            return;
        };

        $uuid = $product->parentProduct->client_product_uuid;

        $route = 'courses/' . $uuid . '/member/peding';
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $body = ['email' => $order->user->email];
        retry(3, function () use ($suitMembersApiService, $route, $body) {
            return $suitMembersApiService->put($route, $body);
        }, 1000);
    }
}
