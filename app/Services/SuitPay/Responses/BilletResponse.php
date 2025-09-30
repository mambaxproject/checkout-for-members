<?php

namespace App\Services\SuitPay\Responses;

readonly class BilletResponse
{

    public string $id;

    public string $status;

    public string $message;

    public string $paymentCode;

    public string $paymentCodeBase64;

    public array $data;

    public function __construct(array $data)
    {
        $this->id                = data_get($data, 'idTransaction', '');
        $this->status            = data_get($data, 'statusTransaction', 'PENDING');
        $this->message           = data_get($data, 'acquirerMessage', 'Boleto gerado com sucesso');
        $this->paymentCode       = data_get($data, 'digitableLine', '');
        $this->paymentCodeBase64 = data_get($data, 'paymentCodeBase64', '');
        $this->data              = data_get($data['data'], []);
    }

    public function get(): array
    {
        return get_object_vars($this);
    }

}
