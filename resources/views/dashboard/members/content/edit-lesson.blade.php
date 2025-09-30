@extends('layouts.members')

@section('content')
    <form id="formModulo" class="mt-4 mb-4"
        action="{{ route('dashboard.members.updateLesson', ['lessonId' => $lesson['id']]) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        {{ csrf_field() }}
        <div class="space-y-10">
            <h3>Editar conteúdo: Vídeo</h3>
            <input type="hidden" name="courseId" value="{{ $course['id'] }}">
            <input type="hidden" name="moduleId" value="{{ $lesson['moduleId'] }}">
            <input type="hidden" name="old_name" value="{{ $lesson['name'] }}">
            <input type="hidden" name="old_description" value="{{ $lesson['description'] }}">
            <input type="hidden" name="old_videoUrl" value="{{ $lesson['videoUrl'] }}">
            <input type="hidden" name="draft" id="inputDraft" value="false">
            <div class="space-y-8">
                <div class="rounded-2xl bg-white">
                    <div class="px-6 pb-6 pt-5">
                        <div class="grid grid-cols-12 gap-6">

                            <div class="col-span-12">
                                <label id="name" class="mb-1">Nome da aula <span
                                        class="text-danger-400">*</span></label>
                                <input value="{{ $lesson['name'] }}" class="mt-2" type="text" id="name"
                                    name="name" placeholder="Digite o nome da sua aula" required />
                            </div>

                            <div class="col-span-12">
                                <label id="name" class="mb-1">Url da aula <span
                                        class="text-danger-400">*</span></label>
                                <input class="mt-2 {{ $lesson['videoProvider'] === 'cloudflare' ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : '' }}" type="url" value="{{ $lesson['videoUrl'] }}" id="videoUrl"
                                    name="videoUrl" placeholder="Digite a url sua aula" required 
                                    {{ $lesson['videoProvider'] === 'cloudflare' ? 'disabled' : '' }}/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white">

                    <div class="p-6">
                        <h5 class="font-medium">Descrição da aula (Opcional)</h5>
                    </div>

                    <div class="px-6 pb-6">
                        <textarea rows="6" id="description" name="description" maxlength="5000"
                            placeholder="Explique a sua aula no máximo 5000 characters" oninput="setCharacterLimit(this)">{{ $lesson['description'] }}</textarea>
                    </div>

                </div>

                <div class="rounded-2xl bg-white">

                    <div class="p-6">
                        <h5 class="font-medium">Anexos</h5>
                    </div>
                    <div class="px-6 pb-6">
                        @include('components.dropzone', [
                            'id' => 'attachments[]',
                            'name' => 'attachments[]',
                            'accept' => 'image/*,application/pdf',
                            'isMultiple' => true,
                        ])
                    </div>
                </div>

                @if (!empty($lesson['Attachments']))
                    <div class="col-span-12">
                        <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                            <div class="overflow-x-scroll md:overflow-visible">
                                <table class="table-lg table w-full">
                                    <thead>
                                        <tr>
                                            <th>Anexo</th>
                                            <th>Nome</th>
                                            <th>Extensão</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lesson['Attachments'] as $key => $attachments)
                                            <tr class="attachmentMedia">
                                                <td>
                                                    <a href="{{ $attachments['url'] }}" title="Ver anexo"
                                                        data-tooltip-text="Ver anexo" target="_blank">
                                                        @include('components.icon', [
                                                            'icon' => 'description',
                                                            'custom' => 'text-2xl',
                                                        ])
                                                    </a>
                                                </td>
                                                <td>{{ basename($attachments['url']) }}</td>
                                                <td>
                                                    <span
                                                        class="rounded-md bg-neutral-600 px-3 py-2 text-xs font-semibold uppercase text-white md:mr-[20%]">
                                                        {{ pathinfo($attachments['url'], PATHINFO_EXTENSION) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    @component('components.dropdown-button', [
                                                        'id' => 'dropdownMoreTableAnexofeaturedImage' . $key,
                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                        'customContainer' => 'ml-auto w-fit',
                                                        'custom' => 'text-xl',
                                                    ])
                                                        <ul>
                                                            <li>
                                                                <button
                                                                    class="deleteRow flex w-full items-center rounded-lg px-3 py-2 text-sm text-danger-500 hover:bg-danger-50"
                                                                    data-url="{{ route('dashboard.members.deleteLessonComplement', ['complementId' => $attachments['id']]) }}">
                                                                    Remover
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    @endcomponent
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="flex items-center justify-end gap-4 mt-5">
                    <button
                        class="button h-12 rounded-full border border-neutral-200 hover:bg-neutral-200 active:bg-neutral-300"
                        type="submit" onclick="submitForm(true)">
                        Salvar como rascunho
                    </button>

                    <button class="button button-primary h-12 rounded-full" type="submit" onclick="submitForm(false)">
                        Salvar Alterações
                    </button>
                </div>

            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <script src="{{ asset('js/dashboard/dropzone-config.js') }}"></script>
    <script src="{{ asset('js/members/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/members/tinymce/langs/pt_BR.js') }}"></script>
    <script>
        function submitForm(isDraft) {
            document.getElementById('inputDraft').value = isDraft ? 'true' : 'false';
            document.getElementById('formModulo').submit();
        }

        tinymce.init({
            selector: '#textarea',
            language: 'pt_BR',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | table',
            plugins: 'lists, table',
            menubar: false,
            height: 300,
            branding: false,
        });

        $(document).on('click', '.deleteRow', function(event) {
            event.preventDefault();

            let url = $(this).data('url');

            if (!confirm('Tem certeza que deseja remover esse anexo permanentemente?')) {
                return;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    window.location.reload();
                },
            });
        });
    </script>
@endpush
