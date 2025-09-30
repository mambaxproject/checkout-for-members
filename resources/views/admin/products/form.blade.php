@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ isset($product) ? route("admin.products.update", [$product->id]) :  route("admin.products.store") }}"
                  enctype="multipart/form-data"
            >
                @method(isset($product) ? 'PUT' : 'POST')
                @csrf

                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $product->name ?? "") }}" required>
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="description">{{ trans('cruds.product.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', strip_tags($product->description ?? "")) }}</textarea>
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="category_id">{{ trans('cruds.product.fields.category') }}</label>
                    <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                        @foreach($categories as $id => $entry)
                            <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $product->category->id ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('category'))
                        <span class="text-danger">{{ $errors->first('category') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.category_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="photo">{{ trans('cruds.product.fields.photo') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="photo-dropzone">
                    </div>
                    @if($errors->has('photo'))
                        <span class="text-danger">{{ $errors->first('photo') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.photo_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="infos">{{ trans('cruds.product.fields.infos') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('infos') ? 'is-invalid' : '' }}" name="infos" id="infos">{!! old('infos', $product->infos ?? "") !!}</textarea>
                    @if($errors->has('infos'))
                        <span class="text-danger">{{ $errors->first('infos') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.infos_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="shop_id">{{ trans('cruds.product.fields.shop') }}</label>
                    <select class="form-control select2 {{ $errors->has('shop_id') ? 'is-invalid' : '' }}" name="shop_id" id="shop_id" required>
                        @foreach($shops as $id => $entry)
                            <option value="{{ $id }}" {{ (old('shop_id') ? old('shop_id') : $product->shop_id ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('shop_id'))
                        <span class="text-danger">{{ $errors->first('shop_id') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.shop_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required">{{ trans('cruds.product.fields.situation') }}</label>
                    @foreach(\App\Enums\SituationProductEnum::getDescriptions() as $item)
                        <div class="form-check {{ $errors->has('situation') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio"
                                   id="situation_{{ $item['value'] }}"
                                   name="situation"
                                   value="{{ $item['value'] }}"
                                   {{ old('situation', $product->situation ?? "") === $item['value'] ? 'checked' : '' }}
                                   required
                            />
                            <label class="form-check-label" for="situation_{{ $item['value'] }}">{{ $item['description'] }}</label>
                        </div>
                    @endforeach
                    @if($errors->has('situation'))
                        <span class="text-danger">{{ $errors->first('situation') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.situation_helper') }}</span>
                </div>

                <div class="form-group">
                    <label>{{ trans('cruds.product.fields.status') }}</label>
                    @foreach(App\Models\Product::STATUS_RADIO as $key => $label)
                        <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $product->status ?? "") === (string) $key ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @if($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.status_helper') }}</span>
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
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
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
              $('form').find('input[name="photo"]').remove()
              $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
            },
            removedfile: function (file) {
              file.previewElement.remove()
              if (file.status !== 'error') {
                $('form').find('input[name="photo"]').remove()
                this.options.maxFiles = this.options.maxFiles + 1
              }
            },
            init: function () {
        @if(isset($product) && $product->photo)
              var file = {!! json_encode($product->photo) !!}
                  this.options.addedfile.call(this, file)
              this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
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

    <script>
        $(document).ready(function () {
          function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
              return {
                upload: function() {
                  return loader.file
                    .then(function (file) {
                      return new Promise(function(resolve, reject) {
                        // Init request
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route('admin.products.storeCKEditorImages') }}', true);
                        xhr.setRequestHeader('x-csrf-token', window._token);
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.responseType = 'json';

                        // Init listeners
                        var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                        xhr.addEventListener('error', function() { reject(genericErrorText) });
                        xhr.addEventListener('abort', function() { reject() });
                        xhr.addEventListener('load', function() {
                          var response = xhr.response;

                          if (!response || xhr.status !== 201) {
                            return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                          }

                          $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                          resolve({ default: response.url });
                        });

                        if (xhr.upload) {
                          xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                              loader.uploadTotal = e.total;
                              loader.uploaded = e.loaded;
                            }
                          });
                        }

                        // Send request
                        var data = new FormData();
                        data.append('upload', file);
                        data.append('crud_id', '{{ $product->id ?? 0 }}');
                        xhr.send(data);
                      });
                    })
                }
              };
            }
          }

          var allEditors = document.querySelectorAll('.ckeditor');
          for (var i = 0; i < allEditors.length; ++i) {
            ClassicEditor.create(
              allEditors[i], {
                extraPlugins: [SimpleUploadAdapter]
              }
            );
          }
        });
    </script>
@endsection
