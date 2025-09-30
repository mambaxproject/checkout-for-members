<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDiscountCouponRequest;
use App\Http\Requests\StoreCouponDiscountRequest;
use App\Http\Requests\UpdateDiscountCouponRequest;
use App\Models\CouponDiscount;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DiscountCouponController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('discount_coupon_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $discountCoupons = CouponDiscount::all();

        return view('admin.discountCoupons.index', compact('discountCoupons'));
    }

    public function create()
    {
        abort_if(Gate::denies('discount_coupon_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.discountCoupons.create');
    }

    public function store(StoreCouponDiscountRequest $request)
    {
        $discountCoupon = CouponDiscount::create($request->all());

        return redirect()->route('admin.discount-coupons.index');
    }

    public function edit(CouponDiscount $discountCoupon)
    {
        abort_if(Gate::denies('discount_coupon_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.discountCoupons.edit', compact('discountCoupon'));
    }

    public function update(UpdateDiscountCouponRequest $request, CouponDiscount $discountCoupon)
    {
        $discountCoupon->update($request->all());

        return redirect()->route('admin.discount-coupons.index');
    }

    public function show(CouponDiscount $discountCoupon)
    {
        abort_if(Gate::denies('discount_coupon_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $discountCoupon->load('discountCouponDiscountOrders');

        return view('admin.discountCoupons.show', compact('discountCoupon'));
    }

    public function destroy(CouponDiscount $discountCoupon)
    {
        abort_if(Gate::denies('discount_coupon_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $discountCoupon->delete();

        return back();
    }

    public function massDestroy(MassDestroyDiscountCouponRequest $request)
    {
        $discountCoupons = CouponDiscount::find(request('ids'));

        foreach ($discountCoupons as $discountCoupon) {
            $discountCoupon->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
