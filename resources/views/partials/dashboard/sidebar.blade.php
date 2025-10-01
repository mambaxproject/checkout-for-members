<div class="flex w-full items-center justify-between bg-neutral-800 px-4 py-4 lg:hidden">
    <h1>
        <a class="mx-auto flex items-center justify-center" href="{{ route('dashboard.home.index') }}" title="Dashboard">
            <img class="animate h-10 group-aria-expanded:hidden"
                 src="{{ asset('images/dashboard/brand-suitsales-horizontal.svg') }}"
                 alt="{{ config('app.name') }}" loading="lazy">
        </a>
    </h1>

    <button class="toggle-open-menu button h-10 w-10 rounded-lg text-white hover:bg-neutral-700"
            onclick="toggleOpenMenu()" type="button">
        @include('components.icon', ['icon' => 'menu'])
    </button>
</div>

<div class="animate group peer fixed left-0 top-0 z-[39] hidden h-full w-[88px] translate-x-0 rounded-r-[22px] bg-[#333] aria-expanded:w-[288px] lg:!block"
     id="sidebarContainer" aria-expanded="true">
    <div class="flex flex-col">

        <h1 class="p-4">
            <a class="mx-auto flex h-14 w-fit items-center" href="{{ route('dashboard.home.index') }}" title="Dashboard">
                <img class="animate w-14 group-aria-expanded:hidden"
                     src="{{ asset('images/dashboard/brand-suitsales.svg') }}"
                     alt="{{ config('app.name') }}" loading="lazy">
                <img class="animate hidden h-10 group-aria-expanded:block"
                     src="{{ asset('images/dashboard/brand-suitsales-horizontal.svg') }}"
                     alt="{{ config('app.name') }}" loading="lazy">
            </a>
        </h1>

        <div class="flex h-[calc(100vh-56px-32px)] flex-col">
            <div class="scrollSidebar h-full max-h-[calc(100vh-64px)] overflow-y-auto">
                <ul class="space-y-1 px-4">

                    <!-- Produtos -->
                    <li>
                        <a title="Produtos" href="{{ route('dashboard.products.index') }}"
                           @class([
                               'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                               'bg-white/10' =>
                                   request()->routeIs('dashboard.products.*') ||
                                   request()->routeIs('dashboard.affiliates.productsAffiliate') ||
                                   request()->routeIs('dashboard.coproducers.productsCoproducer'),
                           ])>
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'local_offer',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Produtos</span>
                        </a>
                    </li>

                    <!-- Membros -->
                    <li>
                        <a title="Membros" href="{{ route('dashboard.members.index') }}"
                           @class([
                               'animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start',
                               'bg-white/10' => request()->routeIs('dashboard.members.*'),
                           ])>
                            @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'people',
                                'custom' => 'text-xl',
                            ])
                            <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Membros</span>
                        </a>
                    </li>

                    <!-- Painel Administrativo -->
                    @if (user()->isAdmin)
                        <li>
                            <a title="Painel Administrativo" href="{{ route('admin.dashboard.products') }}"
                               class="animate flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start">
                                @include('components.icon', [
                                    'type' => 'fill',
                                    'icon' => 'manufacturing',
                                    'custom' => 'text-xl',
                                ])
                                <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Painel Administrativo</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </div>

            <!-- Botão de Sair -->
            <div class="mt-auto px-4 py-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center justify-center gap-3 rounded-lg px-4 py-3 text-white hover:bg-white/10 group-aria-expanded:justify-start"
                            title="Sair">
                        @include('components.icon', [
                            'icon' => 'logout',
                            'custom' => 'text-xl',
                        ])
                        <span class="hidden whitespace-nowrap text-sm group-aria-expanded:block">Sair</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
