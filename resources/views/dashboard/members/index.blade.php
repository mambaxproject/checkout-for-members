@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex justify-between w-full">
            <div class="px-2">
                <h1 class="text-xl font-semibold">Membros</h1>
            </div>

            <div class="px-2">
                <a href="{{ route('dashboard.members.redirectMembers') }}" target="_blank"
                    class="button button-primary h-10 rounded-full flex-[1.5] text-center" type="button">
                    @include('components.icon', [
                        'type' => 'fill',
                        'icon' => 'home',
                        'custom' => 'text-xl',
                    ])
                    <span class="whitespace-nowrap ml-2 text-sm group-aria-expanded:block">
                        Início da Área de Membros
                    </span>
                </a>
            </div>
        </div>
        <nav class="flex items-center border-b border-neutral-300" data-tabs-toggle="#page-tab-content">

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-courses" aria-selected="false" role="tab" type="button">
                Cursos
            </button>
        </nav>

        <div id="page-tab-content">

            <div class="hidden" id="tab-courses">

                <div class="mb-6 flex items-center gap-6">

                    <form class="flex-1" action="" method="">

                        <div class="grid grid-cols-12 gap-2 md:gap-6">

                            <div class="col-span-12">

                                <div class="append">
                                    <form action="{{ route('dashboard.members.index') }}" method="GET">
                                        <input placeholder="Pesquisar" name="name" type="text" />
                                        <button class="append-item-right w-12" type="button">
                                            @include('components.icon', ['icon' => 'search'])
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </form>
                    <button class="button button-outline-primary h-12 w-full gap-1 md:w-auto"
                        data-drawer-target="drawerFilterMembers" data-drawer-show="drawerFilterMembers"
                        data-drawer-placement="right" type="button">
                        @include('components.icon', [
                            'icon' => 'filter_alt',
                            'type' => 'fill',
                            'custom' => 'text-xl',
                        ])
                        Filtros de pesquisa
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($courses['data'] as $course)
                        @component('components.card')
                            <div class="col-span-1">

                                <div class="rounded-xl bg-white">

                                    <figure
                                        class="relative flex h-[240px] w-full items-center justify-center overflow-hidden rounded-t-xl bg-white">
                                        <div class="bg-success-200 absolute left-3 top-3 rounded-full px-2 py-1 text-[10px] font-semibold uppercase">
                                            {{ $course['typeCourse'] === 'course' ? 'Curso' : 'Formação' }}
                                        </div>

                                        <img class="h-full w-full object-cover" src="{{ $course['thumbnailUrl'] }}"
                                            alt="Curso" loading="lazy" />
                                    </figure>

                                    <div class="flex flex-col h-full space-y-4 p-4">

                                        <div class="space-y-2">

                                            <div class="flex items-center gap-1">
                                                <div class="rounded-full bg-neutral-100 px-4 py-1 text-xs font-medium">
                                                    {{ $course['nameCategory'] }}</div>

                                                <div
                                                    class="flex items-center justify-center gap-1 rounded-full bg-neutral-100 px-4 py-1 text-xs font-medium">
                                                    <span
                                                        class="-ml-0.5 block h-2 w-2 rounded-full {{ \App\Enums\MemberStatusEnum::getClassBackground(strtolower($course['status'])) }}"></span>
                                                    {{ $course['status'] }}
                                                </div>

                                            </div>

                                            <div class="space-y-1">
                                                <h4 class="font-semibold">{{ $course['name'] }}</h4>
                                                <h5 class="text-xs text-neutral-500 mt-2 mb-3 pb-2 pt-2"
                                                    style="word-break: break-word;">
                                                    <strong> Produto: </strong> {{ $course['productName'] }}
                                                </h5>
                                                <p class="text-xs text-neutral-500 line-clamp-3"
                                                    style="word-break: break-word;">
                                                    {{ $course['description'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex justify-between w-full mt-5">
                                            <a href="{{ route('dashboard.members.edit', ['courseId' => $course['id']]) }}"
                                                class="button button-light h-10 rounded-full" type="button" target="_blank">
                                                Editar
                                            </a>
                                            @if ($course['status'] == 'Ativo')
                                                <a href="{{ route('dashboard.members.redirectMembersCourse', ['courseId' => $course['id']]) }}"
                                                    target="_blank"
                                                    class="button button-light h-10 rounded-full flex-[1.5] text-center"
                                                    type="button">
                                                    @include('components.icon', [
                                                        'type' => 'fill',
                                                        'icon' => 'menu_book',
                                                        'custom' => 'text-xl',
                                                    ])
                                                    <span class="whitespace-nowrap ml-2 text-sm group-aria-expanded:block">
                                                        Acessar
                                                        {{ ($course['typeCourse'] ?? '') === 'course' ? 'curso' : 'formação' }}
                                                    </span>
                                                </a>
                                            @endif
                                            @if ($course['status'] == 'Pendente')
                                                <a href="{{ route('dashboard.members.redirectMembersCourse', ['courseId' => $course['id']]) }}"
                                                    target="_blank"
                                                    class="button button-light h-10 rounded-full flex-[1.5] text-center"
                                                    type="button">
                                                    @include('components.icon', [
                                                        'type' => 'fill',
                                                        'icon' => 'Visibility',
                                                        'custom' => 'text-xl',
                                                    ])
                                                    <span class="whitespace-nowrap ml-2 text-sm group-aria-expanded:block">
                                                        Ver Preview
                                                    </span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                            </div>
                        @endcomponent
                    @endforeach
                </div>
                {{ $pagination->links() }}
            </div>

        </div>

    </div>
@endsection
@push('floating')
    @component('components.drawer', [
        'id' => 'drawerFilterMembers',
        'title' => 'Pesquisar área de membros',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="{{ route('dashboard.members.index') }}" method="GET">

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" value="{{ request()->input('name') }}"
                        placeholder="Digite o nome" />
                </div>

                <div class="col-span-12">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Selecione o status</option>
                        @foreach (\App\Enums\MemberStatusEnum::getDescriptions() as $item)
                            <option value="{{ ucfirst($item['value']) }}" @selected(request()->input('status') == ucfirst($item['value']))>
                                {{ $item['description'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="categoryId">Categoria</label>
                    <select id="categoryId" name="categoryId">
                        <option value="">Selecione a categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}" @selected(request()->input('categoryId') == $category['id'])>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="createdAt">Data de cadastro</label>
                    <input type="date" id="createdAt" name="createdAt" value="{{ request()->input('createdAt') }}" />
                </div>
            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Pesquisar
            </button>

        </form>
    @endcomponent
@endpush
