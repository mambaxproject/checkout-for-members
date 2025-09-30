<?php

namespace App\Jobs\Dashboard\Members;

use App\Models\ItemOrder;
use App\Models\Order;
use App\Services\Members\SuitMembersApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class DeactivateMemberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        foreach ($this->order->items as $item) {
            try {
                $this->stepsSendSuitMembers($item);
            } catch (\Throwable $th) {
                Log::channel('members')->error(
                    'Erro ao enviar para api de membros para inativar usúario.',
                    [
                        'error' => $th->getMessage(),
                        'function' => 'DeactivateMemberJob.handle',
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
        $route = 'courses/' . $uuid . '/member/peding';
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $body = ['email' => $this->order->user->email];
        retry(3, function () use ($suitMembersApiService, $route, $body) {
            return $suitMembersApiService->post($route, $body);
        }, 1000);
    }
}
