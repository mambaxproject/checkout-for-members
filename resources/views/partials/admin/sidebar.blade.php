<!-- Start::app-sidebar -->
<aside
    class="app-sidebar"
    id="sidebar"
>

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a
            href="{{ route('admin.products.index') }}"
            class="header-logo text-xl text-white"
        >
            <span
                class="desktop-dark"
                alt="logo"
            >
                {{ config('app.name') }}
            </span>

            <span
                class="toggle-logo"
                alt="logo"
            >
                S
            </span>
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div
        class="main-sidebar"
        id="sidebar-scroll"
    >

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">

            <div
                class="slide-left"
                id="slide-left"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                >
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>

            <div class="flex h-[calc(100vh-56px-32px)] flex-col">

                <div class="scrollSidebar h-full max-h-[calc(100vh-64px)] overflow-y-auto">
                    <ul class="main-menu">

                <!-- Start::slide__category -->
                <li class="slide__category">
                    <span class="category-name">Admin</span>
                </li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                <li class="slide shas-sub">
                    <a
                        href="{{ route('admin.dashboard.products') }}"
                        class="side-menu__item"
                    >
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">Dashboards</span>
                    </a>
                </li>

                <li class="slide shas-sub">
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="side-menu__item"
                    >
                        <i class="bx bx-badge-check side-menu__icon"></i>
                        <span class="side-menu__label">Produtos</span>
                    </a>
                </li>
                <!-- End::slide -->

            </ul>
                </div>

                <div class="mt-auto flex flex-col">
                    <ul class="space-y-1 px-4">
                        <li>
                            <a
                                    class="animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start"
                                    title="Voltar para o banking"
                                    href="{{ route('dashboard.home.index') }}"
                                    target="_blank"
                            >
                                <span class=" whitespace-nowrap text-sm group-aria-expanded:block"><i class="bx bx-home side-menu__icon"></i> Voltar para suitsales</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <div
                class="slide-right"
                id="slide-right"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                >
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>

        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
