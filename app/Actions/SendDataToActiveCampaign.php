<?php

namespace App\Actions;

use App\Models\Order;
use App\Services\ActiveCampaign\ActiveCampaign;

readonly class SendDataToActiveCampaign
{
    public function __construct(
        public Order $order
    ) {}

    public function handle(): array
    {
        $this->order->load('shop', 'items.product', 'user');

        $productsNames = implode(', ', $this->order->items->map(fn ($item) => $item->product->name)->toArray());

        $appActiveCampaign = $this->order->shop
            ->apps()
            ->active()
            ->hasApp('active-campaign')
            ->first();

        if (! $appActiveCampaign) {
            return ['message' => 'App Active Campaign não configurado.'];
        }

        $activeCampaign = new ActiveCampaign(
            $appActiveCampaign->data['api_url'],
            $appActiveCampaign->data['api_key'],
        );

        $customFields = $activeCampaign->customFields()->all();

        $dataRequest = [
            'email'       => $this->order->user->email,
            'firstName'   => $this->order->user->name ?? '',
            'lastName'    => $this->order->user->last_name ?? '',
            'phone'       => $this->order->user->phone_number ?? '',
            'fieldValues' => [
                [
                    'field' => current(array_filter($customFields['fields'], fn ($item) => $item['perstag'] == 'LINKPAGAMENTO'))['id'],
                    'value' => route('checkout.checkout.thanks', $this->order->orderHash),
                ],
            ],
        ];

        $contact = $activeCampaign->contacts()->createOrUpdate($dataRequest);

        $payment_status = ucfirst(strtolower($this->order->paymentStatusOriginal));

        $tag = $activeCampaign->tags()->firstOrCreate([
            'tag'     => 'Pedido ' . $payment_status . ' - ' . $productsNames,
            'tagType' => 'contact',
        ]);

        $activeCampaign->contacts()->addTag([
            'contact' => $contact['contact']['id'],
            'tag'     => $tag['id'],
        ]);

        return [
            'url'      => $appActiveCampaign->data['api_url'],
            'content'  => $dataRequest,
            'response' => $contact,
        ];
    }

}
