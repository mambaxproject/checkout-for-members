<div class="tab-content hidden" id="tab-area-members" data-tab="tab-area-members">
    <form action="{{ route('dashboard.members.create') }}" id="formMembers" method="POST" enctype="multipart/form-data">
        @csrf
        {{ csrf_field() }}

        <input name="redirect" id="redirectMembers" value="1" type="hidden">

        <input name="categoryId" value="{{ $product->category_id }}" type="hidden">

        <input name="productUuid" value="{{ $product->client_product_uuid }}" type="hidden">

        <input type="hidden" id="typeTrack" name="typeTrack" value="<?php echo !empty($courseSuitMembers) ? ($courseSuitMembers['hasTrack'] ? 'formation' : 'course') : 0; ?>">
        <input name="isTrack" id="isTrack" value="<?php echo !empty($courseSuitMembers) ? $courseSuitMembers['hasTrack'] : 0; ?>" type="hidden">

        <div class="space-y-4 md:space-y-10">

            @component('components.card', ['custom' => 'p-6 md:p-8'])

                @component('components.toggle', [
                    'id' => 'toggleAddCourse',
                    'label' => 'Habilitar área de membros',
                    'isChecked' => !empty($courseSuitMembers),
                ])
                    <div class="space-y-6 pt-4">

                        <div class="flex items-center justify-between">

                            <h3>Área de membros</h3>

                            @if (!empty($courseSuitMembers))
                                <a class="button button-primary h-12 rounded-full" target="_blank"
                                    href="{{ route('dashboard.members.edit', ['courseId' => $courseSuitMembers['id']]) }}">
                                    Continuar editando curso
                                    @include('components.icon', [
                                        'icon' => 'arrow_forward',
                                        'custom' => 'text-xl text-white',
                                    ])
                                </a>
                            @endif

                        </div>

                        <div class="grid grid-cols-12 gap-6">

                            <div class="col-span-12">

                                <label for="">Tipo de conteúdo</label>

                                <div class="space-x-4">

                                    <label id="labelTypeContentCourse" for="typeContentCourse">

                                        <input class="peer hidden" id="typeContentCourse" name="typeContent"
                                            {{ empty($courseSuitMembers) || !$courseSuitMembers['hasTrack'] ? 'checked' : '' }}
                                            type="radio">

                                        <div
                                            class="cursor-pointer rounded-lg border border-neutral-200 p-6 hover:border-neutral-200 hover:bg-neutral-50 peer-checked:border-primary peer-checked:bg-green-50 peer-checked:[&_i]:text-primary">
                                            <div class="flex items-center gap-4">
                                                @include('components.icon', [
                                                    'icon' => 'photo_frame',
                                                    'custom' => 'text-3xl',
                                                ])
                                                <div class="">
                                                    <h4 class="text-lg font-semibold">Curso</h4>
                                                    <p>Um curso individual com aulas e conteúdo específico</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label id="labelTypeContentFormation" for="typeContentFormation">
                                        <input class="peer hidden" id="typeContentFormation" name="typeContent"
                                            {{ isset($courseSuitMembers['hasTrack']) && $courseSuitMembers['hasTrack'] ? 'checked' : '' }}
                                            type="radio">
                                        <div
                                            class="cursor-pointer rounded-lg border border-neutral-200 p-6 hover:border-neutral-200 hover:bg-neutral-50 peer-checked:border-primary peer-checked:bg-green-50 peer-checked:[&_i]:text-primary">
                                            <div class="flex items-center gap-4">
                                                @include('components.icon', [
                                                    'icon' => 'subscriptions',
                                                    'custom' => 'text-3xl',
                                                ])
                                                <div class="">

                                                    <h4 class="text-lg font-semibold">Formação</h4>
                                                    <p>Um conjunto de cursos agrupados por tema ou objetivo</p>

                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-span-12">

                                <label for="name">
                                    Nome do curso
                                </label>

                                <input type="text" id="name" name="name"
                                    value="{{ !empty($courseSuitMembers) ? $courseSuitMembers['name'] : old('name') }}"
                                    placeholder="Digite o nome do seu curso" {{ !empty($courseSuitMembers) ? 'disabled' : '' }}
                                    required />

                            </div>

                            <div class="col-span-12">

                                <label for="description">
                                    Descrição
                                </label>

                                <textarea rows="6" id="description" name="description" minlength="150" maxlength="245"
                                    placeholder="Explique o seu curso em no mínimo 150 caracteres e no máximo 245" oninput="setCharacterLimit(this)"
                                    {{ !empty($courseSuitMembers) ? 'disabled' : '' }} required>{{ !empty($courseSuitMembers) ? $courseSuitMembers['description'] : old('description') }}</textarea>

                            </div>

                            @if (empty($courseSuitMembers))
                                <div class="col-span-12">

                                    <label for="thumbnail">Thumbnail do curso (Obrigatório *)</label>

                                    @include('components.dropzone', [
                                        'id' => 'thumbnail',
                                        'name' => 'thumbnail',
                                        'accept' => 'image/*',
                                        'required' => true,
                                    ])

                                    <p class="mt-1 text-sm text-neutral-400">Adicione uma image com no máximo de 5mb</p>

                                </div>
                            @else
                                <div class="col-span-12">

                                    <table class="table w-full">
                                        <thead>
                                            <tr>
                                                <th>Thumbnail</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a title="Ver Thumbnail" href="{{ $courseSuitMembers['thumbnailUrl'] }}"
                                                        target="_blank">
                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                            src="{{ $courseSuitMembers['thumbnailUrl'] }}"
                                                            alt="{{ $courseSuitMembers['thumbnailUrl'] }}" loading="lazy" />
                                                    </a>
                                                </td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            @endif

                            @if (empty($courseSuitMembers))
                                <div class="col-span-12">

                                    <label for="cover">Capa do curso (Obrigatório *)</label>

                                    @include('components.dropzone', [
                                        'id' => 'cover',
                                        'name' => 'cover',
                                        'accept' => 'image/*',
                                        'required' => true,
                                    ])

                                    <p class="mt-1 text-sm text-neutral-400">Adicione uma image com no máximo de 5mb, sugerimos
                                        uma imagem de 1700x850</p>

                                </div>
                            @else
                                <div class="col-span-12">

                                    <table class="table w-full">

                                        <thead>
                                            <tr>
                                                <th>Capa do curso</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a title="Ver Capa" href="{{ $courseSuitMembers['cover'] }}"
                                                        target="_blank">
                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                            src="{{ $courseSuitMembers['cover'] }}"
                                                            alt="{{ $courseSuitMembers['cover'] }}" loading="lazy" />
                                                    </a>
                                                </td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>

                                </div>
                            @endif

                        </div>

                    </div>
                @endcomponent

            @endcomponent

            <div class="actions flex items-center justify-center gap-4">

                @if (!is_null($product->category_id) && empty($courseSuitMembers))
                    <button class="button button-primary h-12 w-full max-w-xs rounded-full"
                        onclick="submitFormMembers(event, false)" type="button">
                        Salvar
                    </button>
                    <button class="button button-primary h-12 w-full max-w-xs rounded-full"
                        onclick="submitFormMembers(event, true)" type="button">
                        Salvar e configurar curso
                        @include('components.icon', [
                            'icon' => 'arrow_forward',
                            'custom' => 'text-xl text-white',
                        ])
                    </button>
                @endif
            </div>
        </div>

    </form>
</div>

@push('custom-script')
    <script>
        function setCharacterLimit(textarea) {
            const maxLength = parseInt(textarea.getAttribute('maxlength'));
            const currentLength = textarea.value.length;
            if (currentLength > maxLength) {
                textarea.value = textarea.value.substring(0, maxLength);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const typeContentCourse = document.getElementById('typeContentCourse');
            const typeContentFormation = document.getElementById('typeContentFormation');
            const isTrack = document.getElementById('isTrack');
            const typeTrack = document.getElementById('typeTrack');
            const nameLabel = document.querySelector('label[for="name"]');
            const nameInput = document.getElementById('name');
            const descriptionLabel = document.querySelector('label[for="description"]');
            const descriptionTextarea = document.getElementById('description');
            const thumbnailDropzone = document.querySelector('label[for="thumbnail"]');
            const coverDropzone = document.querySelector('label[for="cover"]');
            const labelTypeContentCourse = document.getElementById('labelTypeContentCourse');
            const labelTypeContentFormation = document.getElementById('labelTypeContentFormation');


            function updateContentLabels(formation) {
                if (!formation) {
                    nameLabel.textContent = 'Nome do curso (Obrigatório *)';
                    nameInput.placeholder = 'Digite o nome do seu curso';
                    descriptionLabel.textContent = 'Descrição do curso (Obrigatório *)';
                    descriptionTextarea.placeholder =
                        'Explique o seu curso em no mínimo 150 caracteres e no máximo 245';
                    thumbnailDropzone.textContent = 'Thumbnail do curso (Obrigatório *)';
                    coverDropzone.textContent = 'Capa do curso (Obrigatório *)';
                    isTrack.value = 0;
                } else if (formation) {
                    nameLabel.textContent = 'Nome da formação (Obrigatório *)';
                    nameInput.placeholder = 'Digite o nome da sua formação';
                    descriptionLabel.textContent = 'Descrição da formação (Obrigatório *)';
                    descriptionTextarea.placeholder =
                        'Explique a sua formação em no mínimo 150 caracteres e no máximo 245';
                    thumbnailDropzone.textContent = 'Thumbnail da formação (Obrigatório *)';
                    coverDropzone.textContent = 'Capa da formação (Obrigatório *)';
                    isTrack.value = 1;
                }
            }

            typeContentCourse.addEventListener('change', () => updateContentLabels(false));
            typeContentFormation.addEventListener('change', () => updateContentLabels(true));

            if (typeTrack.value == 'formation') {
                labelTypeContentCourse.style.display = 'none'
                updateContentLabels(true)
            }

            if (typeTrack.value == 'course') {
                labelTypeContentFormation.style.display = 'none'
                updateContentLabels(false)
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const toggleAddCourse = document.getElementById('toggleAddCourse');
            const actions = document.querySelector('.actions');

            if (!toggleAddCourse || !actions) return;

            const toggleActionsVisibility = () => {
                actions.classList.toggle('hidden', !toggleAddCourse.checked);
            };

            toggleAddCourse.addEventListener('change', toggleActionsVisibility);

            toggleActionsVisibility();
        });

        function submitFormMembers(event, redirect) {
            event.preventDefault();
            const form = document.getElementById('formMembers');
            if (redirect) {
                form.setAttribute('target', '_blank');
            } else {
                form.removeAttribute('target');
            }
            document.getElementById('redirectMembers').value = redirect ? 1 : 0;
            form.submit();
        }
    </script>
@endpush
