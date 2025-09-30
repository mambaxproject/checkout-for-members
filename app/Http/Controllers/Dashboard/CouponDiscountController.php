<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CouponDiscount\{StoreCouponDiscountRequest, UpdateCouponDiscountRequest};
use App\Models\{CouponDiscount, Product};
use Illuminate\Http\{JsonResponse, RedirectResponse};

class CouponDiscountController extends Controller
{
    public function store(StoreCouponDiscountRequest $request): RedirectResponse|JsonResponse
    {
        $product = Product::find($request->product_id);

        $coupon = $product->couponsDiscount()->create($request->all());

        $offersIds = $request->offers ?? [];

        $coupon->offers()->syncWithoutDetaching($offersIds);

        $offersToDetach = $coupon->offers()
            ->whereNotNull('parent_id')
            ->whereNotIn('products.id', $offersIds)
            ->pluck('products.id')
            ->toArray();

        $coupon->offers()->detach($offersToDetach);

        if ($request->filled('product.attributes')) {
            $product->attributes->set($request->input('product.attributes'));
            $product->save();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Cupom de desconto criado com sucesso.',
            ]);
        }

        return back()->with('success', 'Cupom de desconto criado com sucesso.');
    }

    public function update(UpdateCouponDiscountRequest $request, CouponDiscount $couponDiscount): RedirectResponse|JsonResponse
    {
        $couponDiscount->update($request->all());

        $offersIds = $request->offers ?? [];

        $couponDiscount->offers()->syncWithoutDetaching($offersIds);

        $offersToDetach = $couponDiscount->offers()
            ->whereNotNull('parent_id')
            ->whereNotIn('products.id', $offersIds)
            ->pluck('products.id')
            ->toArray();

        $couponDiscount->offers()->detach($offersToDetach);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Cupom de desconto atualizado com sucesso.',
            ]);
        }

        return back()->with('success', 'Cupom de desconto atualizado com sucesso.');
    }

    public function destroy(CouponDiscount $couponDiscount): RedirectResponse|JsonResponse
    {
        $couponDiscount->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Cupom de desconto excluído com sucesso.',
            ]);
        }

        return back()->with('success', 'Cupom de desconto excluído com sucesso.');
    }

}
