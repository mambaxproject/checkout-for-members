@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.discountCoupon.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.discount-coupons.update", [$discountCoupon->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="code">{{ trans('cruds.discountCoupon.fields.code') }}</label>
                    <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code"
                           id="code" value="{{ old('code', $discountCoupon->code) }}" required>
                    @if($errors->has('code'))
                        <span class="text-danger">{{ $errors->first('code') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.code_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="description">{{ trans('cruds.discountCoupon.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description"
                              id="description">{{ old('description', $discountCoupon->description) }}</textarea>
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.description_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="amount">{{ trans('cruds.discountCoupon.fields.amount') }}</label>
                    <input class="noScrollInput form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number"
                           name="amount" id="amount" value="{{ old('amount', $discountCoupon->amount) }}" step="0.01"
                           required>
                    @if($errors->has('amount'))
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.amount_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.discountCoupon.fields.type') }}</label>
                    @foreach(App\Models\CouponDiscount::TYPE_RADIO as $key => $label)
                        <div class="form-check {{ $errors->has('type') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio" id="type_{{ $key }}" name="type"
                                   value="{{ $key }}"
                                   {{ old('type', $discountCoupon->type) === (string) $key ? 'checked' : '' }} required>
                            <label class="form-check-label" for="type_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @if($errors->has('type'))
                        <span class="text-danger">{{ $errors->first('type') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.type_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="quantity">{{ trans('cruds.discountCoupon.fields.quantity') }}</label>
                    <input class="noScrollInput form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number"
                           name="quantity" id="quantity" value="{{ old('quantity', $discountCoupon->quantity) }}"
                           step="1">
                    @if($errors->has('quantity'))
                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.quantity_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="start_at">{{ trans('cruds.discountCoupon.fields.start_at') }}</label>
                    <input class="form-control datetime {{ $errors->has('start_at') ? 'is-invalid' : '' }}" type="text"
                           name="start_at" id="start_at" value="{{ old('start_at', $discountCoupon->start_at) }}"
                           required>
                    @if($errors->has('start_at'))
                        <span class="text-danger">{{ $errors->first('start_at') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.start_at_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="end_at">{{ trans('cruds.discountCoupon.fields.end_at') }}</label>
                    <input class="form-control datetime {{ $errors->has('end_at') ? 'is-invalid' : '' }}" type="text"
                           name="end_at" id="end_at" value="{{ old('end_at', $discountCoupon->end_at) }}" required>
                    @if($errors->has('end_at'))
                        <span class="text-danger">{{ $errors->first('end_at') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.end_at_helper') }}</span>
                </div>
                <div class="form-group">
                    <label>{{ trans('cruds.discountCoupon.fields.status') }}</label>
                    @foreach(App\Models\CouponDiscount::STATUS_RADIO as $key => $label)
                        <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status"
                                   value="{{ $key }}" {{ old('status', $discountCoupon->status) === (string) $key ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @if($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.discountCoupon.fields.status_helper') }}</span>
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