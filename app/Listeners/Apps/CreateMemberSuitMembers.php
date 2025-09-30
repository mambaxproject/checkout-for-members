<?php

namespace App\Listeners\Apps;

use App\Events\OrderApproved;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Product;
use App\Services\Members\SuitMembersApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMemberSuitMembers implements ShouldQueue
{
    public function handle(OrderApproved $event): void
    {
        foreach ($event->order->items as $item) {
            try {
                $this->stepsSendSuitMembers($item);
            } catch (\Throwable $th) {
                Log::channel('members')->error(
                    'Erro ao enviar para api de membros.',
                    [
                        'error' => $th->getMessage(),
                        'function' => 'CreateMemberSuitMembers.handle',
                        'trace' => $th->getTraceAsString()
                    ]
                );
            }
        }
    }

    private function stepsSendSuitMembers(ItemOrder $itemOrder): void
    {
        $product = $itemOrder->product;

        if (!$product->isTypeSuitMembers) {
            return;
        }

        $uuid = $product->parentProduct->client_product_uuid;
        $route = 'courses/' . $uuid . '/member';
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $body = $this->getBodyCreateCustomerSuitMembers($itemOrder->order, $product);
        retry(3, function () use ($suitMembersApiService, $route, $body) {
            return $suitMembersApiService->post($route, $body);
        }, 1000);
    }

    private function getBodyCreateCustomerSuitMembers(Order $order, Product $product): array
    {
        $customer = $order->user;
        return [
            'name' => $customer->name,
            'email' => $customer->email,
            'document' => preg_replace('/\D/', '', $customer->document_number),
            'offer' => $product->id
        ];
    }
}
