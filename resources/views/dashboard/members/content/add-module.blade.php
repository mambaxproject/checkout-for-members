@extends('layouts.members')

@section('content')
    <div class="space-y-10">
        <h3>Adicionar módulo</h3>
        <div class="space-y-8">
            <form id="formModulo" action="{{ route('dashboard.members.createModule') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                {{ csrf_field() }}
                <input type="hidden" name="draft" id="inputDraft" value="false">
                <input type="hidden" name="courseId" value="{{ $course['id'] }}">
                <input type="hidden" name="parentId" value="{{ $course['parentId'] }}">
                <div class="rounded-2xl bg-white">
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-12">
                            <div class="col-span-12">
                                <label id="name" class="mb-1 mt-3">Nome do módulo</label>
                                <input class="mt-2" type="text" id="name" name="name"
                                    placeholder="Digite o nome do seu módulo" value="{{ old('name') }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        @if (is_null($course['parentId']) && !$course['hasTrack'])
                            <div class="col-span-12 lg:col-span-12">
                                <label id="description mb-1">Descrição do módulo (Opcional)</label>
                                <textarea rows="6" id="description" name="description" minlength="150" maxlength="245"
                                    placeholder="Explique o seu módulo em no máximo 245 characters" oninput="setCharacterLimit(this)">{{ old('description') }}</textarea>
                                <p class="error-msg" id="error-msg-description"></p>
                            </div>
                        @endif
                    </div>
                    @if (is_null($course['parentId']))
                        <div class="px-6 pb-6">
                            <div class="col-span-12 mt-5">
                                <label for="thumbnail"> Thumbnail do módulo</label>
                                @component('components.dropzone', [
                                    'id' => 'thumbnail',
                                    'name' => 'thumbnail',
                                    'accept' => 'image/*',
                                    'required' => true,
                                ])
                                @endcomponent
                                <p class="mt-1 text-sm text-neutral-400">Adicione uma image do módulo com no máximo de 5mb
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
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
            </form>
        </div>
    </div>
    <script>
        function submitForm(isDraft) {
            document.getElementById('inputDraft').value = isDraft ? 'true' : 'false';
            document.getElementById('formModulo').submit();
        }
    </script>
@endsection
