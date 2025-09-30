@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Salvar {{ trans('cruds.shop.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ isset($shop) ? route("admin.shops.update", [$shop->id]) :  route("admin.shops.store") }}"
                  enctype="multipart/form-data"
            >
                @method(isset($shop) ? 'PUT' : 'POST')
                @csrf

                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.shop.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', $shop->name ?? "") }}" required>
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.shop.fields.name_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="description">{{ trans('cruds.shop.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description"
                              id="description">{{ old('description', $shop->description ?? "") }}</textarea>
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.shop.fields.description_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="image">{{ trans('cruds.shop.fields.image') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}"
                         id="image-dropzone">
                    </div>
                    @if($errors->has('image'))
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.shop.fields.image_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required">{{ trans('cruds.shop.fields.status') }}</label>
                    @foreach(\App\Enums\StatusEnum::getDescriptions() as $key => $item)
                        <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <input class="form-check-input"
                                   type="radio"
                                   id="status_{{ $item['value'] }}"
                                   name="status"
                                   value="{{ $item['value'] }}"
                                   {{ old('status', $shop->status ?? \App\Enums\StatusEnum::ACTIVE->name) === $item['value'] ? 'checked' : '' }}
                                   required
                            />
                            <label class="form-check-label" for="status_{{ $item['value'] }}">{{ $item['name'] }}</label>
                        </div>
                    @endforeach
                    @if($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.shop.fields.status_helper') }}</span>
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

@section('scripts')
    <script>
        Dropzone.options.imageDropzone = {
            url: '{{ route('admin.shops.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                width: 4096,
                height: 4096
            },
            success: function (file, response) {
                $('form').find('input[name="image"]').remove()
                $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="image"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($shop) && $shop->image)
                var file = {!! json_encode($shop->image) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function (file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
