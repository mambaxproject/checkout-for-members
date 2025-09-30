<?php

namespace App\Services\SuitPay\Endpoints;

use App\DataTransferObjects\PaymentData;
use App\Services\SuitPay\Responses\PixResponse;

class SuitpayPixService extends BaseEndpoint
{

    public function __construct(string $ci, string $cs)
    {
        parent::__construct($ci, $cs);
    }

    public function process(array $paymentData): array
    {
        try {
            $paymentData = new PaymentData($paymentData);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        $payment         = $this->service->init()->post('/api/v1/gateway/request-qrcode', $paymentData->toArray());

        if ($payment->failed()) {
            throw new \Exception($payment->body());
        }

        $responsePayment = new PixResponse($payment->json());

        return $responsePayment->get();
    }

}
