@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h1>Grupos</h1>

            <div class="flex items-center gap-2">
                <button
                        class="button button-primary h-12 rounded-full"
                        data-drawer-target="drawerTelegramGroups"
                        data-drawer-show="drawerTelegramGroups"
                        data-drawer-placement="right"
                        type="button"
                >
                    @include('components.icon', [
                        'icon' => 'add',
                        'custom' => 'text-xl',
                    ])
                    Adicionar Grupo
                </button>
            </div>
        </div>


        <div>

            @component('components.card', ['custom' => 'overflow-hidden'])
                <div class="overflow-x-scroll md:overflow-visible">
                    <table class="table w-full">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>Produto</th>
                            <th>Situação</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($groups as $group)
                            <tr>
                                <td>{{ $group->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <p>{{ $group->name }}</p>
                                </td>
                                <td>{{ $group?->product?->name }}</td>
                                <td>
                                    <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' => 'text-xs ' . \App\Enums\SituationTelegramGroupEnum::getClass($group->status),
                                        ])
                                        {{ $group->situationFormatted }}
                                    </div>
                                </td>
                                <td>
                                    @component('components.dropdown-button', [
                                        'id' => 'dropdownMoreTableTelegram' . $group->id,
                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                        'custom' => 'text-xl',
                                    ])
                                        <ul>
                                            @if($group->isActive)
                                                <li>
                                                    <a class="flex activeBtn items-center rounded-lg px-3 py-2 hover:bg-neutral-100" href="{{route('dashboard.telegram.show', $group->id)}}">Detalhes</a>
                                                </li>
                                            @else
                                                <li>
                                                    <button
                                                            onclick="startRequesting('{{ $group->id }}')"
                                                            data-data='{{ json_encode($group) }}'
                                                            data-url="{{ route('dashboard.telegram.update', $group->id) }}"
                                                            class="flex w-full activeBtn items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                            data-modal-target="activeTelegramGroupModal"
                                                            data-modal-toggle="activeTelegramGroupModal"
                                                            type="button">
                                                        Ativar
                                                    </button>
                                                </li>
                                            @endif
                                            <li>
                                                <button
                                                        data-drawer-target="drawerTelegramGroups"
                                                        data-drawer-show="drawerTelegramGroups"
                                                        data-drawer-placement="right"
                                                        data-data="{{$group->toJson()}}"
                                                        data-url="{{ route('dashboard.telegram.update', $group->id) }}"
                                                        class="flex editTelegramGroups items-center rounded-lg px-3 py-2 hover:bg-neutral-100">
                                                    Editar
                                                </button>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('dashboard.telegram.destroy', $group->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" onclick="if (confirm('Tem certeza?')) this.closest('form').submit();" class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100" title="Solicitar estorno">
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

        </div>

    </div>
@endsection

@push('floating')

    @component('components.modal', [
    'id' => 'activeTelegramGroupModal',
    'title' => 'Ativar grupo',
])
        <div class="text-center space-y-4">
            <p>Cole no chat do seu grupo privado:</p>

            <div class="inline-flex items-center bg-gray-100 px-[100px] rounded gap-3 px-4 py-2">
                {{-- Ícone pulsando --}}
                <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>

                @include('components.icon', [
                    'icon' => 'content_copy',
                    'custom' => 'text-xl text-gray-400',
                ])

                <span
                        class="copyClipboard group relative flex w-fit cursor-pointer items-center gap-2"
                        data-clipboard-text=""
                >
                {{ config('services.telegram.bot_username') }} <span class="code"></span>
                <span class="absolute -right-16 hidden rounded-md bg-gray-200 px-2 py-1 text-xs font-semibold group-hover:block">Copiar</span>
            </span>
            </div>
        </div>
    @endcomponent

    @component('components.drawer', [
        'id' => 'drawerTelegramGroups',
        'title' => 'Adicionar grupo',
        'custom' => 'max-w-xl',
    ])
        <form
                action="{{ route('dashboard.telegram.store') }}"
                method="POST"
                class="space-y-6"
                id="addNewActionForm">
            @csrf
            @method('POST')
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <label for="productSelect">Selecionar produto</label>
                    <select
                            name="product_id"
                            id="productSelect"
                            onchange="setProductName('productSelect')"
                            required>
                        <option value="">Selecione um produto</option>

                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <div class=" feedback-message">Selecione pelo menos um produto
                    </div>
                </div>
                <div class="col-span-12">
                    <label for="nameAction">Nome</label>
                    <input
                            placeholder="Nome da ação"
                            required
                            id="nameAction"
                            name="name"
                            type="text">
                </div>
                <input type="hidden" name="nameProduct" id="nameProduct">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="button button-primary h-12 rounded-full">
                    Salvar
                </button>
            </div>
        </form>
    @endcomponent
@endpush

@push('custom-script')
    <script>
        let intervalId = null;

        function startRequesting(groupId) {
            intervalId = setInterval(() => {
                fetch('/api/v1/public/telegram/is-group-active/'+groupId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.is_active) {
                            window.location = '/dashboard/telegram/' + groupId;
                        }
                    })
                    .catch(error => {});
            }, 2000);
        }

        $(document).on('click', '.activeBtn', function() {
            const data = $(this).data('data');
            const modal = $('#activeTelegramGroupModal');
            modal.find('.copyClipboard').attr('data-clipboard-text', "{{config('services.telegram.bot_username')}} " + data.code);
            modal.find('.code').text(data.code);
        });

        $(document).on('click', '.editTelegramGroups', function() {
            const data = $(this).data('data');
            const url = $(this).data('url');
            const drawer = document.getElementById('drawerTelegramGroups');
            console.log(data)


            drawer.querySelector('form').setAttribute('action', url);
            drawer.querySelector('input[name=_method]').value = "put";

            drawer.querySelector('.button').innerHTML = "Salvar";
            drawer.querySelector('.titleDrawer').innerHTML = 'Editar grupo';
            drawer.querySelector('select[name=product_id]').value = data.product_id;
            drawer.querySelector('input[name=name]').value = data.name;
        });

    </script>

    <script>
        window.addEventListener('load', function() {
            let modal = window.FlowbiteInstances.getInstance('Modal', 'activeTelegramGroupModal')

            modal.updateOnHide(function() {
                if (intervalId !== null) {
                    clearInterval(intervalId);
                    intervalId = null;
                }
            });
        })
    </script>

    <script>
        document.addEventListener("click", function(event) {
            const button = event.target.closest(".copyClipboard");
            if (!button) return;

            const clipboardText = button.getAttribute("data-clipboard-text");

            if (!navigator.clipboard) {
                notyf.error("Seu navegador não suporta copiar para a área de transferência!");
                return;
            }

            if (!clipboardText) {
                notyf.error("Nenhum texto encontrado para copiar!");
                return;
            }

            navigator.clipboard.writeText(clipboardText)
                .then(() => notyf.success("Copiado com sucesso!"))
                .catch(() => notyf.error("Erro ao tentar copiar!"));
        });
    </script>
@endpush
