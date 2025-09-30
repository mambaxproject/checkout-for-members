<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\{CardInstallmentsRequest, PaymentRequest};
use App\Services\Checkout\CheckoutService;
use App\Services\SuitPay\Endpoints\SuitpayCreditCardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Number;

class CheckoutApiController extends Controller
{
    public function pay(PaymentRequest $request): JsonResponse
    {
        return response()->json((new CheckoutService)->pay($request));
    }

    public function cardInstallments(CardInstallmentsRequest $request): JsonResponse
    {
        $request_value = $request->validated('value');

        $response = (new SuitpayCreditCardService($request->shop->client_id_banking, $request->shop->client_secret_banking))
            ->cardFees($request_value);

        for ($i = 1; $i <= count($response['installments']); $i++) {
            $installment = Number::currency($response['installments']['installment' . $i . 'x'], 'BRL', 'pt-br');

            $value_numeric = $response['values']['value' . $i . 'x'];

            if ($i != 1 and $response['installments']['installment' . $i . 'x'] < 5) {
                continue;
            }

            $response['formatted'][] = ['text' => $i . ' x ' . $installment, 'total' => $value_numeric];
        }

        return response()->json($response);
    }
}
