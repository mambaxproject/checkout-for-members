@extends('layouts.members')

@section('content')
    <div class="space-y-10">

        <div class="flex items-center justify-between">

            <div class="">

                <h3>Turmas</h3>
                <p class="text-sm text-neutral-400">Gerencie as turmas e configure a liberação de conteúdo</p>

            </div>

            <a class="button button-primary h-12 w-fit rounded-full"
                href="{{ route('dashboard.members.addClass', ['courseId' => $course['id']]) }}">
                Nova Turma
            </a>

        </div>

        <div class="space-y-6">

            <div class="grid grid-cols-2 gap-6 xl:grid-cols-4">

                <div class="col-span-1">
                    @component('components.card')
                        <div class="space-y-2 p-4">

                            <div class="flex items-center justify-between">

                                <h4 class="">Total de Turmas</h4>
                                @include('components.icon', [
                                    'icon' => 'group',
                                    'custom' => 'text-neutral-400',
                                ])

                            </div>

                            <p class="text-xl font-bold">{{ $dashboard['totalClasses'] }}</p>

                        </div>
                    @endcomponent
                </div>

                <div class="col-span-1">
                    @component('components.card')
                        <div class="space-y-2 p-4">

                            <div class="flex items-center justify-between">

                                <h4 class="">Turmas Ativas</h4>
                                @include('components.icon', [
                                    'icon' => 'check_circle',
                                    'custom' => 'text-neutral-400',
                                ])

                            </div>

                            <p class="text-xl font-bold">{{ $dashboard['totalClassesActive'] }}</p>

                        </div>
                    @endcomponent
                </div>

                <div class="col-span-1">
                    @component('components.card')
                        <div class="space-y-2 p-4">
                            <div class="flex items-center justify-between">
                                <h4 class="">Total de alunos</h4>
                                @include('components.icon', [
                                    'icon' => 'people',
                                    'custom' => 'text-neutral-400',
                                ])
                            </div>
                            <p class="text-xl font-bold">{{ $dashboard['totalMembers'] }}</p>
                        </div>
                    @endcomponent
                </div>

                <div class="col-span-1">
                    @component('components.card')
                        <div class="space-y-2 p-4">

                            <div class="flex items-center justify-between">

                                <h4 class="">Progresso Médio</h4>
                                @include('components.icon', [
                                    'icon' => 'trending_up',
                                    'custom' => 'text-neutral-400',
                                ])

                            </div>

                            <p class="text-xl font-bold">{{ $dashboard['averageProgess'] }}%</p>

                        </div>
                    @endcomponent
                </div>

            </div>
            <form class="w-full flex-1" action="{{ route('dashboard.members.classes', ['courseId' => $course['id']]) }}"
                method="GET">
                @csrf
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12">
                        <div class="append">
                            <input type="text" id="" name="name" value="{{ request('name') }}"
                                placeholder="Pesquisar por nome da turma" />
                            <button class="append-item-right w-12" type="submit">
                                @include('components.icon', ['icon' => 'search'])
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Alunos</th>
                            <th>Ofertas vinculadas</th>
                            <th>Progresso</th>
                            <th>Duração</th>
                            <th>Status</th>
                            <th>Turma padrão</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($classes as $class)
                            <tr>
                                <td>
                                    {{ $class['name'] }}
                                </td>
                                <td>
                                    {{ $class['totalMembers'] }}
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($class['products'] as $product)
                                            <span data-slot="badge"
                                                class="inline-flex items-center justify-center rounded-md border px-2 py-0.5 font-medium w-fit whitespace-nowrap shrink-0 [&>svg]:size-3 gap-1 [&>svg]:pointer-events-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden text-foreground [a&]:hover:bg-accent [a&]:hover:text-accent-foreground text-xs">
                                                {{ $product['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        {{ $class['progress'] }}%
                                    </div>
                                </td>
                                <td>
                                    <div class="flex">
                                        {{ $class['subscription'] ? 'Vitalício' : $class['detailDescription'] }}
                                    </div>
                                </td>
                                <td>
                                    <div
                                        class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">

                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' =>
                                                'text-xs ' .
                                                ($class['status'] ? 'text-primary' : 'text-danger-600'),
                                        ])
                                        {{ $class['status'] ? 'Ativo' : 'Desativado' }}


                                    </div>
                                </td>
                                <td>
                                    <div
                                        class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' =>
                                                'text-xs ' .
                                                ($class['default'] ? 'text-primary' : 'text-danger-600'),
                                        ])
                                        {{ $class['default'] ? 'Sim' : 'Não' }}
                                    </div>
                                </td>

                                <td class="text-right">
                                    @component('components.dropdown-button', [
                                        'id' => 'dropdownMoreTableStudents' . $class['id'],
                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                        'custom' => 'text-xl',
                                    ])
                                        <ul>
                                            <li>
                                                <a class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                    href="{{ route('dashboard.members.editClass', ['courseId' => $course['id'], 'classId' => $class['id']]) }}">
                                                    Editar Turma
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('dashboard.members.toggleClass', ['classId' => $class['id']]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status"
                                                        value="{{ $class['status'] ? 0 : 1 }}">

                                                    @if ($class['status'] && $class['totalMembers'] > 0)
                                                        <button type="button" disabled
                                                            class="w-full text-left flex items-center rounded-lg px-3 py-2 bg-neutral-200 text-neutral-400 cursor-not-allowed">
                                                            Desativar Turma (membros ativos)
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            class="w-full text-left flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100">
                                                            {{ $class['status'] ? 'Desativar Turma' : 'Ativar Turma' }}
                                                        </button>
                                                    @endif
                                                </form>
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
@endsection

@push('script')
@endpush
