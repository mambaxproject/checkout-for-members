<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Enums\StatusAbandonedCartEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUpdateAbandonedCartRequest;
use App\Models\{AbandonedCart, Affiliate};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AbandonedCartApiController extends Controller
{
    public function store(StoreUpdateAbandonedCartRequest $request): JsonResponse
    {
        $cleanUrl = $this->removeParamsUrl($request);

        $abandonedCart = AbandonedCart::query()
            ->where('product_id', $request->product_id)
            ->where('link_checkout', $cleanUrl)
            ->where('status', StatusAbandonedCartEnum::PENDING->value)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->email);
            })->first();

        $data = $request->validated();

        $data['link_checkout'] = $cleanUrl;

        if ($request->has('affiliate_code')) {
            $affiliate            = Affiliate::whereCode($request->affiliate_code)->first();
            $data['affiliate_id'] = $affiliate?->id;
        }

        $abandonedCart = $abandonedCart ?
            $abandonedCart->update($data) : $this->createAbandonedCart($data);

        return response()->json([
            'success' => true,
            'data'    => $abandonedCart,
        ], Response::HTTP_OK);
    }

    private function removeParamsUrl(StoreUpdateAbandonedCartRequest $request): string
    {
        return strtok($request->link_checkout, '?');
    }

    private function createAbandonedCart(array $data): AbandonedCart
    {
        $data['client_abandoned_cart_uuid'] = Str::uuid()->toString();

        return AbandonedCart::create($data);
    }
}
