@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">

            <h1>Produtos</h1>

            @can('product_create')
                <button class="button button-primary h-12 gap-1 rounded-full" data-modal-target="modalAddProduct"
                    data-modal-toggle="modalAddProduct" type="button">
                    @include('components.icon', [
                        'icon' => 'add',
                        'custom' => 'text-xl',
                    ])
                    Adicionar produto
                </button>
            @endcan

        </div>

        <nav class="flex items-center border-b border-neutral-300">

            <a href="{{ route('dashboard.products.index') }}" title="Produtos lojista"
                class="border-b-2 border-primary px-6 py-4 hover:border-primary">
                Meus produtos
            </a>

            <a href="{{ route('dashboard.coproducers.productsCoproducer') }}" title="Produtos co-participações"
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800">
                Minhas coproduções
            </a>

            <a href="{{ route('dashboard.affiliates.productsAffiliate') }}" title="Produtos afiliado"
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800">
                Minhas afiliações
            </a>

        </nav>

        <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">

            <form class="w-full flex-1" action="{{ route('dashboard.affiliates.productsAffiliate') }}" method="GET">

                <div class="grid grid-cols-12 gap-4">

                    <div class="col-span-12">

                        <div class="append">

                            <input type="text" name="filter[name]" value="{{ request()->input('filter.name') }}"
                                placeholder="Pesquisar" />

                            <button class="append-item-right w-12" type="button">
                                @include('components.icon', ['icon' => 'search'])
                            </button>

                        </div>

                    </div>

                </div>

            </form>

            <button class="button button-outline-primary h-12 w-full gap-1 md:w-auto"
                data-drawer-target="drawerFilterProducts" data-drawer-show="drawerFilterProducts"
                data-drawer-placement="right" type="button">
                @include('components.icon', [
                    'icon' => 'filter_alt',
                    'type' => 'fill',
                    'custom' => 'text-xl',
                ])
                Filtros de pesquisa
            </button>

        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

            @forelse ($productsShop as $product)
                <div class="col-span-1">
                    @component('components.card')
                        <div class="relative">

                            <figure
                                class="flex h-[240px] w-full items-center justify-center overflow-hidden rounded-t-xl bg-white">

                                <span
                                    class="{{ \App\Enums\SituationProductEnum::getClassBackground($product->situation) }} absolute left-3 top-3 rounded-full px-2 py-1 text-[10px] font-semibold uppercase">{{ $product->situationTranslated }}</span>

                                <img class="h-full w-full object-cover" src="{{ $product->featuredImageUrl }}"
                                    alt="{{ $product->name }}" loading="lazy" />

                            </figure>

                            <div class="absolute right-3 top-3">

                                @component('components.dropdown-button', [
                                    'id' => 'dropdownMoreProducts' . $product->client_product_uuid,
                                    'customButton' => 'w-8 h-8 rounded-full bg-white',
                                    'custom' => 'text-xl',
                                ])
                                    <ul>
                                        <li>
                                            <a class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                title="Editar produto {{ $product->name }}"
                                                href="{{ route('dashboard.products.edit', ['productUuid' => $product->client_product_uuid]) }}">
                                                Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                title="Editar produto {{ $product->name }}"
                                                href="{{ url(route('dashboard.products.edit', ['productUuid' => $product->client_product_uuid]) . '#tab=tab-links') }}">
                                                Ver links
                                            </a>
                                        </li>

                                        @if (!$product->isInAnalysis)
                                            <hr class="my-1 border-neutral-100">
                                            <li>
                                                <form
                                                    action="{{ route($product->isDisable ? 'dashboard.products.enable' : 'dashboard.products.disable', $product) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button
                                                        class="flex w-full items-center rounded-lg px-3 py-2 text-sm text-danger-500 hover:bg-danger-50"
                                                        type="submit"
                                                        @if (!$product->isDisable) onclick="return confirm('Tem certeza que deseja desatiar o produto {{ $product->name }}?')" @endif>
                                                        {{ $product->isDisable ? 'Reativar' : 'Desativar' }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endif

                                    </ul>
                                @endcomponent

                            </div>

                            <div class="p-4">

                                <span
                                    class="copyClipboard cursor-pointer rounded-full bg-neutral-200 px-2 py-[3px] text-[10px] font-semibold"
                                    data-tooltip-text="Click para copiar o ID : <br> {{ $product->client_product_uuid }}"
                                    data-tooltip-position="right" data-clipboard-text="{{ $product->client_product_uuid }}">
                                    <i class="ti ti-key text-1xl"></i>
                                    <i class="ti ti-copy text-1xl"></i>
                                </span>

                                <h4 class="font-semibold">{{ \Illuminate\Support\Str::limit($product->name, 25) }}</h4>
                                <p class="text-xs text-neutral-500">
                                    a partir de <span
                                        class="text-sm font-semibold text-primary">{{ Number::currency($product->minPriceOffers, 'BRL', 'pt-br') }}</span>
                                </p>

                            </div>

                        </div>
                    @endcomponent
                </div>
            @empty
                <div class="col-span-12 rounded-lg bg-gray-50 p-4 text-center text-sm text-gray-800 dark:bg-gray-800 dark:text-gray-300"
                    role="alert">
                    Sem registros.
                </div>
            @endforelse

        </div>

        @if ($productsShop instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $productsShop->links() }}
        @endif

    </div>
@endsection

@push('floating')
    <!-- MODAL ADD PRODUCT -->
    @component('components.modal', [
        'id' => 'modalAddProduct',
        'title' => 'Adicionar produto',
    ])
        <form action="{{ route('dashboard.products.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-12">
                    <label for="name">
                        Nome do produto
                    </label>
                    <input placeholder="Digite o nome do produto" type="text" name="name" id="name" minlength="3"
                        class="{{ $errors->has('name') ? 'border-danger-500' : '' }}" required />

                    @error('name')
                        <span class="text-sm text-danger-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-12">
                    <label for="type">Selecione uma categoria</label>
                    <select id="category" name="category_id">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="button button-primary ml-auto mt-4 h-12 gap-1 rounded-full" type="submit">
                Continuar
                @include('components.icon', [
                    'icon' => 'arrow_forward',
                    'custom' => 'text-xl',
                ])
            </button>
        </form>
    @endcomponent

    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterProducts',
        'title' => 'Pesquisar de produtos',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="{{ route('dashboard.products.index') }}" method="GET">

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="filter[name]">Nome</label>
                    <input type="text" id="filter[name]" name="filter[name]" value="{{ request()->input('filter.name') }}"
                        placeholder="Digite o nome" />
                </div>
                <div class="col-span-12">
                    <label for="filter[client_product_uuid]">ID</label>
                    <input type="text" id="filter[client_product_uuid]" name="filter[client_product_uuid]"
                        value="{{ request()->input('filter.client_product_uuid') }}" placeholder="Digite o id" />
                </div>
                <div class="col-span-12">
                    <label for="filter[situation]">Status</label>
                    <select id="filter[situation]" name="filter[situation]">
                        <option value="">Selecione o status</option>
                        @foreach (\App\Enums\SituationProductEnum::getDescriptions() as $item)
                            <option value="{{ $item['value'] }}" @selected(request()->input('filter.situation') == $item['value'])>
                                {{ $item['description'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="filter[category_id]">Categoria</label>
                    <select id="filter[category_id]" name="filter[category_id]">
                        <option value="">Selecione a categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request()->input('filter.category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="filter[created_at]">Data de cadastro</label>
                    <input type="date" id="filter[created_at]" name="filter[created_at]"
                        value="{{ request()->input('filter.created_at') }}" />
                </div>
            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Pesquisar
            </button>

        </form>
    @endcomponent

    @component('components.drawer', [
        'id' => 'drawerFilterCoParticipations',
        'title' => 'Pesquisar por Co-Participações',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="" method="">

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="">Nome</label>
                    <input type="text" placeholder="Digite o nome">
                </div>
                <div class="col-span-12">
                    <label for="">Categoria</label>
                    <select>
                        <option value="">Selecione a categoria</option>
                    </select>
                </div>
                <div class="col-span-12">
                    <label for="">Data de cadastro</label>
                    <input type="date" placeholder="Digite">
                </div>
                <div class="col-span-12">
                    <label for="">Status do produto</label>
                    <select>
                        <option value="">Selecione o status</option>
                    </select>
                </div>
            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Pesquisar
            </button>

        </form>
    @endcomponent

    @component('components.drawer', [
        'id' => 'drawerFilterAffiliations',
        'title' => 'Pesquisar por Afiliados',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="" method="">

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="">Nome</label>
                    <input type="text" placeholder="Digite o nome">
                </div>
                <div class="col-span-12">
                    <label for="">Categoria</label>
                    <select>
                        <option value="">Selecione a categoria</option>
                    </select>
                </div>
                <div class="col-span-12">
                    <label for="">Data de cadastro</label>
                    <input type="date" placeholder="Digite">
                </div>
                <div class="col-span-12">
                    <label for="">Status do produto</label>
                    <select>
                        <option value="">Selecione o status</option>
                    </select>
                </div>
            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Pesquisar
            </button>

        </form>
    @endcomponent

    {{-- MODAL SUCCESS --}}
    @if (session('modalMessage'))
        @component('components.modal', [
            'id' => 'successModal',
            'title' => '',
        ])
            <div class="px-20 pb-20">
                <div id="lottie-animation" style="width: 200px; height: 200px; margin: auto;"></div>
                <h3 class="mb-2 text-center font-semibold">Parabéns!!</h3>
                <p class="text-center">{!! session('modalMessage') !!}</p>
            </div>
        @endcomponent

        <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.0/lottie.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('successModal');
                const modalInstance = new Modal(modal);
                modalInstance.show();
            });

            // Caminho gerado pelo Laravel
            const lottieAnimationPath = "{{ asset('images/dashboard/animates/success.json') }}";

            // Inicializar a animação
            lottie.loadAnimation({
                container: document.getElementById('lottie-animation'), // Elemento onde a animação será renderizada
                renderer: 'svg', // Renderizador (svg, canvas, html)
                loop: false, // Define se a animação deve ser reproduzida em loop
                autoplay: true, // Define se a animação começa automaticamente
                path: lottieAnimationPath // Caminho para o arquivo JSON
            });
        </script>
    @endif

    {{-- MODAL ERROR PRODUCT --}}
    @if (session('modalMessageErrorProduct'))
        @component('components.modal', [
            'id' => 'errorModal',
            'title' => '',
        ])
            <div class="pb-12">
                <div id="lottie-animation" style="width: 180px; height: 180px; margin: 0 auto 12px;"></div>
                <h3 class="mb-8 text-center font-semibold">Produto Reprovado</h3>
                <ul class="mx-auto max-w-lg space-y-4 rounded-xl bg-neutral-100 px-6 py-6">
                    <li>
                        <p class=""><strong>Informações Incompletas ou Inválidas:</strong></p>
                        <p class="text-sm">O produto foi reprovado porque as informações fornecidas no cadastro estão
                            incompletas ou contêm erros, como nome, descrição ou preço incorretos.</p>
                    </li>
                    <li>
                        <p class=""><strong>Problemas com a Precificação:</strong></p>
                        <p class="text-sm">O produto foi marcado como reprovado devido a erros no preço, como preço negativo ou
                            discrepância com as regras de precificação definidas.</p>
                    </li>
                    <li>
                        <p class=""><strong>Violação de Políticas de Venda:</strong></p>
                        <p class="text-sm">O produto não atende aos termos e condições da plataforma, como categorias
                            restritas, produtos proibidos ou conteúdo inadequado.</p>
                    </li>
                </ul>
                <a class="button button-primary mx-auto mt-8 h-12 w-fit rounded-full" href="#">
                    Editar produto
                </a>
            </div>
        @endcomponent

        <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.0/lottie.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('errorModal');
                const modalInstance = new Modal(modal);
                modalInstance.show();
            });

            // Caminho gerado pelo Laravel
            const lottieAnimationPath = "{{ asset('images/dashboard/animates/error-product.json') }}";

            // Inicializar a animação
            lottie.loadAnimation({
                container: document.getElementById('lottie-animation'), // Elemento onde a animação será renderizada
                renderer: 'svg', // Renderizador (svg, canvas, html)
                loop: true, // Define se a animação deve ser reproduzida em loop
                autoplay: true, // Define se a animação começa automaticamente
                path: lottieAnimationPath // Caminho para o arquivo JSON
            });
        </script>
    @endif
@endpush

@push('custom-script')
    <script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>
@endpush
