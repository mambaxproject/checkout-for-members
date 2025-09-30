@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.order.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>{{ trans('cruds.order.fields.id') }}</th>
                            <td>{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.order.fields.user') }}</th>
                            <td>{{ $order->user->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.order.fields.amount') }}</th>
                            <td>{{ $order->amount }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.order.fields.item_order') }}</th>
                            <td>
                                @foreach($order->items as $key => $item)
                                    <span class="badge badge-secondary">
                                        {{ $item->product->name }} <br>
                                        {{ $item->quantity }} x {{ $item->typeProduct->name }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.order.fields.amount') }}</th>
                            <td>{{ \Illuminate\Support\Number::currency($order->amount, 'BRL', ' ') }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.orderPayment.fields.payment_method') }}</th>
                            <td>{{ $order->paymentMethod }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.orderPayment.fields.payment_status') }}</th>
                            <td>{!! $order->paymentStatus !!}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
