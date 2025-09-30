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
                    class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
            >
                Minhas coproduções
            </a>

            <a
                    href="{{ route('dashboard.affiliates.productsAffiliate') }}"
                    title="Produtos afiliado"
                    class="border-b-2 border-primary px-6 py-4 hover:border-primary"
            >
                Minhas afiliações
            </a>

        </nav>

        <div id="page-tab-content">

            <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">

                <form
                        class="w-full flex-1"
                        action="{{ route('dashboard.affiliates.productsAffiliate') }}"
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

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($productsAffiliate as $affiliate)
                    <div class="col-span-1">
                        @component('components.card')
                            <div class="">

                                <figure class="relative flex h-[240px] w-full items-center justify-center overflow-hidden rounded-t-xl bg-white">

                                    <img
                                            class="h-full w-full object-cover"
                                            src="{{ $affiliate->product->featuredImageUrl }}"
                                            alt="{{ $affiliate->product->name }}"
                                            loading="lazy"
                                    >

                                    <div class="{{ \App\Enums\SituationAffiliateEnum::getClassBackground($affiliate->situation) }} {{ \App\Enums\SituationCoproducerEnum::getClass($affiliate->situation) }} absolute left-3 top-3 rounded-full px-2 py-1 text-[10px] font-semibold uppercase">
                                        {{ $affiliate->situationFormatted }}
                                    </div>

                                    <div class="absolute right-3 top-3">

                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreProducts' . $affiliate->id,
                                            'customButton' => 'w-8 h-8 rounded-full bg-white',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>
                                                <li>
                                                    <a
                                                            class="flex items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                            href="{{ route('dashboard.affiliates.linksProductToAffiliate', ['affiliate' => $affiliate, 'product' => $affiliate->product]) }}"
                                                            title="Links ofertas produto"
                                                    >
                                                        Links
                                                    </a>
                                                </li>
                                            </ul>
                                        @endcomponent

                                    </div>

                                </figure>

                                <div class="p-6">

                                    <div class="mb-4">

                                        <p class="text-xs">Por: {{ $affiliate->product->shop->name }}</p>
                                        <h6 class="mb-2 font-semibold">{{ $affiliate->product->name }}</h6>

                                        <p class="text-xs">Comissão</p>
                                        <h5 class="text-lg font-bold">{{ $affiliate->formattedValue }}</h5>

                                    </div>

                                    <button
                                            class="button button-primary h-10 w-full rounded-full"
                                            data-drawer-target="drawerFilterProductsDetails-{{ $affiliate->id }}"
                                            data-drawer-show="drawerFilterProductsDetails-{{ $affiliate->id }}"
                                            data-drawer-placement="right"
                                            type="button"
                                    >
                                        Ver detalhes
                                    </button>

                                    @component('components.drawer', [
                                        'id' => 'drawerFilterProductsDetails-' . "$affiliate->id",
                                        'title' => 'Detalhes do produto',
                                        'custom' => 'max-w-xl',
                                    ])
                                        <div class="space-y-8">

                                            <div class="flex items-center gap-6">

                                                <figure class="relative h-32 w-32 overflow-hidden rounded-xl bg-neutral-200">
                                                    <img
                                                            class="absolute h-full w-full object-cover"
                                                            src="{{ $affiliate->product->featuredImageUrl }}"
                                                            alt="{{ $affiliate->product->name }}"
                                                            loading="lazy"
                                                    />
                                                </figure>

                                                <div class="">

                                                    <h4 class="text-lg font-semibold">{{ $affiliate->product->name }}</h4>
                                                    <p class="text-xs text-neutral-400">
                                                        Por {{ $affiliate->product->shop->name }}</p>

                                                </div>

                                            </div>

                                            @if ($affiliate->isActive)
                                                <a
                                                        class="button button-primary h-10 w-full rounded-full"
                                                        title="Links ofertas produto"
                                                        href="{{ route('dashboard.affiliates.linksProductToAffiliate', ['affiliate' => $affiliate, 'product' => $affiliate->product]) }}"
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
                                                                <span class="text-sm">{{ $affiliate->product->category->name }}</span>
                                                            </li>

                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Tipo</span>
                                                                <span class="text-sm">{{ $affiliate->product->paymentTypeTranslated }}</span>
                                                            </li>

                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Comissão</span>
                                                                <span class="text-sm">{{ $affiliate->formattedValue }}</span>
                                                            </li>

                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Página de vendas</span>
                                                                @if ($affiliate->product->getValueSchemalessAttributes('externalSalesLink'))
                                                                    <a
                                                                        class="text-sm text-primary"
                                                                        target="_blank"
                                                                        title="Página de venda do produto"
                                                                        href="{{ $affiliate->redirectAffiliateExternalSalesLink }}"
                                                                    >
                                                                        {{ $affiliate->redirectAffiliateExternalSalesLink }}
                                                                    </a>
                                                                @endif
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
                                                                <span class="text-sm">{{ $affiliate->product->getValueSchemalessAttributes('affiliate.descriptionProduct') }}</span>
                                                            </li>
                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">E-mail de suporte para afiliados</span>
                                                                <a
                                                                    class="text-sm text-primary"
                                                                    title="E-mail de suporte para afiliados"
                                                                    href="mailto:{{ $affiliate->product->getValueSchemalessAttributes('affiliate.emailSupport') }}"
                                                                    target="_blank"
                                                                >
                                                                    {{ $affiliate->product->getValueSchemalessAttributes('affiliate.emailSupport') }}
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
                                                                <span class="text-sm">{{ $affiliate->product->shop->name }}</span>
                                                            </li>
                                                            <li class="flex flex-col gap-1 py-3">
                                                                <span class="text-sm font-semibold">Site</span>
                                                                <a
                                                                    class="text-sm text-primary"
                                                                    target="_blank"
                                                                    title="Página loja do produto"
                                                                    href="{{ $affiliate->product->shop->link }}"
                                                                >
                                                                    {{ $affiliate->product->shop->link }}
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
                                                        Pixel
                                                    </h4>
                                                    <div class="rounded-xl bg-neutral-50 px-6 py-4">


                                                        @component('components.toggle', [
                                                            'id' => 'pixel',
                                                            'customToggle' => 'hidden',
                                                            'isChecked' => true,
                                                        ])
                                                            <button
                                                                    class="button button-light mb-6 h-12 w-full rounded-full"
                                                                    data-drawer-target="drawerAddPixel{{$affiliate->product->id}}"
                                                                    data-drawer-show="drawerAddPixel{{$affiliate->product->id}}"
                                                                    data-drawer-placement="right"
                                                                    type="button"
                                                            >
                                                                @include('components.icon', [
                                                                    'icon' => 'add',
                                                                    'custom' => 'text-xl',
                                                                ])
                                                                Adicionar pixel
                                                            </button>

                                                            <div class="overflow-hidden rounded-lg md:overflow-visible">
                                                                <div class="overflow-x-scroll md:overflow-visible">
                                                                    <form action="{{ route('dashboard.affiliates.productsPixelAffiliate', ['product' => $affiliate->product->id]) }}" method="POST" class="space-y-4">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <table
                                                                            class=" mt-0  table-lg table w-full"
                                                                            id="tablePixels{{$affiliate->product->id}}"
                                                                    >
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Serviço</th>
                                                                            <th>Nome</th>
                                                                            <th></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach ($affiliate->product->pixels as $index => $pixel)
                                                                            <tr data-id="{{ $pixel->id }}">

                                                                                <input
                                                                                        type="hidden"
                                                                                        name="product[pixels][{{ $index }}][id]"
                                                                                        value="{{ $pixel->id }}"
                                                                                />

                                                                                <td>{{ $pixel->pixelService->name }}</td>
                                                                                <td>{{ $pixel->name }}</td>
                                                                                <td class="text-end">
                                                                                    @component('components.dropdown-button', [
                                                                                        'id' => 'dropdownMoreTablePixel' . $pixel->id,
                                                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                                        'custom' => 'text-xl',
                                                                                    ])
                                                                                        <ul>
                                                                                            <li>
                                                                                                <a
                                                                                                        class="viewPixel flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                                        data-pixel="{{ $pixel }}"
                                                                                                        href="javascript:void(0)"
                                                                                                        title="Visualizar"
                                                                                                        data-drawer-target="drawerViewPixel"
                                                                                                        data-drawer-show="drawerViewPixel"
                                                                                                        data-drawer-placement="right"
                                                                                                >
                                                                                                    Visualizar
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a
                                                                                                        class="editPixel flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                                        data-pixel="{{ $pixel }}"
                                                                                                        href="javascript:void(0)"
                                                                                                        data-drawer-target="drawerAddPixel{{$affiliate->product->id}}"
                                                                                                        data-drawer-show="drawerAddPixel{{$affiliate->product->id}}"
                                                                                                        data-drawer-placement="right"
                                                                                                >
                                                                                                    Editar
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a
                                                                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                                        onclick="this.closest('tr').remove()"
                                                                                                        href="javascript:void(0)"
                                                                                                >
                                                                                                    Remover
                                                                                                </a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    @endcomponent
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                        <button
                                                                                class="button button-primary h-10 w-full rounded-full"
                                                                                title=""
                                                                        >
                                                                            salvar
                                                                        </button>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        @endcomponent

                                                        @push('floating')
                                                            <!-- FORM -->
                                                            @component('components.drawer', [
                                                                'id' => 'drawerAddPixel'.$affiliate->product->id,
                                                                'title' => 'Adicionar pixel',
                                                                'custom' => 'max-w-2xl drawerAddPixel',
                                                            ])
                                                                <div class="inputsPixels grid grid-cols-12 gap-6">
                                                                    <input
                                                                            type="hidden"
                                                                            id="product[pixels][id]"
                                                                            name="product[pixels][id]"
                                                                            value=""
                                                                    />

                                                                    <input
                                                                            type="hidden"
                                                                            id="product[pixels][mark_billet]"
                                                                            name="product[pixels][mark_billet]"
                                                                            value="0"
                                                                    >

                                                                    <input
                                                                            type="hidden"
                                                                            id="product[pixels][mark_pix]"
                                                                            name="product[pixels][mark_pix]"
                                                                            value="0"
                                                                    >

                                                                    <input
                                                                            type="hidden"
                                                                            id="product[pixels][attributes][backend_purchase]"
                                                                            name="product[pixels][attributes][backend_purchase]"
                                                                            value="0"
                                                                    >

                                                                    <div class="col-span-12">

                                                                        <label for="product[pixels][pixel_service_id]">Serviço</label>
                                                                        <div class="append">
                                                                            <select
                                                                                    class="pixelServiceSelect"
                                                                                    name="product[pixels][pixel_service_id]"
                                                                                    id="product[pixels][pixel_service_id]"
                                                                            >
                                                                                <option value="0">Selecione um serviço
                                                                                </option>

                                                                                @foreach ($pixelServices as $pixelService)
                                                                                    <option
                                                                                            value="{{ $pixelService->id }}"
                                                                                            data-pixelServiceName="{{ str($pixelService->name)->slug('') }}"
                                                                                            data-icon='{{ $pixelService->image_url }}'
                                                                                    >
                                                                                        {{ $pixelService->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div class="append-item-left pointer-events-none w-16 px-3">
                                                                                <img
                                                                                        class="hidden select-icon"
                                                                                        src=""
                                                                                        alt="{{ $pixelService->name }}"
                                                                                />
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-span-12">
                                                                        <label for="product[pixels][name]">Nome</label>
                                                                        <input
                                                                                type="text"
                                                                                id="product[pixels][name]"
                                                                                name="product[pixels][name]"
                                                                                placeholder="Digite um nome para o pixel"
                                                                                required
                                                                        />
                                                                    </div>

                                                                    <div class="col-span-12">

                                                                        <div
                                                                                class="hidden rounded-xl bg-neutral-100 p-6"
                                                                                id="facebookFormContent"
                                                                        >

                                                                            <h4 class="productPixelName [&>img]:h-8">
                                                                                Facebook</h4>
                                                                            <hr class="my-4">

                                                                            <div class="grid grid-cols-12 gap-6">

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][pixel_id]">ID
                                                                                        do pixel</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][pixel_id]"
                                                                                            name="product[pixels][pixel_id]"
                                                                                            placeholder="Digite o PIXEL ID"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][access_token]">Access
                                                                                        Token</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][attributes][access_token]"
                                                                                            name="product[pixels][attributes][access_token]"
                                                                                            placeholder="Access Token"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][amountToSend]">
                                                                                        Valor enviado para o pixel
                                                                                    </label>
                                                                                    <select
                                                                                            name="product[pixels][attributes][amountToSend]"
                                                                                            id="product[pixels][attributes][amountToSend]"
                                                                                    >
                                                                                        <option value="">Selecione
                                                                                        </option>
                                                                                        @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                                                                            <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-span-12">

                                                                                    <div class="space-y-2">

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addFacebookBoleto'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Boleto',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addFacebookPix'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Pix',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addFacebookBackendPurchase'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Back-end Purchase',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div
                                                                                class="hidden rounded-xl bg-neutral-100 p-6"
                                                                                id="googleadsFormContent"
                                                                        >

                                                                            <h4 class="productPixelName [&>img]:h-8">
                                                                                Google</h4>
                                                                            <hr class="my-4">

                                                                            <div class="grid grid-cols-12 gap-6">

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][pixel_id]">ID
                                                                                        do pixel</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][pixel_id]"
                                                                                            name="product[pixels][pixel_id]"
                                                                                            placeholder="Digite o PIXEL ID"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][conversionLabel]">Rótulo
                                                                                        de conversão</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][attributes][conversionLabel]"
                                                                                            name="product[pixels][attributes][conversionLabel]"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][amountToSend]">
                                                                                        Valor enviado para o pixel
                                                                                    </label>
                                                                                    <select
                                                                                            name="product[pixels][attributes][amountToSend]"
                                                                                            id="product[pixels][attributes][amountToSend]"
                                                                                    >
                                                                                        <option value="">Selecione
                                                                                        </option>
                                                                                        @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                                                                            <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-span-12">

                                                                                    <div class="space-y-2">

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addGoogleAdsBoleto'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Boleto',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addGoogleAdsPix'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Pix',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div
                                                                                class="hidden rounded-xl bg-neutral-100 p-6"
                                                                                id="taboolaFormContent"
                                                                        >

                                                                            <h4 class="productPixelName [&>img]:h-8">
                                                                                Taboola</h4>
                                                                            <hr class="my-4">

                                                                            <div class="grid grid-cols-12 gap-6">

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][pixel_id]">ID
                                                                                        do pixel</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][pixel_id]"
                                                                                            name="product[pixels][pixel_id]"
                                                                                            placeholder="Digite o PIXEL ID"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][amountToSend]">
                                                                                        Valor enviado para o pixel
                                                                                    </label>
                                                                                    <select
                                                                                            name="product[pixels][attributes][amountToSend]"
                                                                                            id="product[pixels][attributes][amountToSend]"
                                                                                    >
                                                                                        <option value="">Selecione
                                                                                        </option>
                                                                                        @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                                                                            <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-span-12">

                                                                                    <div class="space-y-2">

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addTaboolaBoleto'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Boleto',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addTaboolaPix'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Pix',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div
                                                                                class="hidden rounded-xl bg-neutral-100 p-6"
                                                                                id="outbrainFormContent"
                                                                        >

                                                                            <h4 class="productPixelName [&>img]:h-8">
                                                                                Outbrain</h4>
                                                                            <hr class="my-4">

                                                                            <div class="grid grid-cols-12 gap-6">

                                                                                <div class="col-span-12">
                                                                                    <label for="">ID do pixel</label>
                                                                                    <input type="text" />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="">Valor enviado para o
                                                                                        pixel</label>
                                                                                    <select>
                                                                                        <option value="">Selecione
                                                                                        </option>
                                                                                        <option value="">Valor total
                                                                                            (com juros)
                                                                                        </option>
                                                                                        <option value="">Valor dos
                                                                                            produtos
                                                                                        </option>
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-span-12">

                                                                                    <div class="space-y-2">

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addOutbrainBoleto'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Boleto',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addOutbrainPix'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Pix',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div
                                                                                class="hidden rounded-xl bg-neutral-100 p-6"
                                                                                id="pinterestFormContent"
                                                                        >

                                                                            <h4 class="productPixelName [&>img]:h-8">
                                                                                Pinterest</h4>
                                                                            <hr class="my-4">

                                                                            <div class="grid grid-cols-12 gap-6">

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][pixel_id]">ID
                                                                                        do pixel</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][pixel_id]"
                                                                                            name="product[pixels][pixel_id]"
                                                                                            placeholder="Digite o PIXEL ID"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][tag_id]">Tag
                                                                                        ID</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][attributes][tag_id]"
                                                                                            name="product[pixels][attributes][tag_id]"
                                                                                            placeholder="Digite a tag ID"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][amountToSend]">
                                                                                        Valor enviado para o pixel
                                                                                    </label>
                                                                                    <select
                                                                                            name="product[pixels][attributes][amountToSend]"
                                                                                            id="product[pixels][attributes][amountToSend]"
                                                                                    >
                                                                                        <option value="">Selecione
                                                                                        </option>
                                                                                        @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                                                                            <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-span-12">

                                                                                    <div class="space-y-2">

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addPinterestBoleto'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Boleto',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addPinterestPix'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Pix',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div
                                                                                class="hidden rounded-xl bg-neutral-100 p-6"
                                                                                id="tiktokFormContent"
                                                                        >

                                                                            <h4 class="productPixelName [&>img]:h-8">
                                                                                Tiktok</h4>
                                                                            <hr class="my-4">

                                                                            <div class="grid grid-cols-12 gap-6">

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][pixel_id]">ID
                                                                                        do pixel</label>
                                                                                    <input
                                                                                            type="text"
                                                                                            id="product[pixels][pixel_id]"
                                                                                            name="product[pixels][pixel_id]"
                                                                                            placeholder="Digite o PIXEL ID"
                                                                                    />
                                                                                </div>

                                                                                <div class="col-span-12">
                                                                                    <label for="product[pixels][attributes][amountToSend]">
                                                                                        Valor enviado para o pixel
                                                                                    </label>
                                                                                    <select
                                                                                            name="product[pixels][attributes][amountToSend]"
                                                                                            id="product[pixels][attributes][amountToSend]"
                                                                                    >
                                                                                        <option value="">Selecione
                                                                                        </option>
                                                                                        @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                                                                            <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-span-12">

                                                                                    <div class="space-y-2">

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addTiktokBoleto'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Boleto',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                        @include('components.toggle', [
                                                                                            'id' => 'addTiktokPix'.$affiliate->product->id,
                                                                                            'customInput' => 'pixelToggles',
                                                                                            'label' => 'Pix',
                                                                                            'contentEmpty' => true,
                                                                                        ])

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <button
                                                                        class="addPixel button button-primary mt-8 h-12 w-full gap-1 rounded-full"
                                                                        type="button"
                                                                >
                                                                    @include('components.icon', [
                                                                        'icon' => 'add',
                                                                        'custom' => 'text-xl',
                                                                    ])
                                                                    Adicionar
                                                                </button>
                                                            @endcomponent

                                                            <!-- VIEWS -->
                                                            @component('components.drawer', [
                                                                'id' => 'drawerViewPixel',
                                                                'title' => 'Ver pixel',
                                                                'custom' => 'max-w-2xl translate-x-0',
                                                            ])
                                                                <div class="grid grid-cols-12 gap-x-4 divide-y divide-neutral-100">
                                                                    <div class="col-span-12">
                                                                        <div class="space-y-1 py-4">
                                                                            <h5 class="font-medium">Serviço</h5>
                                                                            <div class="rounded-xl bg-neutral-100 p-4">
                                                                                <p
                                                                                        class="!whitespace-normal"
                                                                                        id="productView[pixels][service]"
                                                                                ></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-span-12">
                                                                        <div class="space-y-1 py-4">
                                                                            <h5 class="font-medium">Nome</h5>
                                                                            <div class="rounded-xl bg-neutral-100 p-4">
                                                                                <p
                                                                                        class="!whitespace-normal"
                                                                                        id="productView[pixels][name]"
                                                                                ></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-span-12">
                                                                        <div class="py-4">
                                                                            <div class="rounded-xl bg-neutral-100 p-6">
                                                                                <div class="grid grid-cols-12 gap-4">
                                                                                    <div class="col-span-12">
                                                                                        <img
                                                                                                class="h-8"
                                                                                                id="productView[pixels][image_url]"
                                                                                        >
                                                                                    </div>
                                                                                    <div class="col-span-12">
                                                                                        <div class="space-y-1">
                                                                                            <h5 class="font-medium">
                                                                                                Pixel ID</h5>
                                                                                            <div class="rounded-xl bg-white p-4">
                                                                                                <p
                                                                                                        class="!whitespace-normal"
                                                                                                        id="productView[pixels][pixel_id]"
                                                                                                ></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-span-12">
                                                                                        <div class="space-y-1">
                                                                                            <h5 class="font-medium">
                                                                                                Valor enviado para o
                                                                                                pixel</h5>
                                                                                            <div class="rounded-xl bg-white p-4">
                                                                                                <p
                                                                                                        class="!whitespace-normal"
                                                                                                        id="productView[pixels][amountToSend]"
                                                                                                ></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-span-12">
                                                                                        <div id="payment-methods"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endcomponent
                                                        @endpush
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

            @if ($productsAffiliate instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $productsAffiliate->links() }}
            @endif

        </div>

        @push('custom-script')
            <script>
                $(document).on("change", ".pixelServiceSelect", function() {
                    let selectElement    = this;
                    let selectedOption   = this.options[this.selectedIndex];
                    let pixelServiceName = selectedOption.getAttribute("data-pixelServiceName");
                    let drawer             = selectElement.closest('.drawerAddPixel')

                    let contents = drawer.querySelectorAll("[id$=\"FormContent\"]");
                    contents.forEach(function(content) {
                        // Esconde todos os conteúdos
                        content.style.display = "none";

                        // Limpa os campos do formulário correspondente
                        let inputs = content.querySelectorAll("input, textarea, select");
                        inputs.forEach(function(input) {
                            input.value = ""; // Reseta o valor do campo
                            if (input.type === "checkbox" || input.type === "radio") {
                                input.checked = false; // Reseta checkboxes e radios
                            }
                        });
                    });

                    if (selectedOption.value !== "") {
                        let contentId = pixelServiceName + "FormContent";
                        let contentElement = drawer.querySelector('#'+contentId);
                        if (contentElement) {
                            contentElement.style.display = "block";
                        }
                    }

                    let svgIcon = selectedOption.getAttribute("data-icon");
                    let iconElement = drawer.querySelector(".select-icon");

                    // Atualiza o ícone
                    iconElement.src = svgIcon;

                    // Adiciona ou remove o padding-left (pl-16)
                    if (selectedOption.value === "") {
                        iconElement.style.display = "none";
                        selectElement.classList.remove("pl-16");
                    } else {
                        iconElement.style.display = "block";
                        selectElement.classList.add("pl-16");
                    }

                    let contentId = pixelServiceName + "FormContent";

                    $(`#${contentId} .productPixelName`).html(`<img src="${svgIcon}" alt="Product Pixel"/>`);
                });
            </script>

            <script>
                const keyInputPixel = "pixels";

                // Função para limpar os dados do drawer
                function clearDrawerData() {
                    let drawer = $(".drawerAddPixel");

                    // Limpa inputs de texto, número e textarea
                    drawer.find("input[type='text'], input[type='number'], textarea").val("");
                    // Desmarca checkboxes e radio buttons
                    drawer.find("input[type='checkbox'], input[type='radio']").prop("checked", false);
                    // Reseta o valor de selects
                    drawer.find("select").val("0");

                    // Esconde todos os conteúdos que terminam com 'FormContent'
                    let contents = document.querySelectorAll("[id$=\"FormContent\"]");
                    contents.forEach(function(content) {
                        content.style.display = "none";
                    });

                    // Esconde o ícone de seleção
                    $(".select-icon").hide();

                    // Remove a classe 'pl-16' de todos os elementos com a classe 'pixelServiceSelect'
                    $(".pixelServiceSelect").each(function() {
                        this.classList.remove("pl-16");
                    });

                    // Atualiza os textos do botão e do título do drawer
                    drawer.find(".addPixel").text("Adicionar pixel");
                    drawer.find(".titleDrawer").text("Adicionar Pixel");
                }

                // view pixel
                $(document).on("click", ".viewPixel", function() {
                    const drawer = $("#drawerViewPixel");
                    const data = $(this).data("pixel");

                    // Função para definir o texto de um campo no drawer
                    const setTextField = (field, value) => {
                        drawer.find(`#productView\\[${keyInputPixel}\\]\\[${field}\\]`).text(value);
                    };

                    const valueSentToThePixel = {
                        "AMOUNT_TOTAL_WITH_FEE": "Volor total (com juros)",
                        "AMOUNT_TOTAL_PRODUCTS": "Valor dos produtos"
                    };

                    setTextField("service", data.pixel_service.name);
                    setTextField("name", data.name);
                    setTextField("pixel_id", data.pixel_id);
                    setTextField("amountToSend", valueSentToThePixel[data.attributes.amountToSend]);

                    const paymentLabels = {
                        "mark_billet": "Boleto Bancário",
                        "mark_pix": "Pix"
                    };

                    const paymentTemplate = label => `
                        <li class="flex items-center gap-3">
                        <div class="flex h-5 w-5 items-center justify-center rounded-sm bg-primary text-white">
                        @include('components.icon', ['icon' => 'check', 'custom' => 'text-xl'])
                        </div>
                        ${label}
                        </li>
                    `;

                    const renderPaymentMethods = methods => `
                                                                        <ul class="space-y-1">
                                                                            ${Object.entries(methods)
                        .filter(([_, isActive]) => isActive)
                        .map(([method]) => paymentTemplate(paymentLabels[method] || method))
                        .join("")}
                                                                        </ul>
                                                                    `;

                    document.querySelector("#payment-methods").innerHTML = renderPaymentMethods({
                        "mark_billet": data.mark_billet,
                        "mark_pix": data.mark_pix
                    });

                    drawer.find(`#productView\\[pixels\\]\\[image_url\\]`).attr("src", data.pixel_service.image_url);
                });

                // add
                $(document).on("click", ".addPixel", function () {
                    const drawer = this.closest('.drawerAddPixel');
                    const productId = drawer.id.replace('drawerAddPixel', '');
                    const $table = $(`#tablePixels${productId}`);
                    const $inputs = drawer.querySelectorAll(".inputsPixels input, .inputsPixels select");
                    const idPixel = document.querySelector(`#product\\[${keyInputPixel}\\]\\[id\\]`)?.value;
                    const isEdit = !!idPixel;

                    const isValid = Array.from($inputs).every(input => !input.required || input.value);

                    if (!isValid) {
                        notyf.info("Preencha todos os campos obrigatórios");
                        return false;
                    }

                    const pixelServiceText = drawer.querySelector(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\] option:checked`)?.textContent || '';
                    const pixelName = drawer.querySelector(`#product\\[${keyInputPixel}\\]\\[name\\]`)?.value || '';

                    if (isEdit) {
                        const $tr = $table.find(`tbody tr[data-id="${idPixel}"]`);
                        $tr.find("td").eq(0).text(pixelServiceText);
                        $tr.find("td").eq(1).text(pixelName);

                        for (const input of $inputs) {
                            if (input.name && input.value) {
                                if ((input.type === "radio" || input.type === "checkbox") && !input.checked) continue;

                                $tr.append(
                                    `<input type="hidden" name="${input.name.replace(`product[${keyInputPixel}]`, `product[${keyInputPixel}][${idPixel}]`)}" value="${input.value}" />`
                                );
                            }
                        }

                        $($inputs).filter("input[type='text']").val("");
                        $(`#drawerAddPixel${productId} .closeButton`).trigger("click");
                        return;
                    }

                    // Adiciona nova linha
                    $table.find("tbody").append(`
                        <tr>
                            <td>${pixelServiceText}</td>
                            <td>${pixelName}</td>
                            <td class="text-end">
                                <div class="inline-block w-fit">
                                    <button
                                        class="justify-center flex h-8 w-8 items-center rounded-lg hover:bg-neutral-200"
                                        onclick="this.closest('tr').remove()"
                                        type="button"
                                    >
                                        @include('components.icon', ['icon' => 'close', 'custom' => 'text-xl'])
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);

                    const index = $table.find("tbody tr").length - 1;

                    for (const input of $inputs) {
                        if (input.name && input.value) {
                            if ((input.type === "radio" || input.type === "checkbox") && !input.checked) continue;

                            $table.find("tbody tr").eq(index).append(
                                `<input type="hidden" name="${input.name.replace(`product[${keyInputPixel}]`, `product[${keyInputPixel}][${index}]`)}" value="${input.value}" />`
                            );
                        }
                    }

                    clearDrawerData();
                    $(`#drawerAddPixel${productId} .closeButton`).trigger("click");
                });

                // editar
                $(document).on("click", ".editPixel", function() {
                    let nameDrawer = $(this).data("drawerTarget");
                    let productId = nameDrawer.replace('drawerAddPixel', '')
                    let drawer = $(`#${nameDrawer}`);

                    let dataPixel = $(this).data("pixel");
                    let slugService = dataPixel.pixel_service.name.replace(/\s/g, "").toLowerCase();
                    let divDrawerAttributes = drawer.find(`#${slugService}FormContent`);

                    drawer.find(".titleDrawer").text("Editar dados pixel");

                    drawer.find(`#product\\[${keyInputPixel}\\]\\[id\\]`).val(dataPixel.id);
                    drawer.find(`#product\\[${keyInputPixel}\\]\\[name\\]`).val(dataPixel.name);

                    drawer.find(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\] option`).prop("selected", false);
                    drawer.find(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\] option[value="${dataPixel.pixel_service_id}"]`).prop("selected", true);
                    drawer.find(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\]`).trigger("change");

                    drawer.find(`#product\\[${keyInputPixel}\\]\\[mark_billet\\]`).val(dataPixel.mark_billet);
                    drawer.find(`#product\\[${keyInputPixel}\\]\\[mark_pix\\]`).val(dataPixel.mark_pix);
                    drawer.find(`#product\\[${keyInputPixel}\\]\\[attributes\\]\\[backend_purchase\\]`).val(dataPixel.attributes.backend_purchase || 0);

                    if (slugService == "facebook") {
                        divDrawerAttributes.find(`#addFacebookBoleto`+productId).prop("checked", Boolean(dataPixel.mark_billet));
                        divDrawerAttributes.find(`#addFacebookPix`+productId).prop("checked", Boolean(dataPixel.mark_pix));
                        divDrawerAttributes.find(`#addFacebookBackendPurchase`+productId).prop("checked", Boolean(parseInt(dataPixel.attributes.backend_purchase)));
                    }
                    if (slugService == "google") {
                        divDrawerAttributes.find(`#addGoogleAdsBoleto`+productId).prop("checked", Boolean(dataPixel.mark_billet));
                        divDrawerAttributes.find(`#addGoogleAdsPix`+productId).prop("checked", Boolean(dataPixel.mark_pix));
                    }
                    if (slugService == "taboola") {
                        divDrawerAttributes.find(`#addTaboolaBoleto`+productId).prop("checked", Boolean(dataPixel.mark_billet));
                        divDrawerAttributes.find(`#addTaboolaPix`+productId).prop("checked", Boolean(dataPixel.mark_pix));
                    }
                    if (slugService == "outbrain") {
                        divDrawerAttributes.find(`#addOutbrainBoleto`+productId).prop("checked", Boolean(dataPixel.mark_billet));
                        divDrawerAttributes.find(`#addOutbrainPix`+productId).prop("checked", Boolean(dataPixel.mark_pix));
                    }
                    if (slugService == "pinterest") {
                        divDrawerAttributes.find(`#addPinterestBoleto`+productId).prop("checked", Boolean(dataPixel.mark_billet));
                        divDrawerAttributes.find(`#addPinterestPix`+productId).prop("checked", Boolean(dataPixel.mark_pix));
                    }
                    if (slugService == "tiktok") {
                        divDrawerAttributes.find(`#addTiktokBoleto`+productId).prop("checked", Boolean(dataPixel.mark_billet));
                        divDrawerAttributes.find(`#addTiktokPix`+productId).prop("checked", Boolean(dataPixel.mark_pix));
                    }

                    divDrawerAttributes.find(`#product\\[${keyInputPixel}\\]\\[pixel_id\\]`).val(dataPixel.attributes.pixel_id);

                    if (dataPixel.attributes) {
                        for (let key in dataPixel.attributes) {
                            let value = dataPixel.attributes[key];
                            let input = divDrawerAttributes.find(`#product\\[${keyInputPixel}\\]\\[attributes\\]\\[${key}\\]`);

                            if (input.is("select")) {
                                input.find(`option[value="${value}"]`).prop("selected", true);
                            } else {
                                input.val(value);
                            }
                        }
                    }

                    drawer.find(".addPixel").text("Atualizar");
                });

                // close modal
                $(document).on("click", ".closeButton, [drawer-backdrop]", function() {
                    clearDrawerData();
                });
            </script>

            <script>
                $(document).on("change", ".pixelToggles, #addFacebookBoleto, #addFacebookPix, #addFacebookBackendPurchase, #addGoogleAdsBoleto, #addGoogleAdsPix, #addTaboolaBoleto, #addTaboolaPix, #addOutbrainBoleto, #addOutbrainPix, #addPinterestBoleto, #addPinterestPix, #addTiktokBoleto, #addTiktokPix", function() {
                    let $this = $(this);
                    let textId = $this.attr("id");
                    let isChecked = $this.is(":checked");

                    if (textId.includes("Boleto")) {
                        $(".inputsPixels input[name='product[pixels][mark_billet]']").val(isChecked ? 1 : 0);
                    } else if (textId.includes("Pix")) {
                        $(".inputsPixels input[name='product[pixels][mark_pix]']").val(isChecked ? 1 : 0);
                    } else if (textId.includes("BackendPurchase")) {
                        $(".inputsPixels input[name='product[pixels][attributes][backend_purchase]']").val(isChecked ? 1 : 0);
                    }
                });
            </script>
        @endpush

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
