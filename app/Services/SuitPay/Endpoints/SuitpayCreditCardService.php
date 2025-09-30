<?php

namespace App\Services\SuitPay\Endpoints;

use App\DataTransferObjects\PaymentDataCreditCard;
use App\Services\SuitPay\Responses\CreditCardResponse;

class SuitpayCreditCardService extends BaseEndpoint
{
    public function __construct(string $ci, string $cs)
    {
        parent::__construct($ci, $cs);
    }

    public function process(array $paymentData): array
    {
        try {
            $paymentData = new PaymentDataCreditCard($paymentData);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        $payment = $this->service->init()->post('/api/v3/gateway/card', $paymentData->toArray());

        if ($payment->failed()) {
            throw new \Exception($payment->body());
        }

        $responsePayment = new CreditCardResponse($payment->json());

        return $responsePayment->get();
    }

    public function cardFees(float $value): array
    {
        $payment = $this->service->init()->get('api/v1/gateway/fee-simulator-gateway', [
            'value' => $value,
        ]);

        if ($payment->failed()) {
            throw new \Exception($payment->body());
        }

        $card                     = $payment->json()['list'][0];
        $avista                   = $card['valueCredito'];
        $response['values']       = [...array_slice($card, 3, 11), 'value1x' => $avista];
        $response['installments'] = [...array_slice($card, 14), 'installment1x' => $avista];

        return $response;
    }

}
