@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.categoryProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.category-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.categoryProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $categoryProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.categoryProduct.fields.name') }}
                        </th>
                        <td>
                            {{ $categoryProduct->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.categoryProduct.fields.description') }}
                        </th>
                        <td>
                            {{ $categoryProduct->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.categoryProduct.fields.photo') }}
                        </th>
                        <td>
                            @if($categoryProduct->photo)
                                <a href="{{ $categoryProduct->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $categoryProduct->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.categoryProduct.fields.cover_photo') }}
                        </th>
                        <td>
                            @if($categoryProduct->cover_photo)
                                <a href="{{ $categoryProduct->cover_photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $categoryProduct->cover_photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.categoryProduct.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\CategoryProduct::STATUS_RADIO[$categoryProduct->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.category-products.index') }}">
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
            <a class="nav-link" href="#category_products" role="tab" data-toggle="tab">
                {{ trans('cruds.product.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="category_products">
            @includeIf('admin.categoryProducts.relationships.categoryProducts', ['products' => $categoryProduct->categoryProducts])
        </div>
    </div>
</div>

@endsection