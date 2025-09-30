@extends('layouts.members')

@section('content')
    <div class="space-y-10">
        <h3>Alunos</h3>

        <div class="grid grid-cols-3 gap-6">

            <div class="col-span-1">
                <div class="h-full rounded-xl bg-white p-6">

                    <p class="text-2xl font-bold">{{ $members['totalMembers'] }}</p>
                    <h3 class="text-base font-medium">Total de alunos Ativos</h3>

                </div>
            </div>

            <div class="col-span-1">
                <div class="h-full rounded-xl bg-white p-6">

                    <p class="text-2xl font-bold">
                        {{ $members['averageProgress'] }}
                        <span class="text-base">%</span>
                    </p>

                    <h3 class="text-base font-medium">Progresso médio</h3>
                    <p class="text-xs text-neutral-400">Média dos usuários</p>

                </div>
            </div>

            <div class="col-span-1">
                <div class="h-full rounded-xl bg-white p-6">

                    <p class="text-2xl font-bold">
                        {{ $members['totalProgress'] }}
                        <span class="text-base">%</span>
                    </p>

                    <h3 class="text-base font-medium">Conclusão</h3>
                    <p class="text-xs text-neutral-400">Concluíram o curso</p>

                </div>
            </div>

        </div>

        <div class="flex items-center gap-6">

            <form class="w-full flex-1" action="{{ route('dashboard.members.students', ['courseId' => $course['id']]) }}"
                method="GET">
                @csrf
                <div class="grid grid-cols-12 gap-6">

                    <div class="col-span-12">

                        <div class="append">

                            <input type="text" name="filters" value="{{ request('filters') }}"
                                placeholder="Pesquisar por nome ou e-mail" />

                            <button class="append-item-right w-12 mr-3" type="button">
                                @if (request('filters'))
                                    <a href="{{ route('dashboard.members.students', ['courseId' => $course['id']]) }}"
                                        class="h-full flex items-center justify-center mr-3 hover:text-red-500">
                                        @include('components.icon', [
                                            'icon' => 'close',
                                            'custom' => 'text-2xl',
                                        ])
                                    </a>
                                @endif
                                @include('components.icon', ['icon' => 'search', 'custom' => 'mr-2'])
                            </button>
                        </div>
                    </div>
                </div>

            </form>
            @if ($course['status'] == 'Ativo')
                <button class="button button-light h-12 gap-1 rounded-full bg-neutral-200"
                    data-modal-target="add-student-modal" data-modal-toggle="add-student-modal" type="button">
                    @include('components.icon', [
                        'icon' => 'add',
                        'type' => 'fill',
                        'custom' => 'text-xl',
                    ])
                    Adicionar aluno
                </button>
            @endif
            <button class="button button-outline-primary h-12 w-full gap-1 md:w-auto"
                data-drawer-target="drawerFilterStudents" data-drawer-show="drawerFilterStudents"
                data-drawer-placement="right" type="button">
                @include('components.icon', [
                    'icon' => 'filter_alt',
                    'type' => 'fill',
                    'custom' => 'text-xl',
                ])
                Filtros de pesquisa
            </button>

        </div>
        <form class="w-full flex-1" action="{{ route('dashboard.members.students', ['courseId' => $course['id']]) }}"
            method="GET">
            @csrf
            <div class="overflow-x-scroll md:overflow-visible">
                <input type="hidden" name="tab" id="selected-tab" value="active">
                <nav class="no-scrollbar flex items-center overflow-x-auto border-b border-neutral-300 mb-3"
                    data-tabs-toggle="#page-tab-content">
                    <button
                        class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                        data-tabs-target="#tab-students-actives" data-tab-value="active"
                        aria-selected="{{ $tab == 'active' ? 'true' : 'false' }}" role="tab" type="submit">
                        Ativos
                    </button>
                    <button
                        class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                        data-tabs-target="#tab-students-inactive" data-tab-value="inactive"
                        aria-selected="{{ $tab == 'inactive' ? 'true' : 'false' }}" role="tab" type="submit">
                        Desativados
                    </button>
                    <button
                        class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                        data-tabs-target="#tab-students-moderators" data-tab-value="moderators"
                        aria-selected="{{ $tab == 'moderators' ? 'true' : 'false' }}" role="tab" type="submit">
                        Moderadores
                    </button>
                </nav>
        </form>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Comprador</th>
                    <th>E-mail</th>
                    <th>Data da compra</th>
                    <th>Progresso</th>
                    <th>Turma</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if (empty($students))
                    <tr>
                        <td colspan="5" class="text-center py-6 text-neutral-500">
                            Você ainda não tem alunos nesse curso.
                        </td>
                    </tr>
                @else
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student['name'] }}</td>
                            <td>{{ $student['email'] }}</td>
                            <td>{{ date('d/m/Y', strtotime($student['createdAt'])) }}</td>
                            <td>{{ $student['progress'] }}%</td>
                            <td>{{ !$student['moderator'] ? $student['nameClass'] : '-' }}</td>
                            <td class="text-end">
                                @component('components.dropdown-button', [
                                    'id' => 'dropdownMoreTableStudents' . $student['id'],
                                    'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                    'custom' => 'text-xl',
                                ])
                                    <ul>
                                        @if ($student['status'] == 'Ativo' && !$student['moderator'])
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('dashboard.members.moderator', ['courseId' => $course['id']]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="moderator" value="1">
                                                    <input type="hidden" name="email" value="{{ $student['email'] }}">
                                                    <button type="submit"
                                                        class="flex items-center rounded-lg px-3 py-2 text-success-600 hover:bg-danger-50 w-full text-left">
                                                        Tornar moderador
                                                    </button>
                                                </form>
                                            </li>
                                            <hr class="my-1 border-neutral-100">
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('dashboard.members.changeMemberStatus', ['courseId' => $course['id']]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="INACTIVE">
                                                    <input type="hidden" name="email" value="{{ $student['email'] }}">
                                                    <button type="submit"
                                                        class="flex items-center rounded-lg px-3 py-2 text-danger-500 hover:bg-danger-50 w-full text-left">
                                                        Desativar
                                                    </button>
                                                </form>
                                            </li>
                                        @elseif ($student['status'] == 'Desativado')
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('dashboard.members.changeMemberStatus', ['courseId' => $course['id']]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="ACTIVE">
                                                    <input type="hidden" name="email" value="{{ $student['email'] }}">
                                                    <button type="submit"
                                                        class="flex items-center rounded-lg px-3 py-2 text-success-600 hover:bg-danger-50 w-full text-left">
                                                        Reativar
                                                    </button>
                                                </form>
                                            </li>
                                        @endif

                                        @if ($student['status'] == 'Ativo' && $student['moderator'])
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('dashboard.members.moderator', ['courseId' => $course['id']]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="moderator" value="0">
                                                    <input type="hidden" name="email" value="{{ $student['email'] }}">
                                                    <button type="submit"
                                                        class="flex items-center rounded-lg px-3 py-2 text-danger-500 hover:bg-danger-50 w-full text-left">
                                                        Remover moderação
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                @endcomponent

                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    {{ $pagination->links() }}
    </div>
    <div class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0"
        id="add-student-modal" aria-hidden="true">
        <div class="relative max-h-full w-full max-w-xl p-4">
            <div class="w-full space-y-4 rounded-[24px] bg-white">

                <div class="flex items-center justify-between px-6 pt-4">

                    <h3 class="text-base font-medium">Adicionar aluno</h3>
                    <button class="flex h-8 w-8 items-center justify-center rounded-md hover:bg-neutral-100"
                        data-modal-hide="add-student-modal" type="button">
                        @include('components.icon', [
                            'icon' => 'close',
                            'custom' => 'text-xl',
                        ])
                    </button>

                </div>

                <form class="space-y-6 px-6 pb-6"
                    action="{{ route('dashboard.members.addStudent', ['courseId' => $course['id']]) }}" method="POST">
                    @csrf
                    {{ csrf_field() }}
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label id="name" class="mb-1">Nome do aluno</label>
                            <input class="mt-2" type="text" id="name" name="name"
                                placeholder="Digite o nome do aluno" value="{{ old('name') }}" required />
                        </div>
                        <div class="col-span-12">
                            <label for="">E-mail</label>
                            <input class="mt-2" type="email" id="email" name="email"
                                placeholder="Digite o email do aluno" value="{{ old('email') }}" required />
                        </div>
                        <div class="col-span-12">
                            <label for="document">Documento cpf/cnpj</label>
                            <input type="text" value="{{ old('document') }}" class="is-invalid" inputmode="numeric"
                                placeholder="000.000.000-00" oninput="setCpfCnpjMask(this)" name="document" required />
                        </div>
                        <div class="col-span-12">
                            <label for="turma">Turma</label>
                            <select name="offer" id="turma" class="form-select">
                                @foreach ($classes as $class)
                                    <option value="{{ $class['offer'] ?? '' }}">{{ $class['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-neutral-500">
                            Você pode adicionar um aluno manualmente ou enviar um arquivo <strong>.csv</strong> ou
                            <strong>.xls</strong> com os campos <strong>nome</strong>, <strong>email</strong> e
                            <strong>documento</strong>.
                        </p>

                        <div class="flex justify-between gap-4 pt-2">
                            <button class="button button-primary h-12 flex-1 gap-1 rounded-full"
                                data-modal-target="modalAddStudents" data-modal-toggle="modalAddStudents" type="button">
                                @include('components.icon', [
                                    'icon' => 'upload',
                                    'custom' => 'text-xl',
                                ])
                                Enviar arquivo
                            </button>

                            <button class="button button-primary h-12 flex-1 rounded-full" type="submit">
                                @include('components.icon', [
                                    'icon' => 'add',
                                    'custom' => 'text-xl',
                                ])
                                Adicionar manualmente
                            </button>
                        </div>
                </form>

            </div>
        </div>
    </div>
    @push('floating')
        <div class="fixed left-0 right-0 top-0 z-50 hidden items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0"
            @component('components.modal', [
                'id' => 'modalAddStudents',
                'title' => 'Adicionar alunos',
            ])
                <form action="{{ route('dashboard.members.addManyStudents', ['courseId' => $course['id']]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <div class="rounded-xl bg-neutral-100 p-4 text-sm text-neutral-700">
                                Para adicionar múltiplos alunos de uma vez, envie um arquivo no formato <strong>.csv</strong> ou
                                <strong>.xls</strong>
                                com os campos obrigatórios: <strong>nome</strong>, <strong>email</strong> e
                                <strong>documento</strong> de até <strong>2MB</strong>.
                            </div>
                            
                        </div>
                        <div class="col-span-12">
                            <div class="col-span-12 mt-5">
                                <label for="archive"> Arquivo de alunos (.csv, .xls)*</label>
                                @component('components.dropzone-excel', [
                                    'id' => 'archive',
                                    'name' => 'archive',
                                    'required' => true,
                                ])
                                @endcomponent
            <p class="mt-1 text-sm text-neutral-400">Adicione o arquivos com os dados corretos</p>
        </div>
        <div class="col-span-12 mt-4">
            <label for="offer">Turma</label>
            <select name="offer" id="offer" class="form-select">
                @foreach ($classes as $class)
                    <option value="{{ $class['offer'] }}">{{ $class['name'] }}</option>
                @endforeach
            </select>
        </div>
        </div>
        </div>
        <div class="flex justify-between gap-4 pt-4">
            <a class="button button-primary h-12 gap-1 rounded-full"
                href="{{ asset('storage/examples/alunos-exemplo.csv') }}" download>
                @include('components.icon', [
                    'icon' => 'download',
                    'custom' => 'text-xl',
                ])
                Baixar modelo (.csv)
            </a>

            <button class="button button-primary h-12 gap-1 rounded-full" type="submit">
                Adicionar
                @include('components.icon', [
                    'icon' => 'arrow_forward',
                    'custom' => 'text-xl',
                ])
            </button>
        </div>
        </form>
    @endcomponent
@endpush
@push('floating')
    @component('components.drawer', [
        'id' => 'drawerFilterStudents',
        'title' => 'Filtro de pesquisa',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="" method="GET">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="classId">Turma</label>
                    <select id="classId" name="classId">
                        <option value="">Selecione a turma</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class['id'] }}" @selected(request()->input('classId') == $class['id'])>
                                {{ $class['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Pesquisar
            </button>

        </form>
    @endcomponent
@endpush
</div>
<script>
    document.querySelectorAll('[data-tab-value]').forEach(button => {
        button.addEventListener('click', function(e) {
            document.getElementById('selected-tab').value = this.getAttribute('data-tab-value');
        });
    });
</script>
<script src="{{ asset('js/dashboard/validation/cpfCnpj.js') }}"></script>
<script src="{{ asset('js/dashboard/dropzone-config.js') }}"></script>
@endsection
