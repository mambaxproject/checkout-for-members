<?php

namespace App\Listeners\Apps;

use App\Events\{OrderApproved, OrderCanceled, OrderCreated, OrderFailed, OrderUpdated};
use App\Models\{Order, OrderPayment};
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class SendWebhooksOrderData implements ShouldBeUnique, ShouldQueue
{
    public function handle($event): void
    {
        $order      = $event->order;
        $productIds = $order->items()
            ->with([
                'product' => fn ($query) => $query->select('id', 'parent_id')->withTrashed(),
            ])
            ->get(['item_orders.id', 'product_id'])->pluck('product.parent_id')
            ->toArray();

        $webhooks = $order->shop->webhooks()
            ->whereHas('events', fn ($query) => $query->whereIn('name', $this->getEventName($event)))
            ->where(function ($query) use ($productIds) {
                $query->whereDoesntHave('products')
                    ->orWhereHas('products', fn ($q) => $q->whereIn('products.id', $productIds)
                    );
            })
            ->get();

        $dataRequest = $this->prepareDataRequest($order);

        foreach ($webhooks as $webhook) {
            try {
                $response = Http::retry(5, 5000)
                    ->post($webhook->url, $dataRequest);

                $event->order->shop->logRequests()->create([
                    'url'         => $webhook->url,
                    'content'     => $dataRequest,
                    'response'    => $response->body(),
                    'status_code' => Response::HTTP_OK,
                ]);
            } catch (\Exception $e) {
                $event->order->shop->logRequests()->create([
                    'url'      => $webhook->url,
                    'content'  => $dataRequest,
                    'response' => json_encode([
                        'message' => $e->getMessage(),
                        'line'    => $e->getLine(),
                        'file'    => $e->getFile(),
                    ]),
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ]);
            }
        }
    }

    private function prepareDataRequest(Order $order): array
    {
        $order->load(['shop', 'items.product' => function ($q) {
            $q->withTrashed();
        }, 'user']);

        $payment  = $order->payment;
        $customer = $order->user;

        return [
            'id'                     => $order->id,
            'currency'               => 'BRL',
            'amount'                 => $order->amount,
            'payment_identification' => $payment->external_identification,
            'payment_method'         => $payment->payment_method,
            'payment_status'         => $this->status($payment),
            'payment_installments'   => $payment->installments ?? 1,
            'external_url'           => $payment->external_url ?? null,
            'external_content'       => $payment->external_content ?? null,
            'due_date'               => $payment->due_date->format('Y-m-d'),
            'created_at'             => $order->created_at->toIso8601String(),
            'updated_at'             => $order->updated_at->toIso8601String(),
            'paid_at'                => $payment->paid_at ? $payment->paid_at->toIso8601String() : null,
            'customer'               => [
                'name'            => $customer->name,
                'email'           => $customer->email,
                'document_number' => $customer->document_number,
                'phone_number'    => $customer->phone_number,
            ],
            'items' => $this->items($order),
        ];
    }

    private function items(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id'        => $item->product->parentProduct->id,
                'offer_id'  => $item->product->id,
                'title'     => $item->product->name,
                'quantity'  => $item->quantity,
                'price'     => $item->product->price,
                'path'      => route('checkout.checkout.product', $item->product->code),
                'image_url' => $item->product->parentProduct->featuredImageUrl,
            ];
        }

        return $items;
    }

    private function status(OrderPayment $payment): string
    {
        if ($payment->isPaid()) {
            return 'PAID';
        }

        if ($payment->isPending()) {
            return 'PENDING';
        }

        if ($payment->isCanceled()) {
            return 'CANCELLED';
        }

        return 'CANCELLED';
    }

    private function getEventName($event): array
    {
        $events = [
            OrderApproved::class => ['Pagamento autorizado'],
            OrderCreated::class  => ['Pedido criado'],
            OrderFailed::class   => ['Pagamento recusado'],
            OrderUpdated::class  => ['Pedido atualizado'],
            OrderCanceled::class => ['Pedido cancelado']
        ];

        return $events[get_class($event)];
    }

    public function shouldQueue($event): bool
    {
        return $event->order->shop->webhooks()
            ->whereHas('events', fn ($query) => $query->whereIn('name', $this->getEventName($event)))
            ->exists();
    }

    public function uniqueId($event): int
    {
        return $event->order->id;
    }
}
