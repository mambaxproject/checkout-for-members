<?php

namespace App\Actions;

use App\Enums\PaymentMethodEnum;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

readonly class SendDataToReportana
{

    public function __construct(
        public Order $order
    ) { }

    public function handle(): array
    {
        $this->order->load('shop', 'items.product', 'user.address', 'payments');

        $appReportana = $this->order->shop
            ->apps()
            ->active()
            ->hasApp('reportana')
            ->first();

        if (!$appReportana) {
            return ['message' => 'App Reportana não configurado.'];
        }

        $items         = $this->getItems();
        $paymentMethod = $this->getFormattedPaymentMethod();
        $paymentStatus = $this->getFormattedPaymentStatus();
        $addressUser   = $this->getFormattedAddressUser();

        $billetUrl = match ($paymentMethod) {
            PaymentMethodEnum::PIX->name    => route('checkout.checkout.thanks', $this->order),
            PaymentMethodEnum::BILLET->name => $this->order->getFirstMediaUrl('billetUrl') ?? NULL,
            default                         => NULL,
        };

        $dataRequest = [
            'reference_id'        => $this->order->client_orders_uuid,
            'number'              => $this->order->client_orders_uuid,
            'admin_url'           => route('dashboard.orders.show', ['orderUuid' => $this->order->client_orders_uuid]),
            'customer_name'       => $this->order->user->name,
            'customer_email'      => $this->order->user->email,
            'customer_phone'      => $this->order->user->phone_number,
            'billing_address'     => $addressUser,
            'shipping_address'    => $addressUser,
            'line_items'          => $items,
            'currency'            => 'BRL',
            'total_price'         => $this->order->amount,
            'subtotal_price'      => $this->order->amount,
            'payment_status'      => $paymentStatus,
            'payment_method'      => $paymentMethod,
            'tracking_numbers'    => NULL,
            'referring_site'      => NULL,
            'status_url'          => NULL,
            'billet_url'          => $billetUrl,
            'billet_line'         => $this->order->payments?->last()?->external_content,
            'billet_expired_at'   => $this->order->payments?->last()?->due_date,
            'original_created_at' => $this->order->created_at->format('Y-m-d H:i'),
        ];

        $api_url = 'https://api.reportana.com/2022-05/orders';

        $response = Http::retry(5, 5000)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.base64_encode($appReportana->data['client_id'].':'.$appReportana->data['client_secret']),
            ])
            ->post($api_url, $dataRequest);

        return [
            'url'      => $api_url,
            'content'  => $dataRequest,
            'response' => $response->json(),
        ];
    }

    private function getItems(): array
    {
        return array_map(fn($item) => [
            'title'           => $item->product->name,
            'variant_title'   => $item->product->name,
            'quantity'        => $item->quantity,
            'price'           => $item->amount,
            'path'            => $item->product->url,
            'image_url'       => $item->product->featuredImageUrl,
            'tracking_number' => "",
        ], $this->order->items->all());
    }

    private function getFormattedPaymentMethod()
    {
        return ($this->order->paymentMethod == PaymentMethodEnum::BILLET->name)
            ? 'boleto'
            : $this->order->paymentMethod;
    }

    private function getFormattedPaymentStatus(): string
    {
        if ($this->order->isPaid()) {
            return 'PAID';
        } elseif ($this->order->isCanceled()) {
            return 'NOT_PAID';
        }

        return 'PENDING';
    }

    private function getFormattedAddressUser(): array
    {
        return [
            'name'          => $this->order->user->name,
            'first_name'    => $this->order->user->first_name,
            'last_name'     => $this->order->user->last_name,
            'company'       => $this->order->shop->name,
            'phone'         => $this->order->user->phone_number,
            'zip'           => $this->order->user->address->zipcode,
            'address1'      => $this->order->user->address->street_address,
            'address2'      => $this->order->user->address->neighborhood,
            'city'          => $this->order->user->address->city,
            'province'      => $this->order->user->address->state,
            'province_code' => $this->order->user->address->state,
            'country'       => "BR",
            'country_code'  => "BR",
            'latitude'      => NULL,
            'longitude'     => NULL,
        ];
    }

}