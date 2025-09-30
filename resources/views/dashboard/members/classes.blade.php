@extends('layouts.members')

@section('content')
    <div class="space-y-10">
        <h3>Turmas</h3>

        <div class="flex items-center gap-6">

            <form class="w-full flex-1" action="{{ route('dashboard.members.classes', ['courseId' => $course['id']]) }}"
                method="GET">
                @csrf
                <div class="grid grid-cols-12 gap-6">

                    <div class="col-span-12">

                        <div class="append">

                            <input type="text" id="" name="name" value=""
                                placeholder="Pesquisar por nome da turma" />

                            <button class="append-item-right w-12" type="button">
                                @include('components.icon', ['icon' => 'search'])
                            </button>

                        </div>

                    </div>

                </div>

            </form>
            @if ($course['status'] == 'Ativo')
                <button class="button button-light h-12 gap-1 rounded-full bg-neutral-200"
                    data-modal-target="add-classes-modal" data-modal-toggle="add-classes-modal" type="button">
                    @include('components.icon', [
                        'icon' => 'add',
                        'type' => 'fill',
                        'custom' => 'text-xl',
                    ])
                    Adicionar turma
                </button>
            @endif
        </div>

        <div class="overflow-x-scroll md:overflow-visible">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Alunos</th>
                        <th>Oferta</th>
                        <th>Progresso</th>
                        <th>Turma padrão</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($classes))
                        <tr>
                            <td colspan="5" class="text-center py-6 text-neutral-500">
                                Você ainda não tem turmas nesse curso.
                            </td>
                        </tr>
                    @else
                        @foreach ($classes as $class)
                            <tr>
                                <td>{{ $class['name'] }}</td>
                                <td>{{ $class['totalMembers'] }}</td>
                                <td>{{ $class['product_name'] }}</td>
                                <td>{{ $class['progress'] }}
                                    <span class="text-base">%</span>
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
                                <td class="text-end">
                                    @component('components.dropdown-button', [
                                        'id' => 'dropdownMoreTableStudents' . $class['id'],
                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                        'custom' => 'text-xl',
                                    ])
                                        <ul>
                                            <li>
                                                <button class="flex items-center ml-2 rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                    title="Editar" data-modal-target="edit-classes-modal"
                                                    data-modal-toggle="edit-classes-modal" data-id="{{ $class['id'] }}"
                                                    data-name="{{ $class['name'] }}"
                                                    data-default="{{ $class['default'] ? 'true' : 'false' }}" type="button"
                                                    data-class-id="{{ $class['id'] }}">
                                                    @include('components.icon', [
                                                        'icon' => 'edit',
                                                        'custom' => 'text-lg font-semibold px-2',
                                                    ])
                                                    Editar
                                                </button>
                                            </li>
                                        </ul>
                                    @endcomponent
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>


    <div class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0"
        id="add-classes-modal" aria-hidden="true">
        <div class="relative max-h-full w-full max-w-xl p-4">
            <div class="w-full space-y-4 rounded-[24px] bg-white">

                <div class="flex items-center justify-between px-6 pt-4">

                    <h3 class="text-base font-medium">Editar turma</h3>
                    <button class="flex h-8 w-8 items-center justify-center rounded-md hover:bg-neutral-100"
                        data-modal-hide="add-classes-modal" type="button">
                        @include('components.icon', [
                            'icon' => 'close',
                            'custom' => 'text-xl',
                        ])
                    </button>

                </div>

                <form class="space-y-6 px-6 pb-6"
                    action="{{ route('dashboard.members.createClass', ['courseId' => $course['id']]) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">

                        <div class="col-span-12">
                            <label for="">Nome da turma</label>
                            <input name="name" type="text">
                        </div>

                        <div class="col-span-12">
                            @component('components.toggle', [
                                'id' => 'setClassDefault',
                                'contentEmpty' => 'true',
                                'label' => 'Definir essa turma como padrão',
                                'name' => 'default',
                            ])
                            @endcomponent
                        </div>

                        <div class="col-span-12">

                            <div class="overflow-x-scroll md:overflow-visible">
                                <table class="table w-full">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Oferta</th>
                                            <th>Preço</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productsAvailable as $productAvailable)
                                            <tr>
                                                <td>@include('components.toggle', [
                                                    'id' => 'addOffer' . $productAvailable->id,
                                                    'type' => 'radio',
                                                    'contentEmpty' => true,
                                                    'name' => 'offer',
                                                    'value' => $productAvailable->id,
                                                ])</td>
                                                <td>{{ $productAvailable->name }}</td>
                                                <td>{{ Number::currency($productAvailable->price, 'BRL', 'pt-br') }} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>

                    <button class="button button-primary ml-auto h-12 rounded-full" type="submit">
                        Adicionar turma
                    </button>

                </form>

            </div>
        </div>
    </div>


    <div class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0"
        id="edit-classes-modal" aria-hidden="true">
        <div class="relative max-h-full w-full max-w-xl p-4">
            <div class="w-full space-y-4 rounded-[24px] bg-white">

                <div class="flex items-center justify-between px-6 pt-4">

                    <h3 class="text-base font-medium">Editar turma</h3>
                    <button class="flex h-8 w-8 items-center justify-center rounded-md hover:bg-neutral-100"
                        data-modal-hide="edit-classes-modal" type="button">
                        @include('components.icon', [
                            'icon' => 'close',
                            'custom' => 'text-xl',
                        ])
                    </button>

                </div>

                <form class="space-y-6 px-6 pb-6" action="" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-12 gap-4">

                        <div class="col-span-12">
                            <label for="">Nome da turma</label>
                            <input name="name" type="text">
                        </div>

                        <div id="edit-class-default-toggle" class="col-span-12">
                            @component('components.toggle', [
                                'id' => 'setClassDefaultEdit',
                                'contentEmpty' => 'true',
                                'label' => 'Definir essa turma como padrão',
                                'name' => 'default',
                            ])
                            @endcomponent
                        </div>
                    </div>

                    <button class="button button-primary ml-auto h-12 rounded-full" type="submit">
                        Editar turma
                    </button>

                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editButtons = document.querySelectorAll('[data-modal-target="edit-classes-modal"]');

            editButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById('edit-classes-modal');

                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const isDefault = button.getAttribute('data-default') === 'true';

                    modal.querySelector('input[name="name"]').value = name;

                    const defaultToggleWrapper = modal.querySelector('#edit-class-default-toggle');
                    const defaultToggleInput = modal.querySelector('input[name="default"]');

                    if (defaultToggleInput) {
                        defaultToggleInput.checked = isDefault;
                    }

                    if (isDefault && defaultToggleWrapper) {
                        defaultToggleWrapper.classList.add('hidden');
                    } else if (defaultToggleWrapper) {
                        defaultToggleWrapper.classList.remove('hidden');
                    }

                    let idInput = modal.querySelector('input[name="class_id"]');
                    if (!idInput) {
                        idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = 'class_id';
                        modal.querySelector('form').appendChild(idInput);
                    }
                    idInput.value = id;

                    const form = modal.querySelector('form');
                    const courseId = '{{ $course['id'] }}';
                    form.action = `/dashboard/courses/${courseId}/class/${id}`;
                });
            });
        });
    </script>
    {{ $pagination->links() }}
@endsection
