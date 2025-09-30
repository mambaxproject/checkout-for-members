@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.state.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.states.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.state.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="code">{{ trans('cruds.state.fields.code') }}</label>
                <input class="noScrollInput form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="number" name="code" id="code" value="{{ old('code', '') }}" step="1">
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="uf">{{ trans('cruds.state.fields.uf') }}</label>
                <input class="form-control {{ $errors->has('uf') ? 'is-invalid' : '' }}" type="text" name="uf" id="uf" value="{{ old('uf', '') }}" required>
                @if($errors->has('uf'))
                    <span class="text-danger">{{ $errors->first('uf') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.uf_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.state.fields.status') }}</label>
                @foreach(App\Models\State::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', 'active') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.status_helper') }}</span>
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