<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    @include('partials.dns-prefech-and-preconnect')
    {{-- @include('partials.google-analytics') --}}

    @vite('resources/css/app.css')
    @stack('style')

</head>

<body class="bg-neutral-100">
    <div class="">

        <div class="fixed z-20 flex h-[72px] w-full items-center gap-6 bg-neutral-800 px-6">

            <h1>
                <a href="{{ route('dashboard.home.index') }}">
                    <img class="h-8" alt="Suitpay" src="{{ asset('images/dashboard/brand-suitpay.svg') }}" />
                </a>
            </h1>

            <div class="ml-auto flex items-center gap-4">
                @if ($course['status'] == 'Ativo')
                    <a href="{{ route('dashboard.members.redirectMembersCourse', ['courseId' => $course['parentId'] ?? $course['id']]) }}"
                        target="_blank" class="button button-light h-10 rounded-full text-center" type="button">
                        @include('components.icon', [
                            'type' => 'fill',
                            'icon' => 'menu_book',
                            'custom' => 'text-xl',
                        ])
                        <span class="whitespace-nowrap ml-2 text-sm group-aria-expanded:block">
                            Acessar curso
                        </span>
                    </a>
                @endif
                @if ($course['status'] == 'Pendente')
                    <a href="{{ route('dashboard.members.redirectMembersCourse', ['courseId' => $course['parentId'] ?? $course['id']]) }}"
                        target="_blank" class="button button-light h-10 rounded-full text-center" type="button">
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


        <div class="fixed top-[72px] z-20 min-h-[calc(100vh-72px)] w-96 bg-white p-6">

            <ul class="space-y-1">

                <li>
                    <a href="{{ route('dashboard.members.edit', ['courseId' => $course['parentId'] ?? $course['id']]) }}"
                        @class([
                            'flex items-center gap-2 rounded-md p-4 font-medium hover:bg-neutral-100',
                            'bg-neutral-100' => request()->routeIs('dashboard.members.edit'),
                        ])>
                        @include('components.icon', ['icon' => 'book_2'])
                        Curso
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.members.content', ['courseId' => $course['parentId'] ?? $course['id']]) }}"
                        @class([
                            'flex items-center gap-2 rounded-md p-4 font-medium hover:bg-neutral-100',
                            'bg-neutral-100' => request()->routeIs('dashboard.members.content'),
                        ])>
                        @include('components.icon', ['icon' => 'play_circle'])
                        Conteúdo
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.members.students', ['courseId' => $course['parentId'] ?? $course['id']]) }}"
                        @class([
                            'flex items-center gap-2 rounded-md p-4 font-medium hover:bg-neutral-100',
                            'bg-neutral-100' => request()->routeIs('dashboard.members.students'),
                        ])>
                        @include('components.icon', ['icon' => 'people'])
                        Alunos
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('dashboard.members.classes', ['courseId' => $course['parentId'] ?? $course['id']]) }}"
                        @class([
                            'flex items-center gap-2 rounded-md p-4 font-medium hover:bg-neutral-100',
                            'bg-neutral-100' => request()->routeIs('dashboard.members.classes'),
                        ])
                    >
                        @include('components.icon', ['icon' => 'groups'])
                        Turmas
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.members.settingsCourse', ['courseId' => $course['parentId'] ?? $course['id']]) }}"
                        @class([
                            'flex items-center gap-2 rounded-md p-4 font-medium hover:bg-neutral-100',
                            'bg-neutral-100' => request()->routeIs('dashboard.members.settingsCourse'),
                        ])>
                        @include('components.icon', ['icon' => 'settings'])
                        Configurações
                    </a>
                </li>
            </ul>

        </div>

        <div class="ml-96 flex-1 px-10 pb-10 pt-[calc(72px+40px)]">
            @yield('content')
            @stack('floating')
        </div>

    </div>

    <script src="{{ asset('js/dashboard/jquery-3.6.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="{{ asset('js/members/main.js') }}"></script>
    <script src="{{ asset('js/dashboard/dropzone-config.js') }}"></script>
    <script>
        class CustomNotyf extends Notyf {
            constructor() {
                super({
                    duration: 5000,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    },
                    types: [{
                            type: "info",
                            background: "#3b82f6", // Azul (Tailwind `blue-500`)
                            icon: {
                                className: "material-symbols-rounded",
                                tagName: "i",
                                text: "info", // Ícone de informação
                                color: '#ffffff',
                            },
                        },
                        {
                            type: "warning",
                            background: "#f59e0b", // Amarelo (Tailwind `yellow-500`)
                            icon: {
                                className: 'material-symbols-rounded',
                                tagName: 'i',
                                text: 'warning',
                                color: '#ffffff',
                            }
                        },
                        {
                            type: "success",
                            background: "#3dc763",
                            icon: {
                                className: "material-symbols-rounded",
                                tagName: "i",
                                text: "check_circle", // Ícone de sucesso
                                color: '#ffffff',
                            },
                        },
                        {
                            type: "error",
                            background: "#ed3d3d",
                            icon: {
                                className: "material-symbols-rounded",
                                tagName: "i",
                                text: "close", // Ícone de erro
                                color: '#ffffff',
                            },
                        },
                    ],
                });
            }

            // Métodos personalizados para facilitar o uso
            info(message) {
                this.open({
                    type: "info",
                    message
                });
            }

            warning(message) {
                this.open({
                    type: "warning",
                    message
                });
            }
        }
        // Criando uma instância
        const notyf = new CustomNotyf();
    </script>

    @if ($errors->any())
        <script>
            let errors = @json($errors->all());

            errors.forEach(error => {
                notyf.error(error, "Erro");
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            let successMessages = @json((array) session('success'));
            successMessages.forEach(message => {
                notyf.success(message, "Sucesso");
            });
        </script>
    @endif

    @stack('script')

</body>

</html>
