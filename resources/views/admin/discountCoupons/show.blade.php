@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.discountCoupon.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.discount-coupons.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.id') }}
                        </th>
                        <td>
                            {{ $discountCoupon->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.code') }}
                        </th>
                        <td>
                            {{ $discountCoupon->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.description') }}
                        </th>
                        <td>
                            {{ $discountCoupon->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.amount') }}
                        </th>
                        <td>
                            {{ $discountCoupon->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\CouponDiscount::TYPE_RADIO[$discountCoupon->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.quantity') }}
                        </th>
                        <td>
                            {{ $discountCoupon->quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.start_at') }}
                        </th>
                        <td>
                            {{ $discountCoupon->start_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.end_at') }}
                        </th>
                        <td>
                            {{ $discountCoupon->end_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.discountCoupon.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\CouponDiscount::STATUS_RADIO[$discountCoupon->status] ?? '' }}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.discount-coupons.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.relatedData') }}
        </div>
        <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
            <li class="nav-item">
                <a class="nav-link" href="#discount_coupon_discount_orders" role="tab" data-toggle="tab">
                    {{ trans('cruds.discountOrder.title') }}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" role="tabpanel" id="discount_coupon_discount_orders">
                @includeIf('admin.discountCoupons.relationships.discountCouponDiscountOrders', ['discountOrders' => $discountCoupon->discountCouponDiscountOrders])
            </div>
        </div>
    </div>

@endsection