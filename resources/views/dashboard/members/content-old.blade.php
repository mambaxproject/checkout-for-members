@extends('layouts.members')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>{{ $course['name'] }}</h1>

        <div class="space-y-4 md:space-y-10">

            <div class="flex items-center justify-between">

                <h3>Conteúdo do curso</h3>

                <a href="{{ route('dashboard.members.addModule', ['courseId' => $course['id']]) }}"
                    class="button button-primary h-12 gap-1 rounded-full" type="button">
                    @include('components.icon', [
                        'icon' => 'add',
                        'custom' => 'text-xl text-white',
                    ])
                    <span class="text-sm font-medium">Adicionar módulo</span>
                </a>

            </div>

            @if (array_key_exists('Modules', $course))
                <div class="space-y-3">
                    <div class="space-y-3" id="accordion-collapse" data-accordion="collapse">
                        @foreach ($course['Modules'] as $module)
                            @component('components.card')
                                {{-- Accordion button --}}
                                <div class="group p-6 md:p-8">
                                    <div class="flex items-center gap-4">

                                        <div class="group flex cursor-pointer items-center gap-2 aria-expanded:bg-transparent"
                                            data-accordion-target="#accordion-collapse-{{ $loop->iteration }}"
                                            aria-expanded="{{ $loop->iteration === 1 ? 'true' : 'false' }}">

                                            @include('components.icon', [
                                                'icon' => 'stat_minus_1',
                                                'custom' => 'text-xl font-semibold group-aria-expanded:rotate-180',
                                            ])
                                            <h3 class="text-base font-medium">{{ $module['name'] }}</h3>

                                        </div>

                                        <div
                                            class="{{ \App\Enums\MemberStatusEnum::getClassBackground(strtolower($module['status'])) }} rounded-full px-3 py-1.5 text-[10px] font-semibold uppercase">
                                            {{ $module['status'] }}
                                        </div>

                                        <div class="ml-auto rounded-full bg-neutral-100 px-3 py-1.5 text-xs">
                                            {{ count($module['Lessons'] ?? []) }} aulas
                                        </div>

                                        <div class="flex items-center gap-px">

                                            <a class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-neutral-700 active:bg-neutral-200"
                                                href="{{ route('dashboard.members.editModule', ['courseId' => $course['id'], 'moduleId' => $module['id']]) }}">
                                                @include('components.icon', [
                                                    'icon' => 'edit',
                                                    'custom' => 'text-lg font-semibold',
                                                ])
                                            </a>
                                            @if ($module['status'] == 'Ativo')
                                                <form method="POST"
                                                    action="{{ route('dashboard.members.deleteModule', ['moduleId' => $module['id']]) }}"
                                                    onsubmit="return confirm('Deseja realmente desativar este módulo? As aulas associadas também serão desativadas.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-danger-500 active:bg-neutral-200">
                                                        @include('components.icon', [
                                                            'icon' => 'delete',
                                                            'custom' => 'text-lg font-semibold',
                                                        ])
                                                    </button>
                                                </form>
                                            @elseif($module['status'] == 'Desativado')
                                                <form method="POST"
                                                    action="{{ route('dashboard.members.reactivateModule', ['moduleId' => $module['id']]) }}"
                                                    onsubmit="return confirm('Deseja reativar este módulo? Ele ficará disponível novamente.');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-green-600 active:bg-neutral-200">
                                                        @include('components.icon', [
                                                            'icon' => 'refresh',
                                                            'custom' => 'text-lg font-semibold',
                                                        ])
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                {{-- Accordion body --}}
                                <div class="{{ $loop->iteration === 1 ? '' : 'hidden' }} px-4 pb-4"
                                    id="accordion-collapse-{{ $loop->iteration }}">

                                    <div class="rounded-xl bg-neutral-50 p-6 md:p-8">

                                        <div class="space-y-6">

                                            <div class="flex items-center justify-between px-2">

                                                <h4>Aulas</h4>

                                                <div class="flex items-center gap-2">

                                                    <a class="button button-outline-light h-12 gap-1 rounded-full"
                                                        title="Adicionar aula de vídeo"
                                                        href="{{ route('dashboard.members.addLesson', ['courseId' => $course['id'], 'moduleId' => $module['id']]) }}">
                                                        @include('components.icon', [
                                                            'icon' => 'subscriptions',
                                                            'custom' => 'text-lg',
                                                        ])
                                                        Adicionar vídeo
                                                    </a>

                                                    <a class="button button-outline-light h-12 gap-1 rounded-full"
                                                        title="Adicionar aula de quiz"
                                                        href="{{ route('dashboard.members.addQuiz', ['courseId' => $course['id'], 'moduleId' => $module['id']]) }}">
                                                        @include('components.icon', [
                                                            'icon' => 'checklist_rtl',
                                                            'custom' => 'text-lg',
                                                        ])
                                                        Adicionar quiz
                                                    </a>

                                                </div>

                                            </div>

                                            @if (array_key_exists('Lessons', $module))
                                                <div class="space-y-3">
                                                    @foreach ($module['Lessons'] as $lesson)
                                                        <div
                                                            class="flex items-center gap-4 rounded-xl border border-neutral-200 p-4">

                                                            @if ($lesson['type'] === 'video')
                                                                @include('components.icon', [
                                                                    'icon' => 'subscriptions',
                                                                    'custom' => 'text-lg',
                                                                ])
                                                            @endif

                                                            @if ($lesson['type'] === 'quiz')
                                                                @include('components.icon', [
                                                                    'icon' => 'checklist_rtl',
                                                                    'custom' => 'text-lg',
                                                                ])
                                                            @endif

                                                            <h4>{{ $lesson['name'] }}</h4>

                                                            <div
                                                                class="{{ \App\Enums\MemberStatusEnum::getClassBackground(strtolower($lesson['status'])) }} rounded-full px-3 py-1.5 text-[10px] font-semibold uppercase">
                                                                {{ $lesson['status'] }}
                                                            </div>

                                                            <div class="ml-auto flex items-center gap-px">

                                                                <a class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-neutral-700 active:bg-neutral-200"
                                                                    href="{{ route('dashboard.members.editLesson' . ucfirst($lesson['type']), ['courseId' => $course['id'], 'lessonId' => $lesson['id']]) }}">
                                                                    @include('components.icon', [
                                                                        'icon' => 'edit',
                                                                        'custom' => 'text-lg font-semibold',
                                                                    ])
                                                                </a>
                                                                @if ($lesson['status'] == 'Ativo')
                                                                    <form method="POST"
                                                                        action="{{ route('dashboard.members.deleteLesson', ['courseId' => $course['id'], 'lessonId' => $lesson['id']]) }}"
                                                                        onsubmit="return confirm('Tem certeza que deseja desativar esta aula? Esta ação a tornará inacessível para os alunos.');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-danger-500 active:bg-neutral-200">
                                                                            @include('components.icon', [
                                                                                'icon' => 'delete',
                                                                                'custom' =>
                                                                                    'text-lg font-semibold',
                                                                            ])
                                                                        </button>
                                                                    </form>
                                                                @elseif($module['status'] == 'Ativo' && $lesson['status'] == 'Desativado')
                                                                    <form method="POST"
                                                                        action="{{ route('dashboard.members.reactivateLesson', ['courseId' => $course['id'], 'lessonId' => $lesson['id']]) }}"
                                                                        onsubmit="return confirm('Deseja reativar este módulo? Ele ficará disponível novamente.');">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button type="submit"
                                                                            class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-green-600 active:bg-neutral-200">
                                                                            @include('components.icon', [
                                                                                'icon' => 'refresh',
                                                                                'custom' =>
                                                                                    'text-lg font-semibold',
                                                                            ])
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="p-4">
                                                    <p class="text-center text-neutral-400">Não tem aulas adicionadas</p>
                                                </div>
                                            @endif

                                        </div>

                                    </div>

                                </div>
                            @endcomponent
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection
