<div class="flex w-full items-center justify-between bg-neutral-800 px-4 py-4 lg:hidden">

    <h1>

        <a
            class="mx-auto flex items-center justify-center"
            href="{{ route('dashboard.home.index') }}"
            title="Dashboard"
        >

            <img
                class="animate h-10 group-aria-expanded:hidden"
                src="{{ asset('images/dashboard/brand-suitsales-horizontal.svg') }}"
                alt="{{ config('app.name') }}"
                loading="lazy"
            >

        </a>

    </h1>

    <button
        class="toggle-open-menu button h-10 w-10 rounded-lg text-white hover:bg-neutral-700"
        onclick="toggleOpenMenu()"
        type="button"
    >

        @include('components.icon', [
            'icon' => 'menu',
        ])

    </button>

</div>

<div
    class="animate group peer fixed left-0 top-0 z-[39] hidden h-full w-[88px] translate-x-0 rounded-r-[22px] bg-[#333] aria-expanded:w-[288px] lg:!block"
    id="sidebarContainer"
    aria-expanded="true"
>

    <div class="flex flex-col">

        <h1 class="p-4">

            <a
                class="mx-auto flex h-14 w-fit items-center"
                href="{{ route('dashboard.home.index') }}"
                title="Dashboard"
            >

                <img
                    class="animate w-14 group-aria-expanded:hidden"
                    src="{{ asset('images/dashboard/brand-suitsales.svg') }}"
                    alt="{{ config('app.name') }}"
                    loading="lazy"
                >

                <img
                    class="animate hidden h-10 group-aria-expanded:block"
                    src="{{ asset('images/dashboard/brand-suitsales-horizontal.svg') }}"
                    alt="{{ config('app.name') }}"
                    loading="lazy"
                >

            </a>

        </h1>

        <div class="flex h-[calc(100vh-56px-32px)] flex-col">

            <div class="scrollSidebar h-full max-h-[calc(100vh-64px)] overflow-y-auto">
                <ul class="space-y-1 px-4">
                    <li>
                        <button id="chatToggle" title="Assistente IA" @class([
                            'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                        ])>
                            <i class="material-symbols-rounded text-xl" style="color: rgb(51, 204, 51);">smart_toy</i>
                            <span class="hidden whitespace-nowrap text-sm font-medium group-aria-expanded:block"
                                style="color: rgb(51, 204, 51);">
                                Assistente IA
                            </span>
                        </button>
                    </li>

                    <li>
                        <a
                            title="Início"
                            href="{{ route('dashboard.home.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' => request()->routeIs('dashboard.home.index'),
                            ])
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'home',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Início</span>
                        </a>
                    </li>

                    <li>
                        <a
                            title="Produtos"
                            href="{{ route('dashboard.products.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' =>
                                    request()->routeIs('dashboard.products.*') ||
                                    request()->routeIs('dashboard.affiliates.productsAffiliate') ||
                                    request()->routeIs('dashboard.coproducers.productsCoproducer'),
                            ])
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'local_offer',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Produtos</span>
                        </a>
                    </li>

                    <li>
                        <a
                            title="Membros"
                            href="{{ route('dashboard.members.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' => request()->routeIs('dashboard.members.*'),
                            ])
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'people',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Membros</span>
                        </a>
                    </li>

                    <li>
                        <a
                            title="Marketplace"
                            href="{{ route('dashboard.marketplace.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' => request()->routeIs('dashboard.marketplace.*'),
                            ])
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'shopping_cart',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Marketplace</span>
                        </a>
                    </li>

                    <li>
                        <a
                            title="Afiliados"
                            href="{{ route('dashboard.affiliates.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' =>
                                    request()->routeIs('dashboard.affiliates.*') &&
                                    !request()->routeIs('dashboard.affiliates.productsAffiliate'),
                            ])
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'people',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Afiliados</span>
                        </a>
                    </li>

                    <li>
                        <a
                            title="Afiliados"
                            href="{{ route('dashboard.telegram.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' => request()->routeIs('dashboard.telegram.*'),
                            ])
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="28"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.75"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-brand-telegram"
                            >
                                <path
                                    stroke="none"
                                    d="M0 0h24v24H0z"
                                    fill="none"
                                />
                                <path d="M10 14l1 1l3 -3m-5 2l-6 -2l17 -7l-4 17l-5 -6" />
                            </svg>
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Grupos
                                Telegram</span>
                        </a>
                    </li>

                    <li>
                        <button
                            data-collapse-toggle="dropdownMoreMenuOrders"
                            aria-expanded="false"
                            type="button"
                            @class([
                                'animate group/dropdown flex w-full flex-col items-center justify-center gap-0 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:flex-row group-aria-expanded:gap-3 group-aria-expanded:justify-start',
                                'bg-white/10' =>
                                    request()->routeIs('dashboard.orders.*') ||
                                    request()->routeIs('dashboard.subscriptions.*') ||
                                    request()->routeIs('dashboard.abandoned-carts.*'),
                            ])
                        >

                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'point_of_sale',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Relatórios</span>

                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'keyboard_arrow_down',
                                'custom' => 'animate text-xl group-aria-expanded:ml-auto group-aria-expanded/dropdown:rotate-180',
                            ])

                        </button>

                        <ul
                            class="absolute z-20 mt-1 hidden rounded-xl bg-neutral-700 p-2 group-aria-expanded:relative"
                            id="dropdownMoreMenuOrders"
                        >

                            <li>
                                <a
                                    title="Vendas"
                                    href="{{ route('dashboard.orders.index') }}"
                                    @class([
                                        'block rounded-lg px-3 py-2 text-neutral-400 hover:bg-white/5 whitespace-nowrap text-sm',
                                        'bg-white/5' => request()->routeIs('dashboard.orders.index'),
                                    ])
                                >
                                    Vendas
                                </a>
                            </li>

                            <li>
                                <a
                                    title="Assinaturas"
                                    href="{{ route('dashboard.subscriptions.index') }}"
                                    @class([
                                        'block rounded-lg px-3 py-2 text-neutral-400 hover:bg-white/5 whitespace-nowrap text-sm',
                                        'bg-white/5' => request()->routeIs('dashboard.subscriptions.index'),
                                    ])
                                >
                                    Assinaturas
                                </a>
                            </li>

                            <li>
                                <a
                                    title="Comissionamento"
                                    href="{{ route('dashboard.reports.commissioning.index') }}"
                                    @class([
                                        'block rounded-lg px-3 py-2 text-neutral-400 hover:bg-white/5 whitespace-nowrap text-sm',
                                        'bg-white/5' => request()->routeIs('dashboard.reports.commissioning.index'),
                                    ])
                                >
                                    Comissionamento
                                </a>
                            </li>

                            <li>
                                <a
                                    title="Carrinhos abandonados"
                                    href="{{ route('dashboard.abandoned-carts.index') }}"
                                    @class([
                                        'block rounded-lg px-3 py-2 text-neutral-400 hover:bg-white/5 whitespace-nowrap text-sm',
                                        'bg-white/5' => request()->routeIs('dashboard.abandoned-carts.index'),
                                    ])
                                >
                                    Carrinhos abandonados
                                </a>
                            </li>

                            <li>
                                <a
                                        title="Integração CRM"
                                        href="{{ route('dashboard.suitpay-crm-integration.index') }}"
                                        @class([
                                            'block rounded-lg px-3 py-2 text-neutral-400 hover:bg-white/5 whitespace-nowrap text-sm',
                                            'bg-white/5' => request()->routeIs('dashboard.suitpay-crm-integration.index'),
                                        ])
                                >
                                    Integração CRM
                                </a>
                            </li>

                            <li>
                                <a
                                        title="Integração CRM"
                                        href="{{ route('dashboard.utm-reports.index') }}"
                                        @class([
                                            'block rounded-lg px-3 py-2 text-neutral-400 hover:bg-white/5 whitespace-nowrap text-sm',
                                            'bg-white/5' => request()->routeIs('dashboard.utm-reports.index'),
                                        ])
                                >
                                    Métricas UTM
                                </a>
                            </li>

                        </ul>

                    </li>

                    <li>
                        <a
                            title="Relatórios"
                            href="{{ route('dashboard.reports.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' => request()->routeIs('dashboard.reports.*'),
                            ])
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'bar_chart',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Relatórios</span>
                        </a>
                    </li>

                    <li>

                        <button
                            data-collapse-toggle="dropdownMoreMessaging"
                            aria-expanded="false"
                            type="button"
                            @class([
                                'animate group/dropdown flex w-full flex-col items-center justify-center gap-0 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:flex-row group-aria-expanded:gap-3 group-aria-expanded:justify-start',
                                'bg-white/10' => request()->routeIs('dashboard.notification.*'),
                            ])
                        >

                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'message',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Mensageria</span>

                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'keyboard_arrow_down',
                                'custom' => 'animate text-xl group-aria-expanded:ml-auto group-aria-expanded/dropdown:rotate-180',
                            ])

                        </button>

                        <ul
                            class="absolute z-20 mt-1 hidden rounded-xl bg-neutral-700 p-2 group-aria-expanded:relative"
                            id="dropdownMoreMessaging"
                        >

                            <li>
                                <a
                                    title="Vendas"
                                    href="{{ route('dashboard.notification.index', ['services' => 'whatsapp']) }}"
                                    @class([
                                        'flex items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm text-neutral-400 hover:bg-white/5',
                                        'bg-white/5' => request()->route('services') === 'whatsapp',
                                    ])
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="28"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.75"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-whatsapp"
                                    >
                                        <path
                                            stroke="none"
                                            d="M0 0h24v24H0z"
                                            fill="none"
                                        />
                                        <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                        <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                                    </svg>
                                    Whatsapp
                                </a>
                            </li>

                            <!-- <li>
                                <a
                                    title="Vendas"
                                    href="{{ route('dashboard.notification.index', ['services' => 'sms']) }}"
                                    @class([
                                        'flex items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm text-neutral-400 hover:bg-white/5',
                                        'bg-white/5' => request()->route('services') === 'sms',
                                    ])
                                >
                                    @include('components.icon', [
                                        'icon' => 'message',
                                        'custom' => 'text-lg',
                                    ])
                                    SMS
                                </a>
                            </li>

                            <li>
                                <a
                                    title="Vendas"
                                    href="{{ route('dashboard.notification.index', ['services' => 'email']) }}"
                                    @class([
                                        'flex items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm text-neutral-400 hover:bg-white/5',
                                        'bg-white/5' => request()->route('services') === 'mail',
                                    ])
                                >
                                    @include('components.icon', [
                                        'icon' => 'email',
                                        'custom' => 'text-lg',
                                    ])
                                    E-Mail
                                </a>
                            </li> -->

                        </ul>

                    </li>

                    {{-- <li>
                        <a
                            title="Colaboradores"
                            href="{{ route('dashboard.users.index') }}"
                    @class([
                    'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                    'bg-white/10' => request()->routeIs('dashboard.users.*'),
                    ])
                    >
                    @include('components.icon', [
                    'icon' => 'people',
                    'custom' => 'text-xl',
                    ])
                    <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Colaboradores</span>
                    </a>
                    </li> --}}

                    <li>
                        <a
                            title="Aplicações"
                            href="{{ route('dashboard.apps.index') }}"
                            @class([
                                'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                                'bg-white/10' => request()->routeIs('dashboard.apps.*'),
                            ])
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'apps',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Aplicações</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="mt-auto flex flex-col">

                <ul class="space-y-1 px-4">
                    <li
                        id="suitMembersLink"
                        class="hidden"
                    >
                        <a
                            title="Início da Área de Membros"
                            href="{{ route('dashboard.members.redirectMembers') }}"
                            class="animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start"
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'school',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Minhas compras</span>
                        </a>
                    </li>
                    <li>
                        <a
                            class="animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start"
                            title="Voltar para o banking"
                            href="{{ config('services.suitpay.banking_url') }}"
                            target="_blank"
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'home',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">
                                Voltar para o banking
                            </span>
                        </a>
                    </li>

                    @if (user()->isAdmin)
                        <li>
                            <a
                                class="animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start"
                                title="Painel Administrativo"
                                href="{{ route('admin.dashboard.products') }}"
                            >
                                @include('components.icon', [
                                    'type' => 'fill',
                                    'icon' => 'manufacturing',
                                    'custom' => 'text-xl',
                                ])
                                <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Painel
                                    Administrativo</span>
                            </a>
                        </li>
                    @endif

                    {{-- <li>
                        <button
                            class="animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start"
                            title="Recolher menu"
                            type="button"
                        >
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'left_panel_close',
                                'custom' => 'text-xl hidden group-aria-expanded:block',
                            ])
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'left_panel_open',
                                'custom' => 'text-xl block group-aria-expanded:hidden',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Recolher menu</span>
                        </button>
                    </li> --}}

                </ul>

                <hr class="mt-4 border-neutral-600">

                <ul
                    class="hidden bg-white/5 px-4 py-3"
                    id="dropdownMenuProfile"
                >

                    <li>
                        <a
                            class="flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-white hover:bg-neutral-600 group-aria-expanded:justify-start"
                            title="Meus dados"
                            href="{{ route('dashboard.users.profile') }}"
                        >
                            @include('components.icon', [
                                'icon' => 'account_circle',
                                'custom' => 'text-lg',
                            ])
                            <span class="hidden group-aria-expanded:block">Meus dados</span>
                        </a>
                    </li>

                    <li>
                        <a
                            class="relative flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-white hover:bg-neutral-600 group-aria-expanded:justify-start"
                            title="Minhas notificações"
                            href="{{ route('dashboard.users.profile') }}"
                        >
                            @include('components.icon', [
                                'icon' => 'notifications',
                                'custom' => 'text-lg',
                            ])
                            <span class="hidden group-aria-expanded:block">Minhas notificações</span>
                            <span class="absolute -right-1 -top-1 ml-auto flex h-5 w-5 items-center justify-center rounded-lg bg-red-500 text-xs font-bold group-aria-expanded:relative group-aria-expanded:right-0 group-aria-expanded:top-0">4</span>
                        </a>
                    </li>

                    <hr class="my-2 border-neutral-600">

                    <li>
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                class="relative flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-white hover:bg-neutral-600 group-aria-expanded:justify-start"
                                type="submit"
                                title="Sair"
                            >
                                @include('components.icon', [
                                    'icon' => 'logout',
                                    'custom' => 'text-lg',
                                ])
                                <span class="hidden group-aria-expanded:block">Sair</span>
                            </button>
                        </form>
                    </li>

                </ul>

                <button
                    class="animate group/dropdownMenuProfile flex items-center justify-center gap-3 py-4 text-white hover:bg-white/10 aria-expanded:bg-white/10 group-aria-expanded:justify-start group-aria-expanded:px-4"
                    data-collapse-toggle="dropdownMenuProfile"
                    aria-expanded="false"
                    title="{{ user()->shortName }}"
                    type="button"
                >

                    <img
                        class="h-8 w-8 rounded-full"
                        src="{{ user()->avatarUrl }}"
                        alt="{{ user()->name }}"
                        loading="lazy"
                    />

                    <span class="hidden whitespace-nowrap text-sm text-white group-aria-expanded:block">
                        {{ user()->shortName }}
                    </span>

                    @include('components.icon', [
                        'icon' => 'keyboard_arrow_up',
                        'custom' => 'text-lg ml-auto group-aria-expanded/dropdownMenuProfile:rotate-180 hidden group-aria-expanded:block',
                    ])

                </button>

            </div>

        </div>

    </div>

    <button
        class="group/buttonToggle absolute -right-3 bottom-48 flex h-6 w-6 items-center justify-center rounded-full bg-primary"
        id="toggleSidebarButton"
        type="button"
    >

        @include('components.icon', [
            'icon' => 'chevron_right',
            'custom' => 'animate text-white text-xl relative z-10 -right-px group-aria-expanded:rotate-180 group-aria-expanded:-left-px pointer-events-none',
        ])

        <span class="absolute left-0 top-0 h-6 w-6 rounded-full bg-primary opacity-25 group-hover/buttonToggle:animate-ping"></span>

    </button>

</div>

<div
    id="backdrop"
    onclick="toggleCloseMenu()"
    class="fixed left-0 top-0 z-30 hidden h-full w-full bg-neutral-950/80"
></div>

@push('custom-script')
    <script>
        function toggleOpenMenu() {
            const menuPanel = document.getElementById("sidebarContainer");
            const backdrop = document.getElementById("backdrop");

            menuPanel.setAttribute('aria-expanded', true);
            menuPanel.style.display = "block";
            backdrop.style.display = "block";
            document.body.style.overflow = "hidden";
        }

        function toggleCloseMenu() {
            const menuPanel = document.getElementById("sidebarContainer");
            const backdrop = document.getElementById("backdrop");

            menuPanel.setAttribute('aria-expanded', true);
            menuPanel.style.display = "none";
            backdrop.style.display = "none";
            document.body.style.overflow = "auto";
        }

        // Função para atualizar o estado da sidebar
        function toggleSidebar() {
            const menuPanel = document.getElementById('sidebarContainer');
            const isExpanded = menuPanel.getAttribute('aria-expanded') === 'true';
            const newExpandedState = !isExpanded;

            menuPanel.setAttribute('aria-expanded', newExpandedState);
            // Salvar estado no localStorage
            localStorage.setItem('sidebarExpanded', newExpandedState);
        }

        // Restaurar o estado da sidebar ao carregar a página
        function restoreSidebarState() {
            const savedState = localStorage.getItem('sidebarExpanded');
            const isExpanded = savedState === null ? true : savedState === 'true';
            const menuPanel = document.getElementById('sidebarContainer');

            menuPanel.setAttribute('aria-expanded', isExpanded);
        }

        // Adicionar o listener ao botão e restaurar o estado ao carregar a página
        document.getElementById('toggleSidebarButton').addEventListener('click', toggleSidebar);


        function toggleOpenMenuMembers() {
            $('#suitMembersLink').removeClass('hidden');
            $.ajax({
                url: "{{ route('dashboard.members.checkAccess') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.hasAccess) {
                        $('#suitMembersLink').removeClass('hidden');
                    } else {
                        $('#suitMembersLink').addClass('hidden');
                    }
                },
                error: function() {
                    $('#suitMembersLink').addClass('hidden');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            restoreSidebarState();
            toggleOpenMenuMembers();
        });
    </script>
@endpush
