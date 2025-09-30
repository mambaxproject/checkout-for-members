<?php

namespace App\Http\Controllers\Api\V1\Suitpay;

use App\Actions\SaveDataCommissioningOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WebhookTransationSuitpayRequest;
use App\Models\{Order, Shop};
use App\Services\AbandonedCartService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WebhookApiController extends Controller
{
    public function updateOrderByTransation(WebhookTransationSuitpayRequest $request): JsonResponse
    {
        try {
            $idTransaction = $request->validated('idTransaction');
            $recurrencyId  = $request->validated('recurrencyId');

            $order = Order::query()
                ->whereHas('payments', fn ($query) => $recurrencyId
                    ? $query->byRecurrencyId($recurrencyId)
                    : $query->where('external_identification', $idTransaction))
                ->firstOrFail();

            if ($request->filled('splitGateway')) {
                $order->attributes->set($request->only('splitGateway'));
                $order->save();

                $splitGateways = $request->get('splitGateway');
                $usernames     = collect($splitGateways)->pluck('username')->unique()->filter()->all();
                $shopIds       = Shop::whereIn('username_banking', $usernames)->pluck('owner_id', 'username_banking');

                foreach ($splitGateways as $split) {

                    $commissionedId = $shopIds[$split['username']] ?? null;

                    if (! $commissionedId) {
                        continue;
                    }

                    (new SaveDataCommissioningOrder($order, $commissionedId, $split))->handle();
                }
            }

            $order->payments()->update([
                'payment_status' => $request->validated('statusTransaction'),
            ]);

            if ($order->isPaid()) {
                $dataOrderToUpdate = [];

                if ($request->filled('netAmount')) {
                    $dataOrderToUpdate['net_amount'] = $request->validated('netAmount');
                }

                $order->update($dataOrderToUpdate);

                $order->payments()->update([
                    'paid_at'                  => now(),
                    'payment_gateway_response' => $request->all(),
                ]);

                (new AbandonedCartService)->checkCanConvertCart($order);

                event(new \App\Events\OrderApproved($order));
            } else if ($order->isFailed()) {
                event(new \App\Events\OrderFailed($order));
            }

            $order->webhooks()->create([
                'id_transaction' => $idTransaction,
                'payment_status' => $request->validated('statusTransaction'),
                'payload'        => $request->all(),
            ]);

            $response = [
                'success' => true,
                'data'    => [
                    'order' => $order,
                ],
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
