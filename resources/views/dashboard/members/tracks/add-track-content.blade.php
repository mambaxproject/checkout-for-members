@extends('layouts.members')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">
        <div class="flex items-center justify-between">
            <h1>{{ $track['name'] }}</h1>
            <a class="button button-primary h-12 gap-1 rounded-full"
                href="{{ route('dashboard.members.addCourseTrack', ['trackId' => $track['id']]) }}">
                @include('components.icon', [
                    'icon' => 'add',
                    'custom' => 'text-xl text-white',
                ])
                <span class="text-sm font-medium">Adicionar mini curso</span>
            </a>
        </div>
        <div class="space-y-4 md:space-y-10">
            <div class="space-y-3" id="accordion-collapse" data-accordion="collapse">
                @if (!empty($track['Courses']))
                    @foreach ($track['Courses'] as $course)
                        @component('components.card')
                            <div class="group p-6 md:p-8" data-course-id="{{ $course['id'] }}">
                                <div class="flex items-center gap-4">

                                    <div class="group flex cursor-pointer items-center gap-2 aria-expanded:bg-transparent"
                                        data-accordion-target="#accordion-collapse-{{ $course['id'] }}"
                                        aria-expanded="{{ (isset($openCourseId) && $openCourseId == $course['id']) || (!isset($openCourseId) && $loop->first) ? 'true' : 'false' }}">

                                        @include('components.icon', [
                                            'icon' => 'stat_minus_1',
                                            'custom' => 'text-xl font-semibold group-aria-expanded:rotate-180',
                                        ])

                                        <h3 class="text-base font-medium">{{ $course['name'] }}</h3>
                                        <div
                                            class="{{ \App\Enums\MemberStatusEnum::getClassBackground(strtolower($course['status'])) }} rounded-full px-3 py-1.5 text-[10px] font-semibold uppercase">
                                            {{ $course['status'] }}
                                        </div>
                                    </div>

                                    <div class="ml-auto rounded-full bg-neutral-100 px-3 py-1.5 text-xs">
                                        {{ count($course['Modules']) }} modulos
                                    </div>

                                    <div class="flex items-center gap-px">
                                        <a class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-neutral-100 hover:text-neutral-700 active:bg-neutral-200"
                                            title="Editar mini curso"
                                            href="{{ route('dashboard.members.editCourseTrack', ['courseId' => $course['id']]) }}">
                                            @include('components.icon', [
                                                'icon' => 'edit',
                                                'custom' => 'text-lg font-semibold',
                                            ])
                                        </a>
                                        @if ($course['status'] == 'Ativo')
                                            <form method="POST"
                                                action="{{ route('dashboard.members.deleteCourseTrack', ['courseId' => $course['id']]) }}"
                                                onsubmit="return confirm('Deseja realmente desativar este mini curso?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Desativar mini curso"
                                                    class="animate flex h-[40px] w-[40px] items-center justify-center rounded-md text-neutral-500 hover:bg-danger-50 hover:text-danger-500 active:bg-neutral-200">
                                                    @include('components.icon', [
                                                        'icon' => 'delete',
                                                        'custom' => 'text-lg font-semibold',
                                                    ])
                                                </button>
                                            </form>
                                        @endif
                                        @if ($course['status'] == 'Desativado')
                                            <form method="POST"
                                                action="{{ route('dashboard.members.activateCourseTrack', ['courseId' => $course['id']]) }}"
                                                onsubmit="return confirm('Deseja reativar este mini curso? Ele ficará disponível novamente.');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" title="Reativar mini curso"
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

                            <div class="{{ $course['id'] ? '' : 'hidden' }} px-4 pb-4"
                                id="accordion-collapse-{{ $course['id'] }}">
                                <div class="">
                                    <div class="rounded-xl bg-neutral-50 p-6 md:p-8">
                                        <div class="space-y-6">

                                            <div class="flex items-center justify-between px-2">
                                                <h4>Modulos</h4>
                                                <div class="flex items-center gap-2">
                                                    <a class="button button-outline-light h-12 gap-1 rounded-full"
                                                        title="Adicionar modulo"
                                                        href="{{ route('dashboard.members.addModuleTrack', ['courseId' => $course['id']]) }}">
                                                        @include('components.icon', [
                                                            'icon' => 'folder',
                                                            'custom' => 'text-lg',
                                                        ])
                                                        Adicionar conteúdo
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="space-y-3">
                                                @foreach ($course['Modules'] as $module)
                                                    <div
                                                        class="flex items-center gap-4 rounded-xl border border-neutral-200 p-4">
                                                        @include('components.icon', [
                                                            'icon' => 'folder',
                                                            'custom' => 'text-lg',
                                                        ])

                                                        <h4>{{ $module['name'] }}</h4>

                                                        <div
                                                            class="{{ \App\Enums\MemberStatusEnum::getClassBackground(strtolower('Ativo')) }} rounded-full px-3 py-1.5 text-[10px] font-semibold uppercase">
                                                            Ativo
                                                        </div>

                                                        <div class="ml-auto rounded-full bg-neutral-100 px-3 py-1.5 text-xs">
                                                            {{ $module['totalLessons'] }}
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
                                                                    onsubmit="return confirm('Deseja realmente desativar este módulo?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" title="Desativar módulo"
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
                                                                    onsubmit="return confirm('Deseja reativar este mini curso? Ele ficará disponível novamente.');">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" title="Reativar mini curso"
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
                            </div>
                        @endcomponent
                    @endforeach
                @else
                    @component('components.card')
                        <div class="flex items-center justify-center p-8">
                            <p>Não existe cursos adicionados até o momento</p>
                        </div>
                    @endcomponent
                @endif

            </div>

        </div>

    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    @if ($openCourseId)
        const openCourseId = {{ $openCourseId }};
        const courseHeader = document.querySelector('[data-accordion-target="#accordion-collapse-' + openCourseId + '"]');
        if (courseHeader) {
            courseHeader.click();

            const headerOffset = {{count($track['Courses']) * 70}}; 
            const elementPosition = courseHeader.getBoundingClientRect().top + window.scrollY;
            const offsetPosition = elementPosition - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    @endif
});

    </script>
@endsection
