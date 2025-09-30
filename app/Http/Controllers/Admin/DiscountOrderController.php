<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDiscountOrderRequest;
use App\Http\Requests\StoreDiscountOrderRequest;
use App\Http\Requests\UpdateDiscountOrderRequest;
use App\Models\CouponDiscount;
use App\Models\DiscountOrder;
use App\Models\Order;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DiscountOrderController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('discount_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $discountOrders = DiscountOrder::with(['order', 'discount_coupon'])->get();

        return view('admin.discountOrders.index', compact('discountOrders'));
    }

    public function create()
    {
        abort_if(Gate::denies('discount_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('amount', 'id')->prepend(trans('global.pleaseSelect'), '');

        $discount_coupons = CouponDiscount::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.discountOrders.create', compact('discount_coupons', 'orders'));
    }

    public function store(StoreDiscountOrderRequest $request)
    {
        $discountOrder = DiscountOrder::create($request->all());

        return redirect()->route('admin.discount-orders.index');
    }

    public function edit(DiscountOrder $discountOrder)
    {
        abort_if(Gate::denies('discount_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('amount', 'id')->prepend(trans('global.pleaseSelect'), '');

        $discount_coupons = CouponDiscount::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $discountOrder->load('order', 'discount_coupon');

        return view('admin.discountOrders.edit', compact('discountOrder', 'discount_coupons', 'orders'));
    }

    public function update(UpdateDiscountOrderRequest $request, DiscountOrder $discountOrder)
    {
        $discountOrder->update($request->all());

        return redirect()->route('admin.discount-orders.index');
    }

    public function show(DiscountOrder $discountOrder)
    {
        abort_if(Gate::denies('discount_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $discountOrder->load('order', 'discount_coupon');

        return view('admin.discountOrders.show', compact('discountOrder'));
    }

    public function destroy(DiscountOrder $discountOrder)
    {
        abort_if(Gate::denies('discount_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $discountOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyDiscountOrderRequest $request)
    {
        $discountOrders = DiscountOrder::find(request('ids'));

        foreach ($discountOrders as $discountOrder) {
            $discountOrder->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
