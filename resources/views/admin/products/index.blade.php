@extends('layouts.new-admin', ['title' => 'Aprovar produtos'])

@section('content')
    <div class="grid grid-cols-12 md:gap-x-6">

        <div class="col-span-12">

            <div class="box overflow-hidden">

                <div class="box-header flex items-center justify-between">

                    <div class="box-title font-semibold before:hidden">Lista de produtos</div>

                    @component('components.admin.ui.drawer', [
                        'id' => 'filterProducts',
                        'btnTitle' => 'Filtro de pesquisa',
                        'drawerTitle' => 'Filtrar por',
                    ])
                        <div class="!p-4">

                            <form
                                action="{{ route('admin.products.index') }}"
                                method="GET"
                            >

                                <div class="grid grid-cols-12 gap-4">

                                    <div class="col-span-12">
                                        <label
                                            class="form-label"
                                            for="filter[name]"
                                        >
                                            Nome do produto
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="filter[name]"
                                            name="filter[name]"
                                            value="{{ request('filter.name') }}"
                                            placeholder="Digite o nome do produto"
                                        />
                                    </div>

                                    <div class="col-span-12">
                                        <label
                                            class="form-label"
                                            for="filter[shop]"
                                        >
                                            Nome da loja
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="filter[shop]"
                                            name="filter[shop]"
                                            value="{{ request('filter.shop') }}"
                                            placeholder="Digite o nome da loja"
                                        />
                                    </div>

                                    <div class="col-span-12">
                                        <label
                                            class="form-label"
                                            for="filter[ownerShop]"
                                        >
                                            Nome do autor / Username
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="filter[ownerShop]"
                                            name="filter[ownerShop]"
                                            value="{{ request('filter.ownerShop') }}"
                                            placeholder="Digite o nome do autor ou o Username"
                                        />
                                    </div>

                                    <div class="col-span-12">
                                        <label
                                            class="form-label"
                                            for="filter[situation]"
                                        >
                                            Situação
                                        </label>
                                        <select
                                            class="form-select form-control"
                                            id="filter[situation]"
                                            name="filter[situation]"
                                        >
                                            <option value="">Selecione</option>
                                            @foreach (\App\Enums\SituationProductEnum::getDescriptions() as $situation)
                                                <option
                                                    value="{{ $situation['value'] }}"
                                                    @selected(request('filter.situation') == $situation['value'])
                                                >
                                                    {{ $situation['description'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <button
                                    class="ti-btn ti-btn-primary-full ti-btn-wave mt-6 w-full"
                                    type="submit"
                                >
                                    Pesquisar
                                </button>

                            </form>

                        </div>
                    @endcomponent

                </div>

                <div class="box-header">

                    <div class="mr-auto flex items-center gap-px">
                        <a
                            class="ti-btn text-yellow-700 ti-btn-wave mb-0 !rounded-full border-0"
                            href="{{ route('admin.products.index') }}?filter[situation]=IN_ANALYSIS"
                            title="Ver produtos em análise"
                        >
                            Em análise
                        </a>

                        <a
                            class="ti-btn ti-btn-ghost-success ti-btn-wave mb-0 !rounded-full border-0"
                            href="{{ route('admin.products.index') }}?filter[situation]=PUBLISHED"
                            title="Ver produtos aprovados"
                        >
                            Aprovados
                        </a>

                        <a
                            class="ti-btn ti-btn-ghost-danger ti-btn-wave mb-0 !rounded-full border-0"
                            href="{{ route('admin.products.index') }}?filter[situation]=REPROVED"
                            title="Ver produtos reprovados"
                        >
                            Reprovados
                        </a>

                        <a
                            class="ti-btn text-blue-800 ti-btn-wave mb-0 !rounded-full border-0"
                            href="{{ route('admin.products.index') }}?filter[situation]=DRAFT"
                            title="Ver produtos em rascunho"
                        >
                            Rascunho
                        </a>

                        <a
                            class="ti-btn text-secondary ti-btn-wave mb-0 !rounded-full border-0"
                            href="{{ route('admin.products.index') }}?filter[withRevisionPending]=1"
                            title="Ver produtos com revisão(oes) pendentes"
                        >
                            Publicado (Alterações Pendentes)
                            <span class="inline-flex items-center justify-center w-4 h-4 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
                                {{ $quantityProductsWithRevisionsPending }}
                            </span>
                        </a>

                        <a
                            class="ti-btn ti-btn-outline-light ti-btn-wave mb-0 !rounded-full border-0"
                            href="{{ route('admin.products.index') }}"
                            title="Ver todos os produtos"
                        >
                            Todos
                        </a>
                    </div>

                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                            <thead>
                                <tr class="border-defaultborder border-b [&>th]:!px-6 [&>th]:text-start">
                                    <th class="!w-10">#</th>
                                    <th class="!w-20"></th>
                                    <th>Nome do produto</th>
                                    <th>Situação</th>
                                    <th>Loja</th>
                                    <th>Autor</th>
                                    <th>Cadastrado em</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($products as $product)
                                    <tr class="[&>td]:!px-6 [&>td]:text-start">
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            <figure class="relative h-14 w-20 overflow-hidden rounded-md">
                                                <img
                                                    class="absolute h-full w-full object-cover"
                                                    src="{{ $product->featuredImageUrl }}"
                                                    alt="{{ $product->name }}"
                                                    loading="lazy"
                                                />
                                            </figure>
                                        </td>
                                        <td>
                                            {{ $product->name }}
                                        </td>
                                        <td>
                                            <span class="{{ \App\Enums\SituationProductEnum::productSituationGetClassAdmin($product->situation) }} rounded-full px-4 py-2">
                                                {{ $product->situationTranslated }}
                                            </span>
                                        </td>
                                        <td>
                                            <p>{{ $product->shop->name }}</p>
                                            @if ($product->getValueSchemalessAttributes('externalSalesLink'))
                                                <a
                                                    class="text-primary"
                                                    target="_blank"
                                                    title="Abrir loja da loja {{ $product->shop->name }}"
                                                    href="{{ $product->getValueSchemalessAttributes('externalSalesLink') }}"
                                                >
                                                    {{ Str::limit($product->getValueSchemalessAttributes('externalSalesLink'), 30) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <p>{{ $product->shop->owner->name }}</p>
                                            <p class="text-muted">
                                                {{ $product->shop->username_banking ? '@' . $product->shop->username_banking : 'User Demo' }}                                               
                                            </p>
                                        </td>
                                        <td>{{ $product->created_at->isoFormat('DD/MM/YY HH:mm') }}</td>
                                        <td>
                                            @component('components.admin.ui.dropdown', ['icon' => 'chevron-down'])
                                                <li>
                                                    <a
                                                        class="ti-dropdown-item block !px-[0.9375rem] !py-2 !text-[0.8125rem] !font-medium"
                                                        title="Ver detalhes do produto"
                                                        href="{{ route('admin.products.show', $product) }}"
                                                    >
                                                        Ver detalhes
                                                    </a>
                                                </li>
                                                @if ($product->isInAnalysis)
                                                    <li>
                                                        <a
                                                            class="ti-dropdown-item block !px-[0.9375rem] !py-2 !text-[0.8125rem] !font-medium"
                                                            title="Aprovar produto"
                                                            href="{{ route('admin.products.review', $product) }}"
                                                        >
                                                            Aprovar
                                                        </a>
                                                    </li>
                                                @endif

                                                <li>
                                                    <a
                                                        class="ti-dropdown-item block !px-[0.9375rem] !py-2 !text-[0.8125rem] !font-medium"
                                                        title="Revisões"
                                                        href="{{ route('admin.products.revisions', $product) }}"
                                                    >
                                                        Revisões
                                                        <span class="inline-flex items-center justify-center w-4 h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
                                                            {{ $product->revisions_count }}
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcomponent
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="[&>td]:!px-6 [&>td]:text-start">
                                        <td
                                            class="text-center"
                                            colspan="7"
                                        >
                                            Nenhum produto encontrado
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if ($products->hasPages())
                    <div class="box-footer">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
