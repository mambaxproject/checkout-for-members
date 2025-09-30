@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">

            <h1>Colaboradores</h1>

            <button
                class="button button-primary h-12 gap-1 rounded-full"
                data-drawer-target="drawerAddCollaborators"
                data-drawer-show="drawerAddCollaborators"
                data-drawer-placement="right"
                type="button"
            >
                @include('components.icon', [
                    'icon' => 'add',
                    'custom' => 'text-xl',
                ])
                Adicionar colaborador
            </button>

        </div>

        <div class="">

            <form
                class="mb-6"
                action=""
                method=""
            >

                <div class="grid grid-cols-12 gap-2 md:gap-4">

                    <div class="col-span-12 md:col-span-4">

                        <div class="append">

                            <select class="pl-24">
                                <option value="">Aprovados</option>
                            </select>
                            <div class="append-item-left px-4 font-semibold">Filtrar por:</div>

                        </div>

                    </div>

                    <div class="col-span-12 md:col-span-8">

                        <div class="append">

                            <input
                                placeholder="Pesquisar"
                                type="text"
                            />
                            <button
                                class="append-item-right w-12"
                                type="button"
                            >
                                @include('components.icon', ['icon' => 'search'])
                            </button>

                        </div>

                    </div>

                </div>

            </form>

            @component('components.card', ['custom' => 'overflow-hidden'])
                <div class="overflow-x-scroll md:overflow-visible">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Nome do colaborador</th>
                                <th>E-mail</th>
                                <th>Data do convite</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 8; $i++)
                                <tr>
                                    <td>Nome do colaborador</td>
                                    <td>email@dominio.com</td>
                                    <td>25/03/2024</td>
                                    <td>
                                        <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                            @include('components.icon', [
                                                'icon' => 'circle',
                                                'type' => 'fill',
                                                'custom' => 'text-xs text-primary',
                                            ])
                                            Aprovado
                                        </div>
                                    </td>
                                    <td class="text-end">

                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreTableParticipations' . $i,
                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>
                                                <li>
                                                    <a
                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                        href="#"
                                                    >
                                                        Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a
                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                        href="#"
                                                    >
                                                        Acessar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a
                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                        href="#"
                                                    >
                                                        Remover
                                                    </a>
                                                </li>
                                            </ul>
                                        @endcomponent

                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            @endcomponent

            @include('components.pagination', [
                'currentPage' => '1',
                'totalPages' => '10',
                'totalItems' => '300',
            ])

        </div>

    </div>
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerAddCollaborators',
        'title' => 'Adicionar colaborador',
        'custom' => 'max-w-xl',
    ])
    @endcomponent
@endpush
