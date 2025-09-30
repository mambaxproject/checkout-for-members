<?php

namespace App\Http\Controllers\Public\Subscription;

use App\Enums\PaymentMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Subscription\{UpdateCreditCardRequest, UpdateOfferRequest};
use App\Models\{Order, Product};
use App\Services\SuitPay\Endpoints\SuitpaySubscriptionService;
use Illuminate\Http\{RedirectResponse, Response};
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UpdateController extends Controller
{
    public function show(string $order_hash): View
    {
        abort_if(
            now()->timestamp > (int) request('expires', 0),
            Response::HTTP_FORBIDDEN,
            'O link expirou ou é inválido. Solicite um novo e-mail ao lojista.'
        );

        $order = Order::findOrFail(Crypt::decryptString($order_hash));

        return view('public.subscription.show', compact('order'));
    }

    public function updateCard(Order $order, UpdateCreditCardRequest $request): RedirectResponse
    {
        $subscriptionService = new SuitpaySubscriptionService(
            $order->shop->client_id_banking,
            $order->shop->client_secret_banking
        );

        $resultUpdateCreditCard = $subscriptionService->updateCreditCard(
            array_merge(
                [
                    'callbackUrl'   => route('api.public.webhooks.suitpay.updateOrderByTransation'),
                    'requestNumber' => $order->id,
                    'recurrencyId'  => $order->payment->recurrencyId,
                ],
                $request->validated()
            )
        );

        if (! empty($resultUpdateCreditCard['error']) || $resultUpdateCreditCard['response'] != 'OK') {
            return back()->with('error', $resultUpdateCreditCard['message'] ?? 'Erro ao atualizar o cartão de crédito.');
        }

        $order->comment('
            O cliente atualizou o cartão de crédito da assinatura 
            para um cartão terminando em ' . Str::substr($request->input('card.number'), -4)
        );

        return back()->with('success', 'Cartão de crédito atualizado com sucesso.');
    }

    public function editOffer(string $order_hash, Product $product): View
    {
        abort_if(
            now()->timestamp > (int) request('expires', 0),
            Response::HTTP_FORBIDDEN,
            'O link expirou ou é inválido. Solicite um novo e-mail ao lojista.'
        );

        $order = Order::findOrFail(Crypt::decryptString($order_hash));

        return view('public.subscription.editOffer', compact('order', 'product'));
    }

    public function updateOffer(Order $order, UpdateOfferRequest $request): RedirectResponse
    {
        $subscriptionService = new SuitpaySubscriptionService(
            $order->shop->client_id_banking,
            $order->shop->client_secret_banking
        );

        $product = Product::findOrFail($request->offer_id);

        $resultUpdateOffer = $subscriptionService->updateSubscription(
            array_merge(
                [
                    'callbackUrl'         => route('api.public.webhooks.suitpay.updateOrderByTransation'),
                    'requestNumber'       => $order->id,
                    'recurrencyId'        => $order->payment->recurrencyId,
                    'frequency'           => $product->cyclePayment,
                    'status'              => 'active',
                    'automaticRenovation' => true,
                    'numberCharges'       => $product->numberPaymentsRecurringPayment ?? 12,
                    'firstChargeValue'    => $product->hasFirstPayment ? $product->priceFirstPayment : $product->price,
                    'chargeValue'         => $product->price,
                    'products'            => [
                        [
                            'productName' => $product->name,
                            'idCheckout'  => $product->id,
                            'quantity'    => 1,
                            'value'       => $product->price,
                        ],
                    ],
                ],
                $request->validated()
            )
        );

        if (! empty($resultUpdateOffer['error']) || $resultUpdateOffer['response'] != 'OK') {
            return back()->with('error', $resultUpdateOffer['acquirerMessage'] ?? 'Erro ao atualizar a oferta.');
        }

        try {
            \DB::transaction(
                function () use ($order, $product) {
                    $order->items()
                        ->where('product_id', $product->id)
                        ->update([
                            'amount'   => $product->price,
                            'quantity' => 1,
                        ]);

                    $order->payments()
                        ->where('payment_method', PaymentMethodEnum::CREDIT_CARD->name)
                        ->update([
                            'amount'   => $product->price,
                            'due_date' => date('Y-m-d'),
                        ]);
                }
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Erro ao atualizar a assinatura: ' . $e->getMessage());
        }

        $order->comment("
            O cliente alterou o plano de assinatura 
            de {$order->item->product->name} - {$order->item->product->brazilianPrice} 
            para {$product->name} - {$product->brazilianPrice}"
        );

        return back()->with('success', 'Plano de assinatura atualizado com sucesso.');
    }
}
