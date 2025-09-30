@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">
            <h1>Marketplace</h1>
        </div>

        <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">

            <form
                class="flex-1"
                action="{{ route('dashboard.marketplace.index') }}"
                method="GET"
            >

                <div class="grid grid-cols-12 gap-2 md:gap-4">

                    <div class="col-span-12">

                        <div class="append">

                            <input
                                type="text"
                                name="filter[name]"
                                value="{{ request()->input('filter.name') }}"
                                placeholder="Pesquise pelo nome do produto"
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
                data-drawer-target="drawerFilterMarketplaceHighlightsWeek"
                data-drawer-show="drawerFilterMarketplaceHighlightsWeek"
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

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

            @forelse ($productsMarketplace as $product)
                <div class="col-span-1">
                    @component('components.card', ['custom' => 'overflow-hidden'])
                        <figure class="flex h-[240px] w-full items-center justify-center overflow-hidden rounded-t-xl bg-white">

                            <img
                                class="h-full w-full object-cover"
                                src="{{ $product->featuredImageUrl }}"
                                alt="{{ $product->name }}"
                                loading="lazy"
                            />

                        </figure>

                        <div class="space-y-4 p-6">

                            <div class="space-y-1">

                                <p class="text-xs">Por: {{ $product->shop->name }}</p>

                                <div class="">
                                    <h4>{{ \Illuminate\Support\Str::limit($product->name, 25) }}</h4>
                                </div>

                                <div class="">
                                    <p class="text-xs">Ganhe até:</p>
                                    <h3>{{ $product->valueAffiliateEarning }}</h3>
                                </div>

                            </div>

                            <button
                                class="button button-primary openDetailProductMarketplace h-10 w-full rounded-full"
                                data-drawer-target="drawerFilterMarketplaceDetails-{{ $product->id }}"
                                data-drawer-show="drawerFilterMarketplaceDetails-{{ $product->id }}"
                                data-drawer-placement="right"
                                type="button"
                            >
                                Saiba mais
                            </button>

                            @component('components.drawer', [
                                'id' => 'drawerFilterMarketplaceDetails-' . "$product->id",
                                'title' => 'Detalhes do produto',
                                'custom' => 'max-w-xl',
                            ])
                                <div class="space-y-8">

                                    <div class="flex items-center gap-6">

                                        <figure class="relative h-32 w-32 overflow-hidden rounded-xl bg-neutral-200">
                                            <img
                                                class="absolute h-full w-full object-cover"
                                                src="{{ $product->featuredImageUrl }}"
                                                alt="{{ $product->name }}"
                                                loading="lazy"
                                            />
                                        </figure>

                                        <div class="">

                                            <h4 class="text-lg font-semibold">{{ $product->name }}</h4>
                                            <p class="text-sm text-neutral-400">Por {{ $product->shop->name }}</p>

                                        </div>

                                    </div>
                                    @if ($product->shop->owner_id === auth()->id() || $product->coproducers->where('situation', 'ACTIVE')->contains('user_id', auth()->id()))
                                        <button
                                            class="button button-light h-10 w-full rounded-full"
                                            title="Você é o dono deste produto"
                                            disabled
                                        >
                                            Você é o produtor
                                        </button>
                                    @elseif ($product->affiliates->doesntContain('user_id', auth()->id()))
                                        <form
                                            method="POST"
                                            action="{{ route('dashboard.marketplace.joinAffiliate', $product) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="button button-primary h-10 w-full rounded-full"
                                                title="Afiliar ao produto {{ $product->name }}"
                                                onclick="return confirm('Deseja realmente afiliar-se ao produto {{ $product->name }}?')"
                                            >
                                                Afiliar
                                            </button>
                                        </form>
                                    @else
                                        <button
                                            class="button button-light h-10 w-full rounded-full"
                                            title="Você já é afiliado deste produto"
                                            disabled
                                        >
                                            Você já é afiliado
                                        </button>
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
                                                        <span class="text-sm">{{ $product->category->name }}</span>
                                                    </li>

                                                    <li class="flex flex-col gap-1 py-3">
                                                        <span class="text-sm font-semibold">Tipo</span>
                                                        <span class="text-sm">{{ $product->paymentTypeTranslated }}</span>
                                                    </li>

                                                    <li class="flex flex-col gap-1 py-3">
                                                        <span class="text-sm font-semibold">Receba até</span>
                                                        <span class="text-sm">{{ $product->valueAffiliateEarning }}</span>
                                                    </li>

                                                    <li class="flex flex-col gap-1 py-3">
                                                        <span class="text-sm font-semibold">Página de vendas</span>
                                                        <a
                                                            class="text-sm text-primary"
                                                            target="_blank"
                                                            title="Página de venda do produto"
                                                            href="{{ $product->getValueSchemalessAttributes('externalSalesLink') }}"
                                                        >
                                                            {{ $product->getValueSchemalessAttributes('externalSalesLink') }}
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
                                                        <span class="text-sm">{{ $product->getValueSchemalessAttributes('affiliate.descriptionProduct') }}</span>
                                                    </li>
                                                    <li class="flex flex-col gap-1 py-3">
                                                        <span class="text-sm font-semibold">E-mail de suporte para afiliados</span>
                                                        <a
                                                            class="text-sm text-primary"
                                                            title="E-mail de suporte para afiliados"
                                                            href="mailto:{{ $product->getValueSchemalessAttributes('affiliate.emailSupport') }}"
                                                            target="_blank"
                                                        >
                                                            {{ $product->getValueSchemalessAttributes('affiliate.emailSupport') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>

                                        <div class="">

                                            <h4 class="mb-2 flex items-center gap-2 font-semibold">
                                                @include('components.icon', [
                                                    'icon' => 'confirmation_number',
                                                    'custom' => 'text-xl text-neutral-400',
                                                ])
                                                Ofertas
                                            </h4>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="w-[50%]">Nome</th>
                                                        <th>Preço</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->offers as $offer)
                                                        <tr>
                                                            <td class="!whitespace-normal">{{ $offer->name }}</td>
                                                            <td>{{ $offer->brazilianPrice }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>

                                </div>
                            @endcomponent

                        </div>
                    @endcomponent
                </div>
            @empty
                <div
                    class="col-span-1 rounded-lg bg-gray-50 p-4 text-center text-sm text-gray-800 dark:bg-gray-800 dark:text-gray-300 md:col-span-2 xl:col-span-4"
                    role="alert"
                >
                    Sem registros.
                </div>
            @endforelse

        </div>

        {{ $productsMarketplace->links() }}

    </div>
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterMarketplaceHighlightsWeek',
        'title' => 'Pesquisar por destaque da semana',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form
            action="{{ route('dashboard.marketplace.index') }}"
            method="GET"
        >

            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-12">
                    <label for="filter[name]">Nome</label>
                    <input
                        type="text"
                        name="filter[name]"
                        value="{{ request()->input('filter.name') }}"
                        placeholder="Pesquisar"
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

                <div class="col-span-12">
                    <label for="filter[rangePrice]">Faixa de preço</label>
                    @include('components.form.dual-range', [
                        'name' => 'filter[rangePrice][]',
                        'min' => 10,
                        'max' => 20000,
                        'slide1Value' => 10,
                        'slide2Value' => 20000,
                    ])
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
