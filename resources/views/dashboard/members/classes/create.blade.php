@extends('layouts.members')

@section('content')
    <div class="space-y-10">

        <div class="flex items-center justify-between">
            <div>
                <h3>Criar nova turma</h3>
                <p class="text-sm text-neutral-400">Configure sua turma e a liberação de conteúdo</p>
            </div>
        </div>

        @component('components.card')
            <div class="flex items-center justify-between p-6">
                <div class="flex items-center gap-2">
                    <div id="step1-circle"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-600 font-semibold text-white">
                        <span>
                            1
                        </span>
                        <span
                            class="hidden check-icon flex h-10 w-10 items-center justify-center rounded-full bg-primary font-semibold text-white">
                            @include('components.icon', ['icon' => 'check'])
                        </span>
                    </div>
                    <div>
                        <h4 class="font-semibold">Informações Gerais</h4>
                        <p class="text-sm text-neutral-500">Dados básicos da turma</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div id="step2-circle"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-200 font-semibold text-black">
                        2
                    </div>
                    <div>
                        <h4 class="font-semibold">Liberação de Conteúdo</h4>
                        <p class="text-sm text-neutral-500">Configure a liberação
                            {{ $course['hasTrack'] ? ' das Trilhas' : ' dos módulos' }}</p>
                    </div>
                </div>
            </div>
        @endcomponent
        <form id="classForm" action="{{ route('dashboard.members.createClass', ['courseId' => $course['id']]) }}"
            method="POST">
            @csrf
            <div class="form-step-1 space-y-6">
                @component('components.card')
                    <input type="hidden" name="hasTrack" value="{{ $course['hasTrack'] ? 'true' : 'false' }}">
                    <div class="space-y-6 p-6">
                        <div>
                            <h3>Informações gerais da turma</h3>
                            <p class="text-sm text-neutral-400">Preencha as informações abaixo para criar a turma.</p>
                        </div>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12">
                                <label for="name">Nome da turma</label>
                                <input name="name" placeholder="Nome da turma" required type="text"
                                    class="border rounded-md px-3 py-2 w-full" />
                            </div>
                            <div class="col-span-12">
                                <label for="access_type">Tipo de acesso</label>
                                <select name="access_type" required class="border rounded-md px-3 py-2 w-full">
                                    <option value="">Selecione o tipo de acesso</option>
                                    <option value="public">Acesso limitado</option>
                                    <option value="private">Acesso vitalício</option>
                                </select>
                            </div>
                            <div class="col-span-12">
                                <label for="access_duration">Duração do acesso</label>
                                <select name="access_duration" required class="border rounded-md px-3 py-2 w-full">
                                    <option value="">Selecione a duração do acesso</option>
                                    <option value="1">1 mês</option>
                                    <option value="3">3 meses</option>
                                    <option value="6">6 meses</option>
                                    <option value="12">12 meses</option>
                                </select>
                            </div>
                            <div class="col-span-12">
                                <label for="description">Descrição (Opcional)</label>
                                <textarea name="description" placeholder="Descrição da turma" rows="5" class="border rounded-md px-3 py-2 w-full"></textarea>
                            </div>
                            <div class="col-span-12 mt-3">
                                @component('components.toggle', [
                                    'id' => 'toggleDefaultClass',
                                    'name' => 'defaultClass',
                                    'label' => 'Tornar turma padrão',
                                ])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                @endcomponent
                @component('components.card')
                    <div class="space-y-6 p-6">
                        <div>
                            <h3>Ofertas vinculadas</h3>
                            <p class="text-sm text-neutral-400">Selecione as ofertas que serão vinculadas a esta turma</p>
                        </div>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12">
                                <label class="text-sm font-medium">Selecionar oferta existente</label>
                                <select id="offersSelect" class="border rounded-md px-3 py-2 text-sm w-full">
                                    <option value="">Escolha uma oferta</option>
                                    @foreach ($offers as $offer)
                                        <option value="{{ $offer->id }}">{{ $offer->name }}</option>
                                    @endforeach
                                </select>
                                <div class="space-y-2 mt-4">
                                    <label class="text-sm font-medium">Ofertas vinculadas:</label>
                                    <div id="linkedOffers" class="flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcomponent

                <div class="flex items-center justify-end gap-2">
                    <button class="button button-outline-light h-12 w-fit rounded-full" type="button"
                        onclick="history.back()">
                        Cancelar
                    </button>
                    <button class="button button-primary h-12 w-fit rounded-full" type="button" id="nextBtn">Salvar e
                        continuar</button>
                </div>
            </div>
            <div class="form-step-2 hidden space-y-6">
                @component('components.card')
                    <div class="space-y-6 p-6">
                        <div>
                            <h3>Configuração de liberação de conteúdo</h3>
                            <p class="text-sm text-neutral-400">Configure como o conteúdo será liberado para os alunos desta
                                turma</p>
                        </div>
                        <div class="space-y-4">
                            @foreach ($contents as $content)
                                <div class="module-container rounded-xl border border-neutral-100 shadow-sm">
                                    <h4 class="bg-neutral-50 p-4">
                                        {{ $course['hasTrack'] ? 'Trilha ' : 'Módulo ' }}{{ $content['name'] }}</h4>
                                    <div class="p-6">
                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12">
                                                <label for="release_type_{{ $content['id'] }}">Tipo de liberação</label>
                                                <select name="release_type_{{ $content['id'] }}"
                                                    class="border rounded-md px-3 py-2 w-full">
                                                    <option value="">Selecione o tipo de liberação</option>
                                                    <option value="1">Imediata</option>
                                                    <option value="2">Por dias</option>
                                                    <option value="3">Por data</option>
                                                </select>
                                            </div>
                                            <div class="release_days col-span-12">
                                                <label for="release_days_{{ $content['id'] }}">Dias após a liberação da
                                                    turma</label>
                                                <input id="release_days_{{ $content['id'] }}"
                                                    name="release_days_{{ $content['id'] }}" placeholder="Dias" type="number"
                                                    class="border rounded-md px-3 py-2 w-full" />
                                            </div>
                                            <div class="release_date col-span-12">
                                                <label for="release_date_{{ $content['id'] }}">Data de liberação</label>
                                                <input id="release_date_{{ $content['id'] }}"
                                                    name="release_date_{{ $content['id'] }}" placeholder="DD/MM/AAAA"
                                                    onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'"
                                                    type="text" class="border rounded-md px-3 py-2 w-full" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endcomponent

                <div class="flex items-center justify-end gap-2">
                    <button class="button button-outline-light mr-auto h-12 w-fit gap-1 rounded-full" type="button"
                        id="backBtn">
                        @include('components.icon', ['icon' => 'arrow_left_alt', 'custom' => 'text-xl'])
                        Anterior
                    </button>
                    <button class="button button-outline-light h-12 w-fit rounded-full" type="button">Cancelar</button>
                    <button class="button button-primary h-12 w-fit rounded-full" type="submit">Salvar</button>
                </div>
            </div>

        </form>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('classForm');
            const step1 = document.querySelector('.form-step-1');
            const step2 = document.querySelector('.form-step-2');
            const nextBtn = document.getElementById('nextBtn');
            const backBtn = document.getElementById('backBtn');

            const selectOffers = document.getElementById('offersSelect');
            const containerOffers = document.getElementById('linkedOffers');
            const selectedOffers = [];

            function renderChips() {
                containerOffers.innerHTML = '';
                selectedOffers.forEach(offer => {
                    const chip = document.createElement('span');
                    chip.className =
                        'flex items-center gap-2 px-3 py-1 rounded-md bg-gray-200 text-xs font-medium';
                    chip.innerHTML = `
                ${offer.name}
                <button type="button" class="hover:bg-gray-300 rounded-full p-0.5">×</button>
                <input type="hidden" name="offers[]" value="${offer.id}">
            `;
                    chip.querySelector('button').addEventListener('click', () => {
                        const index = selectedOffers.findIndex(o => o.id === offer.id);
                        if (index > -1) selectedOffers.splice(index, 1);
                        const option = document.createElement('option');
                        option.value = offer.id;
                        option.text = offer.name;
                        selectOffers.appendChild(option);
                        renderChips();
                    });
                    containerOffers.appendChild(chip);
                });
            }

            selectOffers.addEventListener('change', () => {
                const selectedOption = selectOffers.selectedOptions[0];
                if (selectedOption) {
                    const offer = {
                        id: selectedOption.value,
                        name: selectedOption.text
                    };
                    if (!selectedOffers.some(o => o.id === offer.id)) {
                        selectedOffers.push(offer);
                        selectedOption.remove();
                        renderChips();
                    }
                }
                selectOffers.value = '';
            });

            function updateStepCircle(activeStep) {
                const step1Circle = document.getElementById('step1-circle');
                const step2Circle = document.getElementById('step2-circle');
                const step1Number = step1Circle.querySelector('span:not(.check-icon)');
                const step1Check = step1Circle.querySelector('.check-icon');

                if (activeStep === 1) {
                    step1Circle.classList.add('bg-neutral-600', 'text-white');
                    step1Circle.classList.remove('bg-neutral-200', 'text-black');
                    step2Circle.classList.add('bg-neutral-200', 'text-black');
                    step2Circle.classList.remove('bg-neutral-600', 'text-white');
                    step1Number.classList.remove('hidden');
                    step1Check.classList.add('hidden');

                } else {
                    step2Circle.classList.add('bg-neutral-600', 'text-white');
                    step2Circle.classList.remove('bg-neutral-200', 'text-black');
                    step1Circle.classList.add('bg-neutral-200', 'text-black');
                    step1Circle.classList.remove('bg-neutral-600', 'text-white');
                    step1Number.classList.add('hidden');
                    step1Check.classList.remove('hidden');
                }
            }

            const step2Inputs = step2.querySelectorAll('select, input');
            step2Inputs.forEach(el => el.removeAttribute('required'));

            nextBtn.addEventListener('click', e => {
                e.preventDefault();
                const step1Inputs = step1.querySelectorAll('input, select, textarea');
                let valid = true;
                step1Inputs.forEach(el => {
                    if (!el.checkValidity()) {
                        el.reportValidity();
                        valid = false;
                    }
                });
                if (!valid) return;

                if (selectedOffers.length === 0) {
                    alert('Selecione pelo menos uma oferta antes de continuar.');
                    return;
                }

                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                updateStepCircle(2);
                step2.querySelectorAll('select[name^="release_type_"]').forEach(select => {
                    select.setAttribute('required', 'required');
                });
            });

            backBtn.addEventListener('click', () => {
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
                updateStepCircle(1);
            });

            function handleReleaseTypeChange(select) {
                const container = select.closest('.module-container');
                const days = container.querySelector('.release_days input');
                const date = container.querySelector('.release_date input');

                container.querySelector('.release_days').style.display = 'none';
                container.querySelector('.release_date').style.display = 'none';
                days.removeAttribute('required');
                date.removeAttribute('required');

                if (select.value === '2') {
                    container.querySelector('.release_days').style.display = 'block';
                    days.setAttribute('required', 'required');
                }
                if (select.value === '3') {
                    container.querySelector('.release_date').style.display = 'block';
                    date.setAttribute('required', 'required');
                }
            }

            document.querySelectorAll('[name^="release_type_"]').forEach(select => {
                handleReleaseTypeChange(select);
                select.addEventListener('change', () => handleReleaseTypeChange(select));
            });

            const accessTypeSelect = document.querySelector('select[name="access_type"]');
            const accessDurationSelect = document.querySelector('select[name="access_duration"]');

            function handleAccessTypeChange() {
                const show = accessTypeSelect.value !== 'private';
                accessDurationSelect.parentElement.style.display = show ? 'block' : 'none';
                if (show) accessDurationSelect.setAttribute('required', 'required');
                else accessDurationSelect.removeAttribute('required');
            }
            handleAccessTypeChange();
            accessTypeSelect.addEventListener('change', handleAccessTypeChange);
        });
    </script>
@endpush
