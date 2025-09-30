<?php

namespace App\Services\Checkout;

use App\Enums\{PaymentMethodEnum};
use App\Events\{OrderCreated, OrderFailed};
use App\Http\Requests\Checkout\PaymentRequest;
use App\Models\{Order, OrderPayment, Shop};
use App\Services\AbandonedCartService;
use App\Services\Checkout\Cart\CheckoutCartService;
use App\Services\SuitPay\Endpoints\{SuitpayBilletService,
    SuitpayCreditCardService,
    SuitpayPixService,
    SuitpaySubscriptionService};
use Illuminate\Support\Carbon;

class CheckoutPaymentService
{
    private Order $order;

    private OrderPayment $payment;

    private CheckoutCartService $cart;

    private CheckoutSplitService $checkoutSplitService;

    private Shop $shop;

    private string $ci;

    private string $cs;

    public function __construct(Order $order, CheckoutCartService $cart)
    {
        $this->order                = $order;
        $this->cart                 = $cart;
        $this->checkoutSplitService = new CheckoutSplitService($order, $cart);
        $this->shop                 = $cart->shop();

        $this->ci = $this->shop->client_id_banking;
        $this->cs = $this->shop->client_secret_banking;
    }

    public function execute(PaymentRequest $paymentRequest): OrderPayment
    {
        $this->payment    = $this->createPayment($paymentRequest);
        $gateway_response = null;

        try {

            if ($this->payment->isPix) {
                $gateway_response = $this->pix();
            } else if ($this->payment->isCreditCard) {
                if ($this->order->item->product->isRecurring) {
                    $gateway_response = $this->creditCardRecurring($paymentRequest);
                } else {
                    $gateway_response = $this->creditCard($paymentRequest);
                }
            } else if ($this->payment->isBillet) {
                $gateway_response = $this->billet();
            } else {
                throw new \Exception('Payment method not found');
            }

            $orderPaymentData = [
                'payment_status'           => $gateway_response['status'],
                'external_identification'  => $gateway_response['id'],
                'external_content'         => $gateway_response['paymentCode'] ?? null,
                'recurrency_id'            => $gateway_response['recurrencyId'] ?? null,
                'payment_gateway_response' => json_encode($gateway_response),
            ];

            if ($this->payment->isBillet) {
                $orderPaymentData['external_url'] = $gateway_response['data']['url'];
            }

            $this->payment->update($orderPaymentData);

            $this->order->update([
                'net_amount'          => $gateway_response['netAmount'] ?? $this->order->amount,
                'card_token_customer' => $gateway_response['cardToken'] ?? null,
            ]);
        } catch (\Throwable $th) {

            $this->payment->update([
                'payment_status'           => 'FAILED',
                'payment_gateway_response' => $th->getMessage() . '; file:' . $th->getFile() . '; line:' . $th->getLine() . '; response_body:' . json_encode($gateway_response),
            ]);
        }

        if (! $this->payment->isFailed()) {
            event(new OrderCreated($this->order));

            if ($this->payment->isCreditCard()) {
                (new AbandonedCartService)->checkCanConvertCart($this->order);
            }
        }

        if ($this->payment->isFailed() && $this->payment->isCreditCard()) {
            event(new OrderFailed($this->order));
        }

        return $this->payment->refresh();
    }

    private function createPayment($paymentData)
    {
        $daysDueDateBilletd = $this->cart
            ->getPrincipalProduct()
            ->parentProduct
            ->getValueSchemalessAttributes('daysDueDateBilletd') ?? 5;

        $dueDate = $paymentData->payment['paymentMethod'] == PaymentMethodEnum::BILLET->name
            ? Carbon::now()->addDays($daysDueDateBilletd)->format('Y-m-d')
            : date('Y-m-d');

        return $this->order->payments()->create([
            'payment_method' => $paymentData->payment['paymentMethod'],
            'amount'         => $this->order->amount,
            'due_date'       => $dueDate,
            'installments'   => $this->cart->installments(),
        ]);
    }

    private function pix(): array
    {
        return (new SuitpayPixService($this->ci, $this->cs))
            ->process($this->preparePixBilletData());
    }

    private function billet(): array
    {
        return (new SuitpayBilletService($this->ci, $this->cs))
            ->process($this->preparePixBilletData(true));
    }

    private function creditCardRecurring(PaymentRequest $paymentRequest): array
    {
        $product = $this->order->item->product;
        $payload = $this->prepareCreditCardData($paymentRequest->payment);

        $payload['frequency']           = $product->cyclePayment;
        $payload['automaticRenovation'] = true;
        $payload['firstDateBilling']    = date('Y-m-d');
        $payload['firstChargeValue']    = number_format($this->order->first_amount, 2);
        $payload['chargeValue']         = number_format($this->order->amount, 2);
        $payload['card']['installment'] = 1;
        $payload['card']['amount']      = number_format($this->order->first_amount, 2);

        return (new SuitpaySubscriptionService($this->ci, $this->cs))->process($payload);
    }

    private function creditCard(PaymentRequest $paymentRequest): array
    {
        return (new SuitpayCreditCardService($this->ci, $this->cs))
            ->process($this->prepareCreditCardData($paymentRequest->payment));
    }

    private function preparePixBilletData(bool $billet = false): array
    {
        $client = $this->order->user;

        $payload = [
            'requestNumber'    => $this->order->client_orders_uuid,
            'dueDate'          => $this->payment->due_date,
            'amount'           => round($this->order->amount, 2),
            'shippingAmount'   => 0,
            'discountAmount'   => $this->cart->discount(),
            'usernameCheckout' => config('services.suitpay.username_checkout'),
            'callbackUrl'      => route('api.public.webhooks.suitpay.updateOrderByTransation'),
            'client'           => [
                'name'        => $client->name,
                'document'    => $client->document_number,
                'phoneNumber' => $client->phone_number,
                'email'       => $client->email,
                'address'     => [
                    'street'       => 'Rua Paraíba',
                    'number'       => '150',
                    'complement'   => '',
                    'neighborhood' => 'Goiânia 2',
                    'city'         => 'Goiânia',
                    'state'        => 'GO',
                    'zipCode'      => '74663-520',
                    'codIbge'      => '5208707',
                ],
            ],
        ];

        $split = $this->checkoutSplitService->split();

        if (count($split)) {
            $payload['splitGateway'] = $split;
        }

        foreach ($this->order->items as $item) {
            $payload['products'][] = [
                'description' => $item->product->name,
                'quantity'    => $item->quantity,
                'value'       => $item->amount,
            ];
        }

        return $payload;
    }

    private function prepareCreditCardData($payment): array
    {
        $cardExpiration = ! empty($payment['cardExpiration']) ? explode('/', $payment['cardExpiration']) : '';
        $client         = $this->order->user;

        $card = ! empty($payment['cardToken'])
            ? [
                'cardToken'   => $payment['cardToken'],
                'installment' => intval($payment['installments']),
                'amount'      => round($this->order->amount, 2),
            ]
            : [
                'number'          => preg_replace('/\D/', '', $payment['cardNumber']),
                'cvv'             => $payment['cardCvv'],
                'installment'     => intval($payment['installments']),
                'expirationMonth' => $cardExpiration[0],
                'expirationYear'  => substr(date('Y'), 0, 2) . $cardExpiration[1],
                'amount'          => round($this->order->amount, 2),
            ];

        $payload = [
            'requestNumber'  => $this->order->client_orders_uuid,
            'callbackUrl'    => route('api.public.webhooks.suitpay.updateOrderByTransation'),
            'card'           => $card,
            'discountAmount' => $this->order->discounts->sum('amountDiscount'),
            'client'         => [
                'name'        => $client->name,
                'document'    => $client->document_number,
                'phoneNumber' => preg_replace('/\D/', '', $client->phone_number),
                'email'       => $client->email,
                'address'     => [
                    'street'       => 'Rua Paraíba',
                    'number'       => '150',
                    'complement'   => '',
                    'neighborhood' => 'Goiânia 2',
                    'city'         => 'Goiânia',
                    'state'        => 'GO',
                    'zipCode'      => '74663-520',
                    'codIbge'      => '5208707',
                ],
            ],
        ];

        $split = $this->checkoutSplitService->split();

        if (count($split)) {
            $payload['splitGateway'] = $split;
        }

        foreach ($this->order->items as $key => $item) {
            $product = $item->product()->first();

            $currentProduct = [
                'productName' => $product->name,
                'idCheckout'  => '' . $product->id,
                'quantity'    => $item->quantity,
                'value'       => $product->hasFirstPayment ? $product->priceFirstPayment : $item->amount,
            ];

            if (!$key) {
                $currentProduct['value'] -= $this->order->discounts->sum('amountDiscount');
            }

            $payload['products'][] = $currentProduct;
        }

        return $payload;
    }
}
