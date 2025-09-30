@extends('layouts.members')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>{{ $course['name'] }}</h1>

        <form id="formTrack" enctype="multipart/form-data" method="POST"
            action="{{ route('dashboard.members.updateCourseTrack', ['courseId' => $course['id']]) }}">
            @csrf
            @method('PUT')
            {{ csrf_field() }}
            <input type="hidden" id="inputDraft" name="inputDraft" value="">
            <input name="old_name" value="{{ $course['name'] }}" type="hidden">

            <input name="old_description" value="{{ $course['description'] }}" type="hidden">

            <div class="space-y-4 md:space-y-10">

                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-6">

                        <h3>Editar curso</h3>

                        <div class="grid grid-cols-12 gap-6">

                            <div class="col-span-12">
                                <label>Nome do curso</label>
                                <input name="name" type="text" value="{{ $course['name'] }}">
                            </div>
                            <div class="col-span-12">
                                <label for="thumbnail">Thumbnail do curso</label>
                                @include('components.dropzone', [
                                    'id' => 'thumbnail',
                                    'name' => 'thumbnail',
                                    'accept' => 'image/*',
                                    'required' => false,
                                ])
                                <p class="mt-1 text-sm text-neutral-400">Adicione uma image do curso com no máximo de 5mb</p>
                            </div>
                            <div class="col-span-12">
                                <label>Descrição (Opcional)</label>
                                <textarea maxlength="245" name="description" rows="6">{{ $course['description'] }}</textarea>
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
                        </div>

                    </div>
                @endcomponent
                <div class="flex items-center justify-end">
                    <button
                        class="button h-12 rounded-full border border-neutral-200 hover:bg-neutral-200 active:bg-neutral-300"
                        type="button" onclick="submitForm(true)">
                        Salvar como rascunho
                    </button>

                    <button class="button button-primary h-12 rounded-full" type="button" onclick="submitForm(false)">
                        Salvar Alterações
                    </button>
                </div>
            </div>

        </form>

    </div>
    <script>
        function submitForm(isDraft) {
            document.getElementById('inputDraft').value = isDraft ? 'true' : 'false';
            document.getElementById('formTrack').submit();
        }
    </script>
@endsection
