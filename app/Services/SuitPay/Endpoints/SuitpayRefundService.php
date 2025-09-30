<?php

namespace App\Services\SuitPay\Endpoints;

class SuitpayRefundService extends BaseEndpoint
{
    public function __construct(string $ci, string $cs)
    {
        parent::__construct($ci, $cs);
    }

    public function processRefundCreditCard(string $externalIdentification): array
    {
        try {
            return $this->service->init()->post('/api/v3/gateway/cancel', [
                'transactionId' => $externalIdentification,
            ])->json();
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro ao processar o reembolso.',
                'error'   => $e->getMessage(),
            ];
        }
    }

    public function processRefundPix(string $externalIdentification, string $description = ''): array
    {
        try {
            return $this->service->init()->post('/api/v1/gateway/pix/refund', [
                'id'          => $externalIdentification,
                'description' => $description,
            ])->json();
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro ao processar o reembolso.',
                'error'   => $e->getMessage(),
            ];
        }
    }

}
