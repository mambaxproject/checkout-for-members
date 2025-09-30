@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.discountOrder.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.discount-orders.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="order_id">{{ trans('cruds.discountOrder.fields.order') }}</label>
                <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id" required>
                    @foreach($orders as $id => $entry)
                        <option value="{{ $id }}" {{ old('order_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order'))
                    <span class="text-danger">{{ $errors->first('order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.discountOrder.fields.order_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="discount_coupon_id">{{ trans('cruds.discountOrder.fields.discount_coupon') }}</label>
                <select class="form-control select2 {{ $errors->has('discount_coupon') ? 'is-invalid' : '' }}" name="discount_coupon_id" id="discount_coupon_id" required>
                    @foreach($discount_coupons as $id => $entry)
                        <option value="{{ $id }}" {{ old('discount_coupon_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('discount_coupon'))
                    <span class="text-danger">{{ $errors->first('discount_coupon') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.discountOrder.fields.discount_coupon_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection