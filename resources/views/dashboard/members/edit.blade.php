@extends('layouts.members')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>{{ $course['name'] }}</h1>

        <form id="formModulo" enctype="multipart/form-data" method="POST"
            action="{{ route('dashboard.members.update', ['courseId' => $course['id']]) }}">

            @csrf
            @method('PUT')

            {{ csrf_field() }}

            <input name="old_name" value="{{ $course['name'] }}" type="hidden">

            <input name="old_description" value="{{ $course['description'] }}" type="hidden">

            <div class="space-y-4 md:space-y-10">

                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-6">
                        <div class="flex justify-between">
                            <h3>Editar {{ $course['hasTrack'] ? 'formação' : 'curso' }}</h3>
                            <div>
                                @component('components.toggle', [
                                    'id' => 'commentPublic',
                                    'label' => 'Thumbnails na vertical',
                                    'name' => 'verticalThumb',
                                    'isChecked' => $course['displayType']
                                ])
                                @endcomponent

                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-6">

                                <div class="col-span-12">
                                    <label>Nome {{ $course['hasTrack'] ? 'da formação' : 'do curso' }}</label>
                                    <input name="name" type="text" value="{{ $course['name'] }}">
                                </div>

                                <div class="col-span-12">
                                    <label>Descrição</label>
                                    <textarea maxlength="255" name="description" rows="6">{{ $course['description'] }}</textarea>
                                </div>

                                <div class="col-span-12">
                                    <label for="thumbnail">Thumbnail {{ $course['hasTrack'] ? 'da formação' : 'do curso' }}</label>
                                    @include('components.dropzone', [
                                        'id' => 'thumbnail',
                                        'name' => 'thumbnail',
                                        'accept' => 'image/*',
                                        'required' => false,
                                    ])
                                    <p class="mt-1 text-sm text-neutral-400">Adicione uma image
                                        {{ $course['hasTrack'] ? 'da formação' : 'do curso' }} com no máximo de 5mb</p>
                                </div>

                                <div class="col-span-12">
                                    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                        <div class="overflow-x-scroll md:overflow-visible">
                                            <table class="table-lg table w-full">
                                                <thead>
                                                    <tr>
                                                        <th>Thumbnail atual</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <a class="inline-block" title="Ver Thumbnail"
                                                                href="{{ $course['thumbnailUrl'] }}" target="_blank">
                                                                <img class="h-20 w-20 rounded-lg object-cover"
                                                                    src="{{ $course['thumbnailUrl'] }}"
                                                                    alt="{{ $course['thumbnailUrl'] }}" loading="lazy" />
                                                            </a>
                                                        </td>
                                                        <td class="text-end">
                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-12">
                                    <label for="thumbnail">Capa {{ $course['hasTrack'] ? 'da formação' : 'do curso' }}</label>
                                    @include('components.dropzone', [
                                        'id' => 'cover',
                                        'name' => 'cover',
                                        'accept' => 'image/*',
                                        'required' => false,
                                    ])
                                    <p class="mt-1 text-sm text-neutral-400">Adicione uma image da capa
                                        {{ $course['hasTrack'] ? 'da formação' : 'do curso' }} com no máximo de 5mb, sugerimos uma
                                        imagem de 1700x850</p>
                                </div>

                                <div class="col-span-12">
                                    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                        <div class="overflow-x-scroll md:overflow-visible">
                                            <table class="table-lg table w-full">
                                                <thead>
                                                    <tr>
                                                        <th>Capa {{ $course['hasTrack'] ? 'da formação' : 'do curso' }}</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <a class="inline-block" title="Ver capa" href="{{ $course['cover'] }}"
                                                                target="_blank">
                                                                <img class="h-20 w-20 rounded-lg object-cover"
                                                                    src="{{ $course['cover'] }}" alt="{{ $course['cover'] }}"
                                                                    loading="lazy" />
                                                            </a>
                                                        </td>
                                                        <td class="text-end"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    @endcomponent

                    <button class="button button-primary mx-auto h-12 rounded-full" type="submit">
                        Salvar Alterações
                    </button>

                </div>

            </form>

        </div>
    @endsection
