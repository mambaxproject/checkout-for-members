@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">
            <h1>Produtos</h1>
        </div>

        <nav class="flex items-center border-b border-neutral-300">

            <a
                href="{{ route('dashboard.products.index') }}"
                title="Produtos lojista"
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
            >
                Meus produtos
            </a>

            <a
                href="{{ route('dashboard.coproducers.productsCoproducer') }}"
                title="Produtos co-participações"
                class="border-b-2 border-primary px-6 py-4 hover:border-primary"
            >
                Minhas coproduções
            </a>

            <a
                href="{{ route('dashboard.affiliates.productsAffiliate') }}"
                title="Produtos afiliado"
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
            >
                Minhas afiliações
            </a>

        </nav>

        <div id="page-tab-content">

            <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">

                <form
                    class="w-full flex-1"
                    action="{{ route('dashboard.coproducers.productsCoproducer') }}"
                    method="GET"
                >

                    <div class="grid grid-cols-12 gap-4">

                        <div class="col-span-12">

                            <div class="append">

                                <input
                                    type="text"
                                    name="filter[name]"
                                    value="{{ request()->input('filter.name') }}"
                                    placeholder="Pesquisar"
                                />

                                <button
                                    class="append-item-right w-12"
                                    type="button"
                                >
                                    @include('components.icon', ['icon' => 'search'])
                                </button>

                            </div>

                        </div>

                    </div>

                </form>

                <button
                    class="button button-outline-primary h-12 w-full gap-1 md:w-auto"
                    data-drawer-target="drawerFilterProducts"
                    data-drawer-show="drawerFilterProducts"
                    data-drawer-placement="right"
                    type="button"
                >
                    @include('components.icon', [
                        'icon' => 'filter_alt',
                        'type' => 'fill',
                        'custom' => 'text-xl',
                    ])
                    Filtros de pesquisa
                </button>

            </div>

            <div class="grid grid-cols-4 gap-6">

                @foreach ($productsCoproducer as $coproducer)
                    <div class="col-span-1">
                        @component('components.card')
                            <div class="">

                                <figure class="relative flex h-[240px] w-full items-center justify-center overflow-hidden rounded-t-xl bg-white">
                                    <img
                                        class="h-full w-full object-cover"
                                        src="{{ $coproducer->product->featuredImageUrl }}"
                                        alt="{{ $coproducer->product->name }}"
                                        loading="lazy"
                                    >

                                    <div class="{{ \App\Enums\SituationCoproducerEnum::getClassBackground($coproducer->situation) }} {{ \App\Enums\SituationCoproducerEnum::getClass($coproducer->situation) }} absolute left-3 top-3 rounded-full px-2 py-1 text-[10px] font-semibold uppercase">
                                        {{ $coproducer->situationFormatted }}
                                    </div>

                                    <div class="absolute right-3 top-3">

                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreProducts' . $coproducer->id,
                                            'customButton' => 'w-8 h-8 rounded-full bg-white',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>
                                                @if ($coproducer->isActive)
                                                    <li>
                                                        <a
                                                            class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                            href="{{ route('dashboard.coproducers.linksProductToCoproducer', ['coproducer' => $coproducer, 'product' => $coproducer->product->id]) }}"
                                                            title="Links ofertas produto"
                                                        >
                                                            Links
                                                        </a>
                                                    </li>
                                                @endif

                                                @if ($coproducer->isPending)
                                                    <form
                                                        action="{{ route('dashboard.coproducers.updateSituation', $coproducer) }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('PUT')

                                                        <input
                                                            type="hidden"
                                                            name="situation"
                                                            value="{{ \App\Enums\SituationAffiliateEnum::ACTIVE->name }}"
                                                        />

                                                        <li>
                                                            <button
                                                                class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                                type="submit"
                                                                onclick="return confirm('Tem certeza?')"
                                                            >
                                                                Aceitar
                                                            </button>
                                                        </li>
                                                    </form>
                                                @endif

                                                @if ($coproducer->canCancel)
                                                    <form
                                                        action="{{ route('dashboard.coproducers.updateSituation', $coproducer) }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('PUT')

                                                        <input
                                                            type="hidden"
                                                            name="situation"
                                                            value="{{ \App\Enums\SituationAffiliateEnum::CANCELED->name }}"
                                                        />

                                                        <li>
                                                            <button
                                                                class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                                type="submit"
                                                                onclick="return confirm('Tem certeza?')"
                                                            >
                                                                Recusar
                                                            </button>
                                                        </li>
                                                    </form>
                                                @endif
                                            </ul>
                                        @endcomponent

                                    </div>

                                </figure>

                                <div class="p-6">

                                    <div class="mb-4">

                                        <p class="text-xs">Por: {{ $coproducer->product->shop->name }}</p>
                                        <h6 class="mb-2 font-semibold">{{ $coproducer->product->name }}</h6>

                                        <p class="text-xs">Comissão</p>
                                        <h5 class="text-lg font-bold">{{ $coproducer->percentage_commission ? Number::percentage($coproducer->percentage_commission) : '-' }}</h5>

                                    </div>

                                    <button
                                        class="button button-primary h-10 w-full rounded-full"
                                        data-drawer-target="drawerFilterProductsDetails-{{ $coproducer->id }}"
                                        data-drawer-show="drawerFilterProductsDetails-{{ $coproducer->id }}"
                                        data-drawer-placement="right"
                                        type="button"
                                    >
                                        Ver detalhes
                                    </button>

                                    @component('components.drawer', [
                                        'id' => 'drawerFilterProductsDetails-' . "$coproducer->id",
                                        'title' => 'Detalhes do produto',
                                        'custom' => 'max-w-xl',
                                    ])
                                        <div class="space-y-8">

                                            <div class="flex items-center gap-6">

                                                <figure class="relative h-32 w-32 overflow-hidden rounded-xl bg-neutral-200">
                                                    <img
                                                        class="absolute h-full w-full object-cover"
                                                        src="{{ $coproducer->product->featuredImageUrl }}"
                                                        alt="{{ $coproducer->product->name }}"
                                                        loading="lazy"
                                                    />
                                                </figure>

                                                <div class="">

                                                    <h4 class="text-lg font-semibold">{{ $coproducer->product->name }}</h4>
                                                    <p class="text-xs text-neutral-400">Por {{ $coproducer->product->shop->name }}</p>

                                                </div>

                                            </div>

                                            @if ($coproducer->isActive)
                                                <a
                                                    class="button button-primary h-10 w-full rounded-full"
                                                    title="Links de coprodução das ofertas produto"
                                                    href="{{ route('dashboard.coproducers.linksProductToCoproducer', ['coproducer' => $coproducer, 'product' => $coproducer->product->id]) }}"
                                                >
                                                    Ver ofertas
                                                </a>
                                            @endif

                                            <div class="space-y-4">

                                                <div class="">

                                                    <h4 class="mb-2 flex items-center gap-2 font-semibold">
                                                        @include('components.icon', [
                                                            'icon' => 'box',
                                                            'custom' => 'text-xl text-neutral-400',
                                                        ])
                                                        Produto
                                                    </h4>

                                                    <div class="rounded-xl bg-neutral-50 px-6 py-4">
                                                        <ul class="divide-y divide-neutral-200/50">
                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Categoria</span>
                                                                <span class="text-sm">{{ $coproducer->product->category->name }}</span>
                                                            </li>

                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Tipo</span>
                                                                <span class="text-sm">{{ $coproducer->product->paymentTypeTranslated }}</span>
                                                            </li>

                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Comissão</span>
                                                                <span class="text-sm">{{ $coproducer->percentage_commission ? Number::percentage($coproducer->percentage_commission) : '-' }}</span>
                                                            </li>

                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Página de vendas</span>
                                                                <a
                                                                    class="text-sm text-primary"
                                                                    target="_blank"
                                                                    title="Página de venda do produto"
                                                                    href="{{ $coproducer->product->getValueSchemalessAttributes('externalSalesLink') }}"
                                                                >
                                                                    {{ $coproducer->product->getValueSchemalessAttributes('externalSalesLink') }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>

                                                <div class="">

                                                    <h4 class="mb-2 flex items-center gap-2 font-semibold">
                                                        @include('components.icon', [
                                                            'icon' => 'sort',
                                                            'custom' => 'text-xl text-neutral-400',
                                                        ])
                                                        Detalhes
                                                    </h4>
                                                    <div class="rounded-xl bg-neutral-50 px-6 py-4">
                                                        <ul class="divide-y divide-neutral-200/50">
                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Descrição</span>
                                                                <span class="text-sm">{{ $coproducer->product->getValueSchemalessAttributes('affiliate.descriptionProduct') }}</span>
                                                            </li>
                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">E-mail de suporte para afiliados</span>
                                                                <a
                                                                    class="text-sm text-primary"
                                                                    title="E-mail de suporte para afiliados"
                                                                    href="mailto:{{ $coproducer->product->getValueSchemalessAttributes('affiliate.emailSupport') }}"
                                                                    target="_blank"
                                                                >
                                                                    {{ $coproducer->product->getValueSchemalessAttributes('affiliate.emailSupport') }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>

                                                <div class="">

                                                    <h4 class="mb-2 flex items-center gap-2 font-semibold">
                                                        @include('components.icon', [
                                                            'icon' => 'storefront',
                                                            'custom' => 'text-xl text-neutral-400',
                                                        ])
                                                        Loja
                                                    </h4>
                                                    <div class="rounded-xl bg-neutral-50 px-6 py-4">
                                                        <ul class="divide-y divide-neutral-200/50">
                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Nome da loja</span>
                                                                <span class="text-sm">{{ $coproducer->product->shop->name }}</span>
                                                            </li>
                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Site</span>
                                                                <a
                                                                    class="text-sm text-primary"
                                                                    target="_blank"
                                                                    title="Página loja do produto"
                                                                    href="{{ $coproducer->product->shop->link }}"
                                                                >
                                                                    {{ $coproducer->product->shop->link }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    @endcomponent

                                </div>

                            </div>
                        @endcomponent
                    </div>
                @endforeach

            </div>

            @if ($productsCoproducer instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $productsCoproducer->links() }}
            @endif

        </div>

    </div>
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterProducts',
        'title' => 'Pesquisar por produto',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form
            action="{{ route('dashboard.affiliates.productsAffiliate') }}"
            method="GET"
        >

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="filter[name]">Nome</label>
                    <input
                        type="text"
                        id="filter[name]"
                        name="filter[name]"
                        value="{{ request()->input('filter.products.name') }}"
                        placeholder="Digite o nome"
                    />
                </div>

                <div class="col-span-12">
                    <label for="filter[category_id]">Categoria</label>
                    <select
                        id="filter[category_id]"
                        name="filter[category_id]"
                    >
                        <option value="">Selecione a categoria</option>
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(request()->input('filter.category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button
                class="button button-primary mt-8 h-12 w-full rounded-full"
                type="submit"
            >
                Pesquisar
            </button>

        </form>
    @endcomponent
@endpush
