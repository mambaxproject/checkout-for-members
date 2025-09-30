<!-- Start::Header -->
<header class="app-header">

    <nav
        class="main-header !h-[3.75rem]"
        aria-label="Global"
    >

        <div class="main-header-container pe-[1rem] ps-[0.725rem]">

            <div class="header-content-left">

                <!-- Start::header-element -->
                <div class="header-element">
                    <div class="horizontal-logo">
                        <a
                            href="{{ route('dashboard.products.index') }}"
                            class="header-logo text-xl"
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
                </div>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <div class="header-element !items-center md:px-[0.325rem]">
                    <!-- Start::header-link -->
                    <a
                        aria-label="Hide Sidebar"
                        class="sidemenu-toggle animated-arrow hor-toggle horizontal-navtoggle inline-flex items-center"
                        href="javascript:void(0);"
                    >
                        <span></span>
                    </a>
                    <!-- End::header-link -->
                </div>
                <!-- End::header-element -->

            </div>

            <div class="header-content-right">

                @include('components.admin.header.profile')

            </div>

        </div>

    </nav>

</header>
<!-- End::Header -->
