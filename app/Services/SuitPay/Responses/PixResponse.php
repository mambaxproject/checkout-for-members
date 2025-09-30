<?php

namespace App\Services\SuitPay\Responses;

readonly class PixResponse
{

    public string $id;

    public string $status;

    public string $message;

    public string $paymentCode;

    public string $paymentCodeBase64;

    public function __construct(array $data)
    {
        $this->id                = data_get($data, 'idTransaction', '');
        $this->status            = data_get($data, 'statusTransaction', 'PENDING');
        $this->message           = data_get($data, 'acquirerMessage', 'PIX gerado com sucesso');
        $this->paymentCode       = data_get($data, 'paymentCode', '');
        $this->paymentCodeBase64 = data_get($data, 'paymentCodeBase64', '');
    }

    public function get(): array
    {
        return get_object_vars($this);
    }

}
