<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Facades\Validator;

class PaymentData
{
    public $requestNumber;
    public $dueDate;
    public $amount;
    public $shippingAmount;
    public $discountAmount;
    public $usernameCheckout;
    public $callbackUrl;
    public $client;
    public $products;
    public $split;

    public function __construct(array $data)
    {
        $validator = Validator::make($data, [
            'requestNumber'               => ['required', 'string'],
            'dueDate'                     => ['required', 'date'],
            'amount'                      => ['required', 'numeric', 'min:0.01'],
            'shippingAmount'              => ['nullable', 'numeric', 'min:0'],
            'discountAmount'              => ['nullable', 'numeric', 'min:0'],
            'usernameCheckout'            => ['nullable', 'string'],
            'callbackUrl'                 => ['required', 'url'],
            'client.name'                 => ['required', 'string'],
            'client.document'             => ['required', 'string'],
            'client.phoneNumber'          => ['required', 'string'],
            'client.email'                => ['required', 'email'],
            'client.address.codIbge'      => ['required', 'string'],
            'client.address.street'       => ['required', 'string'],
            'client.address.number'       => ['required', 'string'],
            'client.address.complement'   => ['nullable', 'string'],
            'client.address.zipCode'      => ['required', 'string'],
            'client.address.neighborhood' => ['required', 'string'],
            'client.address.city'         => ['required', 'string'],
            'client.address.state'        => ['required', 'string', 'size:2'],
            'products'                    => ['required', 'array', 'min:1'],
            'products.*.description'      => ['required', 'string'],
            'products.*.quantity'         => ['required', 'integer', 'min:1'],
            'products.*.value'            => ['required', 'numeric', 'min:0.01'],
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $this->requestNumber       = $data['requestNumber'];
        $this->dueDate             = $data['dueDate'];
        $this->amount              = $data['amount'];
        $this->shippingAmount      = $data['shippingAmount'] ?? 0.0;
        $this->discountAmount      = $data['discountAmount'] ?? 0.0;
        $this->usernameCheckout    = $data['usernameCheckout'] ?? config('services.suitpay.username_checkout');
        $this->callbackUrl         = $data['callbackUrl'];
        $this->client              = $data['client'];
        $this->products            = $data['products'];
        $this->split               = $data['splitGateway'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'requestNumber'         => $this->requestNumber,
            'dueDate'               => $this->dueDate,
            'amount'                => $this->amount,
            'shippingAmount'        => $this->shippingAmount,
            'discountAmount'        => $this->discountAmount,
            'usernameCheckout'      => $this->usernameCheckout,
            'callbackUrl'           => $this->callbackUrl,
            'client'                => $this->client,
            'products'              => $this->products,
            'splitGateway'          => $this->split,
        ];
    }
}
