@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.itemOrder.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.item-orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.itemOrder.fields.id') }}
                        </th>
                        <td>
                            {{ $itemOrder->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.itemOrder.fields.order') }}
                        </th>
                        <td>
                            {{ $itemOrder->order->amount ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.itemOrder.fields.product') }}
                        </th>
                        <td>
                            {{ $itemOrder->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.itemOrder.fields.amount') }}
                        </th>
                        <td>
                            {{ $itemOrder->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.itemOrder.fields.quantity') }}
                        </th>
                        <td>
                            {{ $itemOrder->quantity }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.item-orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection