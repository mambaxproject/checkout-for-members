<?php

namespace App\Actions;

use App\Enums\{PaymentMethodEnum};
use App\Models\Order;
use App\Services\Utmify\Endpoints\UtmifySendOrder;

readonly class SendDataToUtmify
{
    const APP_FEE_PERCENT = 0.0699;

    public function __construct(
        public Order $order
    ) {}

    public function handle(): array
    {
        $this->order->load('shop', 'items.product', 'user');

        $appUtmify = $this->order->shop
            ->apps()
            ->active()
            ->hasApp('utmify')
            ->first();

        if (! $appUtmify) {
            return ['message' => 'App Utmify não configurado.'];
        }

        $utmData = $this->order->attributes->get('utm') ?? [];

        $dataRequest = [
            'orderId'       => $this->order->client_orders_uuid,
            'platform'      => 'Suitpay',
            'paymentMethod' => $this->translatePaymentMethod($this->order->payment->payment_method),
            'status'        => $this->translatePaymentStatus(),
            'createdAt'     => $this->order->created_at->format('Y-m-d H:i:s'),
            'approvedDate'  => $this->order->updated_at->format('Y-m-d H:i:s'),
            'customer'      => [
                'name'     => $this->order->user->name,
                'email'    => $this->order->user->email,
                'phone'    => $this->order->user->phone_number,
                'document' => $this->order->user->document_number,
                'country'  => 'BR',
            ],
            'products'           => $this->formatProducts($this->order->items),
            'trackingParameters' => [
                'src'          => $utmData['src'] ?? null,
                'sck'          => $utmData['sck'] ?? null,
                'utm_source'   => $utmData['source'] ?? null,
                'utm_campaign' => $utmData['campaign'] ?? null,
                'utm_medium'   => $utmData['medium'] ?? null,
                'utm_content'  => $utmData['content'] ?? null,
                'utm_term'     => $utmData['term'] ?? null,
            ],
            'commission' => [
                'totalPriceInCents'     => $this->order->amount * 100,
                'gatewayFeeInCents'     => 0,
                'userCommissionInCents' => $this->order->invoicingShop * 100,
                'currency'              => 'BRL',
            ],
        ];

        $response = (new UtmifySendOrder($appUtmify->data['api_token']))
            ->sendOrder($dataRequest);

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return [
            'url'      => config('services.utmify.base_url') . '/api-credentials/orders',
            'content'  => $dataRequest,
            'response' => $response->body(),
        ];
    }

    private function translatePaymentMethod($paymentMethod): string
    {
        $utmifyPaymentMethod = [
            PaymentMethodEnum::CREDIT_CARD->name => 'credit_card',
            PaymentMethodEnum::BILLET->name      => 'boleto',
            PaymentMethodEnum::PIX->name         => 'pix',
        ];

        return $utmifyPaymentMethod[$paymentMethod];
    }

    private function translatePaymentStatus(): string
    {
        $payment = $this->order->payment;

        if ($payment->isPending()) {
            return 'waiting_payment';
        }

        if ($payment->isPaid()) {
            return 'paid';
        }

        if ($payment->isCanceled() or $payment->isFailed()) {
            return 'refused';
        }

        throw new \Exception('SendDataToUtmify: Payment status not found.');
    }

    private function formatProducts($items): array
    {
        $formattedProducts = [];

        foreach ($items as $item) {
            $formattedProducts[] = [
                'id'           => '' . $item->product_id,
                'name'         => $item->product->parentProduct->name .' - '. $item->product->name,
                'planId'       => null,
                'planName'     => null,
                'quantity'     => $item->quantity,
                'priceInCents' => $item->product->price * 100,
            ];
        }

        return $formattedProducts;
    }
}
