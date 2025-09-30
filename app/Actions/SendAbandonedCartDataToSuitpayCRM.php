<?php

namespace App\Actions;

use App\Models\AbandonedCart;
use App\Services\SuitPay\Endpoints\SuitpayCRMService;
use App\Enums\{CRMEventTriggerEnum, CRMOriginEnum, StatusEnum, TypeItemOrderEnum};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

readonly class SendAbandonedCartDataToSuitpayCRM
{
    public function __construct(
        public AbandonedCart $abandonedCart,
        private bool $isNotification,
    ) {}

    public function handle(): array
    {
        $shop = $this->abandonedCart->shop;

        $rules = $shop
            ->crmRules()
            ->where('status', StatusEnum::ACTIVE->name)
            ->where('origin', CRMOriginEnum::ABANDONED_CART->value)
            ->when($this->isNotification, function ($q) {
                $q->where('event_trigger', CRMEventTriggerEnum::NOTIFICATION->value);
            })
            ->when(!$this->isNotification, function ($q) {
                $q->where('event_trigger', $this->abandonedCart->status);
            })
            ->get();

        $utmData = $this->abandonedCart->traces->toArray() ?? [];

        foreach ($rules as $rule) {

            try {

                $dataRequest = [
                    "pipeline" => $rule->funnel_id,
                    "columnPipeline" => $rule->step_id,
                    "eventType" => 'CART_ABANDONED',
                    "order" => [
                        "requestNumber" => $this->abandonedCart->id,
                        "status" => $this->abandonedCart->status->value,
                        "createdAt" => $this->abandonedCart->created_at->format('Y-m-d\TH:i:s\Z'),
                        "orderRevenue" => $this->abandonedCart->amount,
                    ],
                    "customer" => [
                        "customerName" => $this->abandonedCart->name,
                        "customerEmail" => $this->abandonedCart->email,
                        "customerPhone" => $this->abandonedCart->phone_number,
                    ],
                    "payment" => [
                        "paymentMethod" => $this->abandonedCart->payment_method,
                    ],
                    "utm" => [
                        "utmSource" => $utmData['utm_source'] ?? null,
                        "utmMedium" => $utmData['utm_medium'] ?? null,
                        "utmCampaign" => $utmData['utm_campaign'] ?? null,
                        "utmContent" => $utmData['utm_content'] ?? null,
                        "utmTerm" => $utmData['utm_term'] ?? null,
                    ],
                    "products" => $this->formatProducts($this->abandonedCart)
                ];

                $response = (new SuitpayCRMService(
                    $shop->client_id_banking,
                    $shop->client_secret_banking,
                ))->sendOrder($dataRequest);

                Log::info('Response Suitpay', ['response' => $response->body()]);

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

    private function formatProducts(AbandonedCart $cart): array
    {
        $formattedProducts = [];

        if (!$cart->order) {
            return [
                [
                    'productId'   => '' . $cart->product_id,
                    'productName' => $cart->product->parentProduct->name .' - '. $cart->product->name,
                    'unitPrice'   => $cart->product->price,
                    'quantity'    => 1,
                    'totalPrice'  => $cart->product->price,
                    'productType' => 'info',
                    'orderBump'   => false,
                    'productDescription' => Str::limit($cart->product->parentProduct->description),
                ]
            ];
        }

        foreach ($cart->order->items as $item) {
            $formattedProducts[] = [
                'productId'   => '' . $item->product_id,
                'productName' => $item->product->parentProduct->name .' - '. $item->product->name,
                'productDescription' => Str::limit($cart->product->parentProduct->description),
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
