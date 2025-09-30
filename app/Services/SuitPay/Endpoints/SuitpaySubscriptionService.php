<?php

namespace App\Services\SuitPay\Endpoints;

use App\DataTransferObjects\PaymentDataSubscription;
use App\Services\SuitPay\Responses\SubscriptionResponse;
use Illuminate\Support\Facades\Log;

class SuitpaySubscriptionService extends BaseEndpoint
{
    public function __construct(string $ci, string $cs)
    {
        parent::__construct($ci, $cs);
    }

    public function process(array $paymentData): array
    {
        $paymentData     = new PaymentDataSubscription($paymentData);
        $payment         = $this->service->init()->post('/api/v1/recurrency/create', $paymentData->toArray());
        $responsePayment = new SubscriptionResponse($payment->json());

        return $responsePayment->get();
    }

    public function updateCreditCard(array $cardData): array
    {
        try {
            return $this->service->init()->post('/api/v1/recurrency/update-card', $cardData)->json();
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro ao atualizar o cartão de crédito.',
                'error'   => $e->getMessage(),
            ];
        }
    }

    public function manualRetry(array $subscriptionData): array
    {
        try {
            return $this->service->init()->post('/api/v1/recurrency/manual-retry', $subscriptionData)->json();
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro na retentativa de pagamento.',
                'error'   => $e->getMessage(),
            ];
        }
    }

    public function updateSubscription(array $subscriptionData): array
    {
        try {
            return $this->service->init()->post('/api/v1/recurrency/manage', $subscriptionData)->json();
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro ao atualizar a assinatura.',
                'error'   => $e->getMessage(),
            ];
        }
    }

    public function getDetails(string $recurrencyId): array
    {
        try {
            if (empty($recurrencyId)) {
                return [];
            }

            return cache()->remember(
                'subscription_details_' . $recurrencyId,
                now()->addHour(),
                fn() => $this->service->init()->get('/api/v1/recurrency/details', ['recurrencyId' => $recurrencyId])->json()
            );
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro ao obter os detalhes da assinatura.',
                'error'   => $e->getMessage(),
            ];
        }
    }
    public function getQuantityPaidTransactionsRecurrency(string $recurrencyId): int
    {
        try {
            $data = $this->service
                ->init()
                ->get('/api/v1/recurrency/details', ['recurrencyId' => $recurrencyId])
                ->json();

            $transactions = $data['transactions'] ?? [];

            $paidOutTransactions = array_filter(
                $transactions,
                fn($transaction) => ($transaction['statusTransaction'] ?? null) === 'PAID_OUT'
            );

            return count($paidOutTransactions);
        } catch (\Throwable $th) {
            Log::channel('database')->error(
                'Erro ao obter quantidade de assinaturas pagas.',
                [
                    'function' => 'SuitpaySubscriptionService.getQuantityTransactionsRecurrency',
                    'route' => '/api/v1/recurrency/details',
                    'body' => ['recurrencyId' => $recurrencyId],
                    'error' => $th->getMessage()
                ]
            );

            throw $th;
        }
    }
}
