<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge"
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
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"
    />

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"
    />

    @include('partials.dns-prefech-and-preconnect')
    @include('partials.google-analytics')

    @vite('resources/css/app.css')
    @yield('styles')
</head>

<body class="bg-neutral-100">

    @include('components.loading')

    <div class="relative">

        <div class="flex flex-col lg:flex-row lg:items-start">

            @include('partials.dashboard.sidebar')

            @include('partials.dashboard.page')

        </div>

        @stack('floating')

    </div>

    <script src="{{ asset('js/dashboard/jquery-3.6.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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

    <script>
        window.intercomSettings = {
            api_base: "https://api-iam.intercom.io",
            app_id: "r5cnhrwg",
            user_id: "{{ auth()->user()->id }}",
            name: "{{ auth()->user()->name }}",
            email: "{{ auth()->user()->email }}",
            created_at: "{{ auth()->user()->created_at->timestamp }}",
        };
    </script>

    <script>
        // Preenchemos previamente o ID do seu aplicativo no URL do widget: 'https://widget.intercom.io/widget/r5cnhrwg'
        (function() {
            var w = window;
            var ic = w.Intercom;
            if (typeof ic === "function") {
                ic('reattach_activator');
                ic('update', w.intercomSettings);
            } else {
                var d = document;
                var i = function() {
                    i.c(arguments);
                };
                i.q = [];
                i.c = function(args) {
                    i.q.push(args);
                };
                w.Intercom = i;
                var l = function() {
                    var s = d.createElement('script');
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = 'https://widget.intercom.io/widget/r5cnhrwg';
                    var x = d.getElementsByTagName('script')[0];
                    x.parentNode.insertBefore(s, x);
                };
                if (document.readyState === 'complete') {
                    l();
                } else if (w.attachEvent) {
                    w.attachEvent('onload', l);
                } else {
                    w.addEventListener('load', l, false);
                }
            }
        })();
    </script>

    @vite('resources/js/app.js')
    @yield('script')
    @stack('custom-script')

    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics_sales.tag_tracker') }}"
    ></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ config('services.google_analytics_sales.tag_tracker') }}');
    </script>
@include('components.ia-assistant') 
</body>
</html>
