<?php

namespace App\Services\SuitPay\Responses;

readonly class CreditCardResponse
{
    public string $id;

    public string $status;

    public string $message;

    public string $acquirerMessage;

    public string $cardToken;

    public function __construct(array $data)
    {
        $this->id              = data_get($data, 'transactionId', '');
        $this->status          = data_get($data, 'statusTransaction', 'FAILED');
        $this->message         = data_get($data, 'acquirerMessage', '');
        $this->acquirerMessage = data_get($data, 'acquirerMessage', '');
        $this->cardToken       = data_get($data, 'cardToken', '');
    }

    public function get(): array
    {
        return get_object_vars($this);
    }

}
