<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>{{ trans('panel.site_title') }}</title>

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    >

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css"
    >

    @include('partials.dns-prefech-and-preconnect')
    @include('partials.google-analytics')

    @vite('resources/css/app.css')
    @yield('styles')
</head>

<body class="bg-neutral-100">

    @include('components.loading')

    <div class="relative">

        <div class="h-screen min-h-full">

            @yield('content')

        </div>

        @stack('floating')

    </div>

    <script src="{{ asset('js/dashboard/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/flowbite.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="{{ asset('js/dashboard/main.js') }}"></script>
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

    @yield('script')
    @stack('custom-script')
</body>

</html>
