<?php

namespace App\DataTransferObjects;

use AllowDynamicProperties;
use Illuminate\Support\Facades\Validator;

#[AllowDynamicProperties] class PaymentDataSubscription
{
    public string $requestNumber;

    public string $type;

    public string $frequency;

    public bool $automaticRenovation;

    public mixed $firstDateBilling;

    public string $firstChargeValue;

    public string $callbackUrl;

    public array $client;

    public array $card;

    public array $products;

    public $split;

    public function __construct(array $data)
    {
        $validator = Validator::make($data, [
            'requestNumber'               => ['required', 'string'],
            'frequency'                   => ['required', 'string', 'in:FORTNIGHTLY,MONTHLY,QUARTERLY,SEMI_ANNUAL'],
            'numberCharges'               => ['nullable', 'integer', 'min:1'],
            'automaticRenovation'         => ['nullable', 'bool'],
            'firstDateBilling'            => ['nullable', 'date_format:Y-m-d'],
            'firstChargeValue'            => ['nullable', 'numeric', 'min:0.01'],
            'chargeValue'                 => ['nullable', 'numeric', 'min:0.01'],
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
            'card.number'                 => ['required', 'string', 'size:16'],
            'card.expirationMonth'        => ['required', 'string', 'size:2', 'regex:/^(0[1-9]|1[0-2])$/'],
            'card.expirationYear'         => ['required', 'string', 'size:4', 'regex:/^(20[2-9][0-9])$/'],
            'card.cvv'                    => ['required', 'string', 'size:3'],
            'card.installment'            => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12'],
            'card.amount'                 => ['required', 'numeric', 'min:0.01'],
            'products'                    => ['required', 'array', 'min:1'],
            'products.*.productName'      => ['required', 'string'],
            'products.*.idCheckout'       => ['required', 'string'],
            'products.*.quantity'         => ['required', 'integer', 'min:1'],
            'products.*.value'            => ['required', 'numeric', 'min:0.01'],
            'discountAmount'              => ['nullable'],
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $this->frequency           = $data['frequency'] ?? 'MONTHLY';
        $this->numberCharges       = $data['numberCharges'] ?? 12;
        $this->automaticRenovation = $data['automaticRenovation'] ?? false;
        $this->firstDateBilling    = $data['firstDateBilling'] ?? now()->addMonth()->format('Y-m-d');
        $this->firstChargeValue    = $data['firstChargeValue'] ?? $data['card']['amount'];
        $this->chargeValue         = $data['chargeValue'] ?? $data['card']['amount'];
        $this->requestNumber       = $data['requestNumber'];
        $this->callbackUrl         = $data['callbackUrl'];
        $this->client              = $data['client'];
        $this->card                = $data['card'];
        $this->products            = $data['products'];
        $this->discountAmount      = $data['discountAmount'] ?? 0;
        $this->split               = $data['splitGateway'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'requestNumber'       => $this->requestNumber,
            'type'                => 'credit',
            'usernameCheckout'    => config('services.suitpay.username_checkout'),
            'frequency'           => $this->frequency,
            'numberCharges'       => $this->numberCharges,
            'automaticRenovation' => $this->automaticRenovation,
            'firstDateBilling'    => $this->firstDateBilling,
            'firstChargeValue'    => $this->firstChargeValue,
            'chargeValue'         => $this->chargeValue,
            'callbackUrl'         => $this->callbackUrl,
            'client'              => $this->client,
            'card'                => $this->card,
            'products'            => $this->products,
            'discountAmount'      => $this->discountAmount,
            'splitGateway'        => $this->split,
        ];
    }
}
