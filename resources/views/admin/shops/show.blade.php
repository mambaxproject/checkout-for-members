@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.shop.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.shops.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>

                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.id') }}
                        </th>
                        <td>
                            {{ $shop->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.image') }}
                        </th>
                        <td>
                            @if($shop->getMedia('image')->count() > 0)
                                <a href="{{ $shop->getMedia('image')->getUrl() }}" target="_blank"
                                   style="display: inline-block">
                                    <img src="{{ $shop->getMedia('image')->getUrl('thumb') }}" loading="lazy" />
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.name') }}
                        </th>
                        <td>
                            {{ $shop->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.description') }}
                        </th>
                        <td>
                            {{ $shop->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shop.fields.status') }}
                        </th>
                        <td>
                            {{ $shop->statusFormatted }}
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.shops.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
