<?php

namespace App\Services\SuitPay\Responses;

readonly class SubscriptionResponse
{
    public string $id;

    public string $recurrencyId;

    public string $status;

    public string $message;

    public string $acquirerMessage;

    public string $cardToken;

    public string $recurrencyLastBillingDate;

    public string $recurrencyNumberFailedBillingAttempts;

    public string $recurrencyStatus;

    public string $recurrencyLastChargeId;

    public function __construct(array $data)
    {
        $this->id                                    = data_get($data, 'transactionId.0', '');
        $this->recurrencyId                          = data_get($data, 'recurrencyId', '');
        $this->status                                = data_get($data, 'statusTransaction', 'FAILED');
        $this->message                               = data_get($data, 'acquirerMessage', '');
        $this->acquirerMessage                       = data_get($data, 'acquirerMessage', '');
        $this->cardToken                             = data_get($data, 'cardToken', '');
        $this->recurrencyLastBillingDate             = data_get($data, 'recurrencyLastBillingDate', '');
        $this->recurrencyNumberFailedBillingAttempts = data_get($data, 'recurrencyNumberFailedBillingAttempts', '');
        $this->recurrencyStatus                      = data_get($data, 'recurrencyStatus', '');
        $this->recurrencyLastChargeId                = data_get($data, 'recurrencyLastChargeId', '');
    }

    public function get(): array
    {
        return get_object_vars($this);
    }

}
