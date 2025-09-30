@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between gap-6 md:gap-8 lg:gap-10">
            <h1>Integração CRM</h1>
        </div>

        {{-- Descrição --}}
        <p class="text-neutral-600">
            Configure o envio automático de eventos dos relatórios para seus funis de CRM.
        </p>

        {{-- Status da integração --}}
        @component('components.card', ['custom' => 'p-4 md:p-6 xl:p-8'])
            <div class="flex flex-col gap-4">
                <h3>Status da Integração</h3>
                <p>Habilite ou desabilite a integração com seu sistema de CRM</p>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <form action="{{route('dashboard.suitpay-crm-integration.activeCRM')}}" method="post">
                            @csrf
                            <input type="checkbox" name="integration_status" onclick="this.form.submit()" {{$isActivated ? 'checked' : ''}}>
                            @if($isActivated)
                                <span class="font-semibold text-success-600">Integração Ativada</span>
                            @else
                                <span class="font-semibold text-danger-600">Integração Desativada</span>
                            @endif
                        </form>
                    </label>
                </div>
            </div>
        @endcomponent

        {{-- Regras de Integração --}}
        @component('components.card', ['custom' => 'p-4 md:p-6 xl:p-8'])
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h3>Regras de Integração</h3>
                    <button data-drawer-target="drawerAddRule"
                            data-drawer-show="drawerAddRule"
                            data-drawer-placement="right"
                            class="button button-primary h-12 w-auto">
                        @include('components.icon', [
                            'icon' => 'add',
                            'custom' => 'text-xl',
                        ])
                        Adicionar Regra
                    </button>
                </div>

                @component('components.card', ['custom' => 'overflow-hidden'])
                    <div class="overflow-x-scroll md:overflow-visible">
                        <table class="table w-full">
                            <thead>
                            <tr>
                                <th>Origem</th>
                                <th>Evento</th>
                                <th>Funil</th>
                                <th>Etapa</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($rules as $rule)
                                <tr>
                                    <td>
                                        <p>{{ \App\Enums\CRMOriginEnum::getDescription($rule->origin) }}</p>
                                    </td>
                                    <td>{{ \App\Enums\CRMEventTriggerEnum::getDescription($rule->event_trigger) }}</td>
                                    <td>{{ $rule->funnel_name }}</td>
                                    <td>{{ $rule->step_name }}</td>
                                    <td>
                                        <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                            @include('components.icon', [
                                                'icon' => 'circle',
                                                'type' => 'fill',
                                                'custom' => 'text-xs '. \App\Enums\StatusEnum::getClassText($rule->status),
                                            ])

                                            {{ \App\Enums\StatusEnum::getFromName($rule->status) }}
                                        </div>
                                    </td>
                                    <td>
                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreTableTelegram' . $rule->id,
                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>
                                                <li>
                                                    <button
                                                            data-drawer-target="drawerAddRule"
                                                            data-drawer-show="drawerAddRule"
                                                            data-drawer-placement="right"
                                                            data-data="{{$rule->toJson()}}"
                                                            data-url="{{ route('dashboard.suitpay-crm-integration.update', $rule->id) }}"
                                                            class="flex editRule items-center rounded-lg px-3 py-2 hover:bg-neutral-100">
                                                        Editar
                                                    </button>
                                                </li>

                                                <li>
                                                    <form method="POST" action="{{ route('dashboard.suitpay-crm-integration.updateStatus', $rule->id) }}">
                                                        @csrf
                                                        <button type="button" onclick="if (confirm('Tem certeza?')) this.closest('form').submit();" class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100" title="Desativar regra">
                                                            {{ $rule->isActive ? 'Desativar' : 'Ativar'}}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('dashboard.suitpay-crm-integration.destroy', $rule->id) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" onclick="if (confirm('Tem certeza?')) this.closest('form').submit();" class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100" title="Deletar regra">
                                                            Remover
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        @endcomponent
                                    </td>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div
                                                class="col-span-12 rounded-lg bg-gray-50 p-4 text-center text-sm text-gray-800 dark:bg-gray-800 dark:text-gray-300"
                                                role="alert"
                                        >
                                            Sem registros.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                @endcomponent
        @endcomponent

    </div>
@endsection


@push('floating')

    @component('components.drawer', [
       'id' => 'drawerAddRule',
       'title' => 'Adicionar Regra',
       'custom' => 'persist-inputs max-w-xl',
   ])
        <form action="{{ route('dashboard.suitpay-crm-integration.store') }}" method="POST">
            @csrf
            @method('POST')
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="origin">Origem</label>
                    <select id="origin" name="origin" required>
                        <option value="">Selecione a origem</option>
                        @foreach (\App\Enums\CRMOriginEnum::getDescriptions() as $origin)
                            <option value="{{ $origin['value'] }}">
                                {{ $origin['description'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="event_trigger">Status do Evento</label>
                    <select id="event_trigger" name="event_trigger" required>
                        <option value="">Selecione o evento</option>
                        @foreach (\App\Enums\CRMEventTriggerEnum::getDescriptions() as $event)
                            <option value="{{ $event['value'] }}">
                                {{ $event['description'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="funnel">Funil CRM</label>
                    <select id="funnel" name="funnel_id" required>
                        <option value="">Selecione</option>
                        @foreach ($pipelines as $pipeline)
                            <option value="{{ $pipeline['id'] }}">
                                {{ $pipeline['title'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="step">Etapa do Funil</label>
                    <select id="step" name="step_id" required>
                        <option value="">Selecione</option>
                    </select>
                </div>

                <input type="hidden" id="funnel_name" name="funnel_name" value="" required>
                <input type="hidden" id="step_name" name="step_name" value="" required>
            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Adicionar
            </button>

        </form>
    @endcomponent

    {{-- MODAL SUCCESS --}}
    @if (session('modalMessage'))
        @component('components.modal', [
            'id' => 'successModal',
            'title' => '',
        ])
            <div class="px-20 pb-20">
                <div id="lottie-animation" style="width: 200px; height: 200px; margin: auto;"></div>
                <h3 class="mb-2 text-center font-semibold">Parabéns!!</h3>
                <p class="text-center">{!! session('modalMessage') !!}</p>
            </div>
        @endcomponent

        <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.0/lottie.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('successModal');
                const modalInstance = new Modal(modal);
                modalInstance.show();
            });

            // Caminho gerado pelo Laravel
            const lottieAnimationPath = "{{ asset('images/dashboard/animates/success.json') }}";

            // Inicializar a animação
            lottie.loadAnimation({
                container: document.getElementById('lottie-animation'), // Elemento onde a animação será renderizada
                renderer: 'svg', // Renderizador (svg, canvas, html)
                loop: false, // Define se a animação deve ser reproduzida em loop
                autoplay: true, // Define se a animação começa automaticamente
                path: lottieAnimationPath // Caminho para o arquivo JSON
            });
        </script>
    @endif
@endpush

@push('custom-script')
    <script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>

    <script>
        let pipeline;
        let pipelines = @json($pipelines);
        let abandonedCartEvents = @json(\App\Enums\CRMEventTriggerEnum::getAbandonedCartDescriptions());
        let orderEvents =  @json(\App\Enums\CRMEventTriggerEnum::getOrderDescriptions());

        function updateEventTrigger(origin) {
            let eventTrigger = $('#event_trigger');

            eventTrigger.empty();
            eventTrigger.append("<option value=\"\">Selecione</option>");

            if (origin === 'abandoned_cart') {
                abandonedCartEvents.forEach(event => {
                    eventTrigger.append(`<option value="${event.value}">${event.description}</option>`);
                });
            } else {
                orderEvents.forEach(event => {
                    eventTrigger.append(`<option value="${event.value}">${event.description}</option>`);
                });
            }
        }

        $('#funnel').on('change', function() {
            pipeline = pipelines.find(funnel => funnel.id == $(this).val())

            $('#funnel_name').val(pipeline.title);

            let stepSelect = $("#step");
            stepSelect.empty();
            stepSelect.append("<option value=\"\">Selecione</option>");

            if (pipeline && pipeline.column) {
                pipeline.column.forEach(column => {
                    stepSelect.append(`<option value="${column.id}">${column.name}</option>`);
                });
            }
        });

        $('#step').on('change', function() {
            let column = pipeline.column.find(column => column.id == $(this).val())
            $('#step_name').val(column.name);
        })

        $('#origin').on('change', function() {
            updateEventTrigger($(this).val())
        })

        $(document).on('click', '.editRule', function() {
            const data = $(this).data('data');
            const url = $(this).data('url');
            const drawer = document.getElementById('drawerAddRule');

            drawer.querySelector('form').setAttribute('action', url);
            drawer.querySelector('input[name=_method]').value = "put";

            drawer.querySelector('.button').innerHTML = "Salvar";
            drawer.querySelector('.titleDrawer').innerHTML = 'Editar grupo';
            drawer.querySelector('select[name=origin]').value = data.origin;

            updateEventTrigger(data.origin)

            drawer.querySelector('select[name=event_trigger]').value = data.event_trigger;
            drawer.querySelector('select[name=funnel_id]').value = data.funnel_id;

            pipeline = pipelines.find(funnel => funnel.id == $('#funnel').val())
            let stepSelect = $("#step");
            pipeline.column.forEach(column => {
                stepSelect.append(`<option value="${column.id}">${column.name}</option>`);
            });

            drawer.querySelector('select[name=step_id]').value = data.step_id;
        })
    </script>
@endpush
