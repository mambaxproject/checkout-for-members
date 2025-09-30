<!DOCTYPE html>
<html
    lang="en"
    dir="ltr"
    class="light"
    data-theme-mode="light"
    data-header-styles="light"
    data-header-position="fixed"
    data-nav-layout="vertical"
    data-menu-styles="dark"
    data-menu-position="fixed"
    data-toggled="icon-click-closed"
>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title> Admin - {{ config('app.name') }} </title>

    <meta
        name="description"
        content="{{ config('app.name') }}"
    >

    <link
        rel="shortcut icon"
        href="{{ asset('vendor/ynex/images/brand-logos/favicon.ico') }}"
    >

    @include('partials.admin.styles')

    <script
            src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
            integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8="
            crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @vite('resources/css/admin.css')
    @stack('styles')

</head>

<body>

    @include('partials.admin.loader')

    <div class="page">

        @include('partials.admin.header')
        @include('partials.admin.sidebar')

        <!-- Start::content  -->
        <div class="content pb-10">
            <!-- Start::main-content -->
            <div class="main-content px-3 md:px-4 lg:px-6">

                @include('partials.admin.page-header', [
                    'page' => $title,
                    'icon' => 'box2-heart-fill',
                ])
                @yield('content')

            </div>
            <!-- End::main-content -->
        </div>
        <!-- End::content  -->

        @include('partials.admin.footer')

    </div>

    <div id="responsive-overlay"></div>

    @include('partials.admin.scripts')
    @stack('scripts')

</body>

</html>
