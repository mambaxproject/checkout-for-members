<?php

namespace App\Actions;

use App\Services\SuitPay\Endpoints\SuitpayCRMService;
use App\Enums\{CRMEventTriggerEnum, CRMOriginEnum, StatusEnum, TypeItemOrderEnum};
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


readonly class SendOrderDataToSuitpayCRM
{
    private bool $isRecurring;

    public function __construct(
        public Order $order
    ) {
        $this->isRecurring = $this->order->item->product->isRecurring;
    }

    public function handle(): array
    {
        $shop   = $this->order->shop;
        $logs   = [];
        $origin = $this->isRecurring ? CRMOriginEnum::SUBSCRIPTION->value : CRMOriginEnum::ORDER->value;

        $rules = $shop->crmRules()
            ->where('status', StatusEnum::ACTIVE->name)
            ->where('origin', $origin)
            ->where('event_trigger', $this->translatePaymentStatus())
            ->get();

        $utmData = $this->order->attributes->get('utm') ?? [];

        foreach ($rules as $rule) {
            try {

                $dataRequest = [
                    "pipeline" => $rule->funnel_id,
                    "columnPipeline" => $rule->step_id,
                    "eventType" => $this->getCRMEventType(),
                    "order" => [
                        "requestNumber" => $this->order->id,
                        "transactionId" => $this->order->payment->external_identification,
                        "recurrencyId" => $this->order->payment->recurrencyId,
                        "status" => $this->order->payment->payment_status,
                        "createdAt" => $this->order->created_at->format('Y-m-d\TH:i:s\Z'),
                        "orderRevenue" => $this->order->amountByTypeUser($shop->owner, $shop),
                    ],
                    "customer" => [
                        "customerName" => $this->order->user->name,
                        "customerEmail" => $this->order->user->email,
                        "customerPhone" => $this->order->user->phone_number,
                        "customerCpf" => $this->order->user->document_number,
                    ],
                    "payment" => [
                        "paymentMethod" => $this->order->payment->payment_method,
                        "amountPaid" => $this->order->amount,
                        "paymentDate" => $this->order->payment?->paid_at?->format('Y-m-d\TH:i:s\Z'),
                    ],
                    "utm" => [
                        "utmSource" => $utmData['source'] ?? null,
                        "utmMedium" => $utmData['medium'] ?? null,
                        "utmCampaign" => $utmData['campaign'] ?? null,
                        "utmContent" => $utmData['content'] ?? null,
                        "utmTerm" => $utmData['term'] ?? null,
                    ],
                    "products" => $this->formatProducts($this->order->items)
                ];

                $response = (new SuitpayCRMService(
                    $shop->client_id_banking,
                    $shop->client_secret_banking,
                ))->sendOrder($dataRequest);

                if ($response->failed()) {
                    throw new \Exception($response->body());
                }

                $logs[] = [
                    'url' => config('services.suitpay.base_url') . '/api/v1/crm/sales/createOpportunitySales',
                    'content' => $dataRequest,
                    'response' => $response->body(),
                    'status_code' => Response::HTTP_OK
                ];

            } catch (\Exception $e) {
                $logs[] = [
                    'url' => config('services.suitpay.base_url') . '/api/v1/crm/sales/createOpportunitySales',
                    'content' => $dataRequest,
                    'response' => json_encode([
                        'message' => $e->getMessage(),
                        'line'    => $e->getLine(),
                        'file'    => $e->getFile(),
                    ]),
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            }
        }

        return $logs;
    }

    private function getCRMEventType(): string
    {
        if ($this->order->item->product->isRecurring) {
            return "SUBSCRIPTION";
        }

        if ($this->order->payment->isPix) {
            if ($this->order->isPaid()) return 'SALE_PIX_CONFIRMED';
            if ($this->order->isPending()) return 'SALE_PIX_PENDING';
        }

        if ($this->order->payment->isBillet) {
            if ($this->order->isPaid()) return 'SALE_BOLETO_CONFIRMED';
            if ($this->order->isPending()) return 'SALE_BOLETO_PENDING';
        }

        if ($this->order->payment->isCreditCard) {
            if ($this->order->isPaid()) return 'SALE_CARD_CONFIRMED';
            if ($this->order->isPending()) return 'SALE_CARD_PENDING';
        }

        throw new \Exception('SendOrderDataToSuitpayCRM: Payment method not found.');
    }

    private function translatePaymentStatus(): CRMEventTriggerEnum
    {
        $payment = $this->order->payment;

        if ($payment->isPending()) {
            return CRMEventTriggerEnum::PENDING;
        }

        if ($payment->isPaid()) {
            return CRMEventTriggerEnum::PAID;
        }

        if ($payment->isCanceled()) {
            return CRMEventTriggerEnum::CANCELED;
        }

        if ($payment->isFailed()) {
            return CRMEventTriggerEnum::FAILED;
        }

        throw new \Exception('SendOrderDataToSuitpayCRM: Payment status not found.');
    }

    private function formatProducts($items): array
    {
        $formattedProducts = [];

        foreach ($items as $item) {
            $formattedProducts[] = [
                'productId'   => '' . $item->product_id,
                'productName' => $item->product->parentProduct->name .' - '. $item->product->name,
                'productDescription' => Str::limit($item->product->parentProduct->description, 250),
                'unitPrice'   => $item->product->price,
                'quantity'    => $item->quantity,
                'totalPrice'  => $item->amount,
                'productType' => 'info',
                'orderBump'   => $item->type == TypeItemOrderEnum::ORDER_BUMP->name,
            ];
        }

        return $formattedProducts;
    }
}
