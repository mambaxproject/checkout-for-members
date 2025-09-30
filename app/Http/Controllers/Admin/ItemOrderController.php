<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyItemOrderRequest;
use App\Http\Requests\StoreItemOrderRequest;
use App\Http\Requests\UpdateItemOrderRequest;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemOrderController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('item_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $itemOrders = ItemOrder::with(['order', 'product'])->get();

        return view('admin.itemOrders.index', compact('itemOrders'));
    }

    public function create()
    {
        abort_if(Gate::denies('item_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('amount', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.itemOrders.create', compact('orders', 'products'));
    }

    public function store(StoreItemOrderRequest $request)
    {
        $itemOrder = ItemOrder::create($request->all());

        return redirect()->route('admin.item-orders.index');
    }

    public function edit(ItemOrder $itemOrder)
    {
        abort_if(Gate::denies('item_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('amount', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $itemOrder->load('order', 'product');

        return view('admin.itemOrders.edit', compact('itemOrder', 'orders', 'products'));
    }

    public function update(UpdateItemOrderRequest $request, ItemOrder $itemOrder)
    {
        $itemOrder->update($request->all());

        return redirect()->route('admin.item-orders.index');
    }

    public function show(ItemOrder $itemOrder)
    {
        abort_if(Gate::denies('item_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $itemOrder->load('order', 'product');

        return view('admin.itemOrders.show', compact('itemOrder'));
    }

    public function destroy(ItemOrder $itemOrder)
    {
        abort_if(Gate::denies('item_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $itemOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyItemOrderRequest $request)
    {
        $itemOrders = ItemOrder::find(request('ids'));

        foreach ($itemOrders as $itemOrder) {
            $itemOrder->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
