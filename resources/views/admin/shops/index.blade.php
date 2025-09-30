@extends('layouts.admin')

@section('content')
    @can('shop_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.shops.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.shop.title_singular') }}
                </a>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.shop.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.shop.fields.image') }}</th>
                            <th>{{ trans('cruds.shop.fields.name') }}</th>
                            <th>{{ trans('cruds.shop.fields.description') }}</th>
                            <th>{{ trans('cruds.shop.fields.status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($shops as $key => $shop)
                        <tr data-entry-id="{{ $shop->id }}">
                            <td>
                                @if($shop->getMedia('image')->count() > 0)
                                    <a href="{{ $shop->getMedia('image')->getUrl() }}" target="_blank"
                                       style="display: inline-block">
                                        <img src="{{ $shop->getMedia('image')->getUrl('thumb') }}" loading="lazy" />
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $shop->name ?? '' }}
                            </td>
                            <td>
                                {{ $shop->description ?? '' }}
                            </td>
                            <td>
                                {{ $shop->statusFormatted }}
                            </td>
                            <td>
                                @can('shop_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.shops.show', $shop->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('shop_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.shops.edit', $shop->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('shop_delete')
                                    <form action="{{ route('admin.shops.destroy', $shop->id) }}"
                                          method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
