@extends('layouts.members')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">
            <h1>{{ $course['name'] }}</h1>

            <a class="button button-primary h-12 gap-1 rounded-full" title="Adicionar modulo"
                href="{{ route('dashboard.members.addModule', ['courseId' => $course['id']]) }}">
                @include('components.icon', [
                    'icon' => 'add',
                    'custom' => 'text-xl text-white',
                ])
                <span class="text-sm font-medium">Adicionar modulo</span>
            </a>

        </div>

        <div class="space-y-4 md:space-y-10">

            <div class="space-y-3" id="accordion-collapse" data-accordion="collapse">
                @foreach ($course['Modules'] as $module)
                    @component('components.card')
                        <div class="group p-6 md:p-8">

                            <div class="flex items-center gap-4">

                                <div class="group flex cursor-pointer items-center gap-2 aria-expanded:bg-transparent"
                                    data-accordion-target="#accordion-collapse-{{ $module['id'] }}"
                                    aria-expanded="{{ (isset($openModuleId) && $openModuleId == $module['id']) || (!isset($openModuleId) && $loop->first) ? 'true' : 'false' }}">

                                    @include('components.icon', [
                                        'icon' => 'stat_minus_1',
                                        'custom' => 'text-xl font-semibold group-aria-expanded:rotate-180',
                                    ])

                                    <h3 class="text-base font-medium">{{ $module['name'] }}</h3>
                                    <div
                                        class="{{ \App\Enums\MemberStatusEnum::getClassBackground(strtolower($module['status'])) }} rounded-full px-3 py-1.5 text-[10px] font-semibold uppercase">
                                        {{ $module['status'] }}
                                    </div>

                                </div>

                                <div class="ml-auto rounded-full bg-neutral-100 px-3 py-1.5 text-xs">
                                    {{ count($module['Lessons']) }}
                                </div>

                                <div class="flex items-center gap-px">

                                    <a class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-neutral-700 active:bg-neutral-200"
                                        title="Editar modulo"
                                        href="{{ route('dashboard.members.editModule', ['courseId' => $course['id'], 'moduleId' => $module['id']]) }}">
                                        @include('components.icon', [
                                            'icon' => 'edit',
                                            'custom' => 'text-lg font-semibold',
                                        ])
                                    </a>
                                    @if ($module['status'] == 'Ativo')
                                        <form method="POST"
                                            action="{{ route('dashboard.members.deleteModule', ['moduleId' => $module['id']]) }}"
                                            onsubmit="return confirm('Deseja realmente desativar este modulo? AS aulas associadas também serão desativadas.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Desativar modulo"
                                                class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-danger-50 hover:text-danger-500 active:bg-neutral-200">
                                                @include('components.icon', [
                                                    'icon' => 'delete',
                                                    'custom' => 'text-lg font-semibold',
                                                ])
                                            </button>
                                        </form>
                                    @endif
                                    @if ($module['status'] == 'Desativado')
                                        <form method="POST"
                                            action="{{ route('dashboard.members.reactivateModule', ['moduleId' => $module['id']]) }}"
                                            onsubmit="return confirm('Deseja reativar este modulo? Ele ficará disponível novamente.');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" title="Reativar modulo"
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

                        <div class="{{ (isset($openModuleId) && $openModuleId == $module['id']) || (!isset($openModuleId) && $loop->first) ? '' : 'hidden' }} px-4 pb-4"
                            id="accordion-collapse-{{ $module['id'] }}">

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

                                    <div class="space-y-3">

                                        @foreach ($module['Lessons'] as $lesson)
                                            <div class="flex items-center gap-4 rounded-xl border border-neutral-200 p-4">

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
                                                    @if ($lesson['status'] != 'Processando upload')
                                                        <a class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-neutral-700 active:bg-neutral-200"
                                                            title="Editar aula curso"
                                                            href="{{ route('dashboard.members.editLesson' . ucfirst($lesson['type']), ['courseId' => $course['id'], 'lessonId' => $lesson['id']]) }}">
                                                            @include('components.icon', [
                                                                'icon' => 'edit',
                                                                'custom' => 'text-lg font-semibold',
                                                            ])
                                                        </a>
                                                    @endif
                                                    @if ($lesson['status'] == 'Ativo')
                                                        <form method="POST"
                                                            action="{{ route('dashboard.members.deleteLesson', ['lessonId' => $lesson['id']]) }}"
                                                            onsubmit="return confirm('Deseja realmente desativar esta aula?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" title="Desativar aula"
                                                                class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-danger-50 hover:text-danger-500 active:bg-neutral-200">
                                                                @include('components.icon', [
                                                                    'icon' => 'delete',
                                                                    'custom' => 'text-lg font-semibold',
                                                                ])
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($lesson['status'] == 'Desativado')
                                                        <form method="POST"
                                                            action="{{ route('dashboard.members.reactivateLesson', ['lessonId' => $lesson['id']]) }}"
                                                            onsubmit="return confirm('Deseja realmente ativar esta aula?');">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" title="Reativar modulo"
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
                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        </div>
                    @endcomponent
                @endforeach
            </div>

        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headerOffset = 100;

            @if (isset($openModuleId))
                const openModule = document.querySelector(
                    '[data-accordion-target="#accordion-collapse-{{ $openModuleId }}"]');
                if (openModule) {
                    openModule.click();
                    const elementPosition = openModule.getBoundingClientRect().top + window.scrollY;
                    const offsetPosition = elementPosition - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth',
                        block: "center"
                    });
                }
            @else
                const firstModule = document.querySelector('#accordion-collapse [data-accordion-target]');
                if (firstModule) {
                    firstModule.click();
                    const elementPosition = firstModule.getBoundingClientRect().top + window.scrollY;
                    const offsetPosition = elementPosition - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth',
                        block: "center"
                    });
                }
            @endif
        });
    </script>
@endsection
