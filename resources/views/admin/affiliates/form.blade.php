@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.affiliate.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ isset($affiliate) ? route("admin.affiliates.update", [$affiliate->id]) :  route("admin.affiliates.store") }}"
                  enctype="multipart/form-data"
            >
                @method(isset($affiliate) ? 'PUT' : 'POST')
                @csrf

                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.affiliate.fields.product') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('products') ? 'is-invalid' : '' }}" name="products[]" id="products" multiple required>
                        @foreach($products as $id => $product)
                            <option value="{{ $id }}" {{ (in_array($id, old('products', [])) || isset($affiliate) && $affiliate->products->contains($id)) ? 'selected' : '' }}>
                                {{ $product }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('products'))
                        <span class="text-danger">{{ $errors->first('products') }}</span>
                    @endif

                    <span class="help-block">{{ trans('cruds.affiliate.fields.product_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.affiliate.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $affiliate->user->name ?? "") }}" required @disabled($affiliate->user->name ?? "")>
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.name_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="email">{{ trans('cruds.affiliate.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $affiliate->user->email ?? "") }}" required @disabled($affiliate->user->email ?? "")>
                    @if($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.email_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="document_number">{{ trans('cruds.affiliate.fields.document_number') }}</label>
                    <input class="form-control {{ $errors->has('document_number') ? 'is-invalid' : '' }}" type="text" name="document_number" id="document_number" value="{{ old('document_number', $affiliate->user->document_number ?? "") }}" required @disabled($affiliate->user->document_number ?? "")>
                    @if($errors->has('document_number'))
                        <span class="text-danger">{{ $errors->first('document_number') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.document_number_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="percentage">{{ trans('cruds.affiliate.fields.percentage') }}</label>
                    <input class="noScrollInput form-control {{ $errors->has('percentage') ? 'is-invalid' : '' }}" type="number" name="percentage" id="percentage" value="{{ old('percentage', $affiliate->percentage ?? "") }}" step="0.01" required>
                    @if($errors->has('percentage'))
                        <span class="text-danger">{{ $errors->first('percentage') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.percentage_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="description">{{ trans('cruds.affiliate.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $affiliate->description ?? "") }}</textarea>
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.description_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="start_at">{{ trans('cruds.affiliate.fields.start_at') }}</label>
                    <input class="form-control {{ $errors->has('start_at') ? 'is-invalid' : '' }}" type="datetime-local" name="start_at" id="start_at" value="{{ old('start_at', $affiliate->start_at ?? "") }}">
                    @if($errors->has('start_at'))
                        <span class="text-danger">{{ $errors->first('start_at') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.start_at_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="end_at">{{ trans('cruds.affiliate.fields.end_at') }}</label>
                    <input class="form-control {{ $errors->has('end_at') ? 'is-invalid' : '' }}" type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at', $affiliate->end_at ?? "") }}" required>
                    @if($errors->has('end_at'))
                        <span class="text-danger">{{ $errors->first('end_at') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.end_at_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required">{{ trans('cruds.affiliate.fields.status') }}</label>
                    @foreach(App\Models\Affiliate::STATUS_RADIO as $key => $label)
                        <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $affiliate->status ?? "") === (string) $key ? 'checked' : '' }} required>
                            <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @if($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.affiliate.fields.status_helper') }}</span>
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
