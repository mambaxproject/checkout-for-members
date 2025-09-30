<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\{CardInstallmentsRequest, PaymentRequest, PaymentUpSellRequest};
use App\Models\{Order, OrderPayment, UpSell};
use App\Services\Checkout\CheckoutService;
use App\Services\SuitPay\Endpoints\SuitpayCreditCardService;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Number;

class CheckoutApiController extends Controller
{
    public function pay(PaymentRequest $request): JsonResponse
    {
        return response()
            ->json((new CheckoutService)->pay($request));
    }

    public function payUpSell(Order $order, UpSell $upSell, PaymentUpSellRequest $request): RedirectResponse
    {
        $data = $request->all();

        $paymentRequest = PaymentRequest::createFrom($request);
        $paymentRequest->replace($data);

        $responsePayment = (new CheckoutService)->pay($paymentRequest);

        $thanksPage = $responsePayment->resolve()['thanks_page'];

        return redirect($thanksPage);
    }

    public function checkPayment(string $order_hash): JsonResponse
    {
        $orderPayment = OrderPayment::where('order_id', Crypt::decryptString($order_hash))
            ->firstOrFail(['id', 'payment_method', 'payment_status']);

        return response()->json(['is_paid' => $orderPayment->isPaid()]);
    }

    public function cardInstallments(CardInstallmentsRequest $request): JsonResponse
    {
        $request_value = $request->validated('value');

        $response = (new SuitpayCreditCardService(
            $request->shop->client_id_banking, $request->shop->client_secret_banking))
            ->cardFees($request_value);

        for ($i = 1; $i <= count($response['installments']); $i++) {
            $installment = Number::currency($response['installments']['installment' . $i . 'x'], 'BRL', 'pt-br');

            $value_numeric = $response['values']['value' . $i . 'x'];
            $value         = Number::currency($value_numeric, 'BRL', 'pt-br');

            if ($i != 1 and $response['installments']['installment' . $i . 'x'] < 5) {
                continue;
            }

            $response['formatted'][] = ['text' => $i . ' x ' . $installment, 'total' => $value_numeric];
        }

        return response()->json($response);
    }
}
