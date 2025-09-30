@extends('layouts.admin')

@section('content')
    @can('order_create')
{{--        <div style="margin-bottom: 10px;" class="row">--}}
{{--            <div class="col-lg-12">--}}
{{--                <a class="btn btn-success" href="{{ route('admin.orders.create') }}">--}}
{{--                    {{ trans('global.add') }} {{ trans('cruds.order.title_singular') }}--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
    @endcan

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.order.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Order">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.order.fields.user') }}</th>
                            <th>{{ trans('cruds.order.fields.item_order') }}</th>
                            <th>{{ trans('cruds.order.fields.amount') }}</th>
                            <th>{{ trans('cruds.orderPayment.fields.payment_method') }}</th>
                            <th>{{ trans('cruds.orderPayment.fields.payment_status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                            <tr data-entry-id="{{ $order->client_orders_uuid }}">
                                <td>
                                    <div class="d-flex flex-column" style="gap: 4px">
                                        <span><b>Nome:</b> {{ $order->user->name ?? '' }}</span>
                                        <span><b>E-mail:</b> {{ $order->user->email ?? '' }}</span>
                                        <span><b>Telefone:</b> {{ $order->user->phone_number ?? ''}}</span>
                                        <span><b>CPF:</b> {{ $order->user->document_number ?? ''}}</span>
                                    </div>
                                </td>
                                <td>
                                    @foreach($order->items as $key => $item)
                                        <span class="badge badge-secondary">
                                            {{ $item->product->name }} <br>
                                            {{ $item->quantity }} x {{ $item->typeProduct->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>{{ \Illuminate\Support\Number::currency($order->amount, 'BRL', ' ') }}</td>
                                <td>{{ $order->paymentMethod }}</td>
                                <td>{!! $order->paymentStatus !!}</td>
                                <td>
                                    <div class="d-flex flex-column align-items-center" style="gap: 4px">
                                        @can('order_show')
                                            <a class="btn btn-xs btn-primary" href="{{ route('admin.orders.show', $order->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan

                                        @can('order_delete')
                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
