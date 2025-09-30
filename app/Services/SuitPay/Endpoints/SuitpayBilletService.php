<?php

namespace App\Services\SuitPay\Endpoints;

use App\DataTransferObjects\PaymentData;
use App\Services\SuitPay\Responses\BilletResponse;
use Illuminate\Support\Facades\Storage;

class SuitpayBilletService extends BaseEndpoint
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

        $transactionBillet = $this->generateTransactionBillet($paymentData->toArray());

        if (!isset($transactionBillet['idTransaction'])) {
            return [
                'success' => false,
                'message' => 'Não foi possível gerar o boleto, tente novamente mais tarde.',
            ];
        }

        $filePdfBillet = $this->getFilePdfBillet($transactionBillet['idTransaction']);

        if (!isset($filePdfBillet['id'])) {
            return [
                'success' => false,
                'message' => 'Não foi possível gerar o boleto, tente novamente mais tarde.',
            ];
        }

        $pathFilePdfBillet = 'suitpay/boleto/' . $filePdfBillet['id'] . '.pdf';

        Storage::disk('s3')->put($pathFilePdfBillet, base64_decode($filePdfBillet['base64']));

        $url = Storage::disk('s3')->url($pathFilePdfBillet);

        $filePdfBillet['url'] = $url;

        $transactionBillet['data'] = $filePdfBillet;

        return (new BilletResponse($transactionBillet))->get();
    }

    public function generateTransactionBillet(array $paymentData): array
    {
        return $this->service->init()
            ->post('/api/v1/gateway/request-boleto', $paymentData)
            ->json();
    }

    public function getFilePdfBillet(string $idTransaction): array
    {
        return $this->service->init()
            ->get('/api/v1/gateway/boleto/pdf?id=' . $idTransaction)
            ->json();
    }

}
