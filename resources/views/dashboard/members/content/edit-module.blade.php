@extends('layouts.members')

@section('content')
    <div class="space-y-10">
        <h3>Editar módulo</h3>
        <div class="space-y-8">
            <form id="formModulo" action="{{ route('dashboard.members.updateModule', ['moduleId' => $module['id']]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                {{ csrf_field() }}
                <input type="hidden" name="old_name" value="{{ $module['name'] }}">
                <input type="hidden" name="old_description" value="{{ $module['description'] }}">
                <input type="hidden" name="draft" id="inputDraft" value="false">
                <div class="rounded-2xl bg-white">
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-12">
                            <div class="col-span-12">
                                <label id="name" class="mb-1 mt-3">Nome do módulo</label>
                                <input class="mt-2" type="text" id="name" name="name"
                                    placeholder="Digite o nome do seu módulo" value="{{ $module['name'] }}" required />
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
                    @if (!$course['hasTrack'])
                        <div class="px-6 pb-6">
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
                                                    <a title="Ver Thumbnail" href="{{ $module['thumbnailUrl'] }}"
                                                        target="_blank">
                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                            src="{{ $module['thumbnailUrl'] }}"
                                                            alt="{{ $module['thumbnailUrl'] }}" loading="lazy" />
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
                        <div class="px-6 pb-6">
                            <div class="col-span-12 mt-5">
                                <label for="thumbnail"> Thumbnail do módulo(Obrigatório *)</label>
                                @component('components.dropzone', [
                                    'id' => 'thumbnail',
                                    'name' => 'thumbnail',
                                    'accept' => 'image/*',
                                    'required' => false,
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
        document.addEventListener("DOMContentLoaded", function() {
            const checkbox = document.getElementById("limitContentDuration");
            const toggleContent = document.querySelector("#toggle-component-limitContentDuration .toggleContent");

            function toggleVisibility() {
                if (checkbox.checked) {
                    setTimeout(() => {
                        toggleContent.classList.remove("hidden");
                        toggleContent.style.display = "block";
                    }, 100);
                } else {
                    toggleContent.classList.add("hidden");
                    toggleContent.style.display = "none";
                }
            }

            toggleVisibility();


            checkbox.addEventListener("change", toggleVisibility);
        });
    </script>
@endsection
