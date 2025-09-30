@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Afiliados</h1>

        <nav class="flex items-center border-b border-neutral-300">

            <a
                href="{{ route('dashboard.affiliates.index', ['filter[situation]' => \App\Enums\SituationAffiliateEnum::ACTIVE->value]) }}"
                title="Listagem de afiliados ativos"
                @class([
                    'border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800',
                    'border-primary' =>
                        request()->input('filter.situation') ===
                            \App\Enums\SituationAffiliateEnum::ACTIVE->value ||
                        !request('filter.situation'),
                ])
            >
                Ativo
            </a>

            <a
                href="{{ route('dashboard.affiliates.index', ['filter[situation]' => \App\Enums\SituationAffiliateEnum::PENDING->value]) }}"
                title="Listagem de afiliados pendentes"
                @class([
                    'border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800',
                    'border-primary' =>
                        request()->input('filter.situation') ===
                        \App\Enums\SituationAffiliateEnum::PENDING->value,
                ])
            >
                Pendente
            </a>

            <a
                href="{{ route('dashboard.affiliates.index', ['filter[situation]' => \App\Enums\SituationAffiliateEnum::CANCELED->value]) }}"
                title="Listagem de afiliados cancelados"
                @class([
                    'border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800',
                    'border-primary' =>
                        request()->input('filter.situation') ===
                        \App\Enums\SituationAffiliateEnum::CANCELED->value,
                ])
            >
                Reprovados
            </a>

        </nav>

        <div id="page-tab-content">

            <button
                class="button button-outline-primary mb-6 ml-auto h-12 w-full gap-1 md:w-auto"
                data-drawer-target="drawerFilterAffiliationsActive"
                data-drawer-show="drawerFilterAffiliationsActive"
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

            @component('components.card', ['custom' => 'overflow-hidden'])
                <div class="overflow-x-scroll md:overflow-visible">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Usuário</th>
                                <th>Produto</th>
                                <th>Comissão</th>
                                <th>Situação</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($affiliates as $affiliate)
                                <tr>
                                    <td>{{ $affiliate->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <p>{{ $affiliate->user->name }}</p>
                                        <p>{{ $affiliate->user->email }}</p>
                                    </td>
                                    <td>{{ $affiliate?->product?->name }}</td>
                                    <td>{{ $affiliate->formattedValue }}</td>
                                    <td>
                                        <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                            @include('components.icon', [
                                                'icon' => 'circle',
                                                'type' => 'fill',
                                                'custom' => 'text-xs ' . \App\Enums\SituationAffiliateEnum::getClass($affiliate->situation),
                                            ])
                                            {{ $affiliate->situationFormatted }}
                                        </div>
                                    </td>
                                    <td class="text-right">

                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreTableParticipations' . $loop->index,
                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>

                                                <li>
                                                    <button
                                                        class="updateAffiliate flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                        data-affiliate="{{ $affiliate }}"
                                                        data-urlUpdate="{{ route('dashboard.affiliates.update', $affiliate) }}"
                                                        data-drawer-target="drawerUpdateAffiliation"
                                                        data-drawer-show="drawerUpdateAffiliation"
                                                        data-drawer-placement="right"
                                                        title="Editar"
                                                        type="button"
                                                    >
                                                        Editar
                                                    </button>
                                                </li>

                                                @if ($affiliate->isPending)
                                                    <li>
                                                        <form
                                                            action="{{ route('dashboard.affiliates.approve', $affiliate) }}"
                                                            method="POST"
                                                        >
                                                            @csrf
                                                            @method('PUT')

                                                            <button
                                                                class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                                type="submit"
                                                                onclick="return confirm('Tem certeza que deseja aprovar a afiliação do {{ $affiliate->user->name }} no produto {{ $affiliate->product->name }}?')"
                                                            >
                                                                Aprovar
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif

                                                @if ($affiliate->isCanceled)
                                                    <li>
                                                        <form
                                                            action="{{ route('dashboard.affiliates.reactive', $affiliate) }}"
                                                            method="POST"
                                                        >
                                                            @csrf
                                                            @method('PUT')

                                                            <button
                                                                class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                                type="submit"
                                                                onclick="return confirm('Tem certeza que deseja reativar a afiliação do {{ $affiliate->user->name }} no produto {{ $affiliate->product->name }}?')"
                                                            >
                                                                Reativar
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif

                                                @if (!$affiliate->isCanceled)
                                                    <li>
                                                        <form
                                                            action="{{ route('dashboard.affiliates.cancel', $affiliate) }}"
                                                            method="POST"
                                                        >
                                                            @csrf
                                                            @method('PUT')

                                                            <button
                                                                class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                                type="submit"
                                                                onclick="return confirm('Tem certeza que deseja cancelar a afiliação do {{ $affiliate->user->name }} no produto {{ $affiliate->product->name }}?')"
                                                            >
                                                                Reprovar
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif

                                                <li>
                                                    <form
                                                        action="{{ route('dashboard.affiliates.destroy', $affiliate) }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                            type="submit"
                                                            onclick="return confirm('Tem certeza que deseja excluir a afiliação do {{ $affiliate->user->name }} no produto {{ $affiliate->product->name }}?')"
                                                        >
                                                            Excluir
                                                        </button>
                                                    </form>
                                                </li>

                                            </ul>
                                        @endcomponent

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div
                                            class="col-span-12 rounded-lg bg-gray-50 p-4 text-center text-sm text-gray-800 dark:bg-gray-800 dark:text-gray-300"
                                            role="alert"
                                        >
                                            Sem registros.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endcomponent

        </div>

    </div>
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterAffiliationsActive',
        'title' => 'Pesquisar por ativo',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="{{ route('dashboard.affiliates.index') }}">

            <input
                type="hidden"
                name="filter[situation]"
                value="{{ request()->input('filter.situation', \App\Enums\SituationAffiliateEnum::ACTIVE->name) }}"
            />

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="filter[user]">Afiliado (nome ou e-mail)</label>
                    <input
                        type="text"
                        id="filter[user]"
                        name="filter[user]"
                        value="{{ request()->input('filter.user') }}"
                        placeholder="Digite o nome ou e-mail"
                    />
                </div>

                <div class="col-span-12">
                    <label for="filter[product]">Nome do produto</label>
                    <input
                        type="text"
                        id="filter[product]"
                        name="filter[product]"
                        value="{{ request()->input('filter.product') }}"
                        placeholder="Digite o nome do produto"
                    />
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

    <!-- DRAWER EDIT -->
    @component('components.drawer', [
        'id' => 'drawerUpdateAffiliation',
        'title' => 'Editar afiliado',
        'custom' => 'max-w-xl',
    ])
        <form
            method="POST"
            action=""
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-12">
                    <label for="">Escolha uma opção</label>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="col-span-1">
                            <label
                                class="mb-0 w-full cursor-pointer"
                                for="affiliationsSelectPercentage"
                            >
                                <input
                                    class="peer hidden"
                                    id="affiliationsSelectPercentage"
                                    name="type"
                                    type="radio"
                                    value="PERCENTAGE"
                                />
                                <div class="flex w-full items-center gap-2 rounded-lg border border-neutral-200 p-6 peer-checked:border-primary peer-checked:[&>span>i]:block peer-checked:[&>span]:border-primary peer-checked:[&>span]:bg-primary">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full border">
                                        @include('components.icon', [
                                            'icon' => 'check',
                                            'custom' => 'text-xl text-white hidden',
                                        ])
                                    </span>
                                    Porcentagem
                                </div>
                            </label>
                        </div>
                        <div class="col-span-1">
                            <label
                                class="mb-0 w-full cursor-pointer"
                                for="affiliationsSelectFixedValue"
                            >
                                <input
                                    class="peer hidden"
                                    id="affiliationsSelectFixedValue"
                                    name="type"
                                    type="radio"
                                    value="VALUE"
                                />
                                <div class="flex w-full items-center gap-2 rounded-lg border border-neutral-200 p-6 peer-checked:border-primary peer-checked:[&>span>i]:block peer-checked:[&>span]:border-primary peer-checked:[&>span]:bg-primary">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full border">
                                        @include('components.icon', [
                                            'icon' => 'check',
                                            'custom' => 'text-xl text-white hidden',
                                        ])
                                    </span>
                                    Valor fixo por venda
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-span-12">
                    <label for="affiliateValueInput">Valor da afiliação:</label>
                    <div class="append">
                        <div class="affiliateSymbol w-12"></div>
                        <input
                            class="noScrollInput"
                            id="affiliateValueInput"
                            name="value"
                            value=""
                            placeholder="Valor da afiliação"
                            type="text"
                        />
                    </div>
                </div>

            </div>

            <button
                class="button button-primary mt-8 h-12 w-full rounded-full"
                type="submit"
            >
                Atualizar
            </button>

        </form>
    @endcomponent
@endpush

@push('custom-script')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dataAffiliate = document.querySelector("[data-affiliate]");
            const affiliate = JSON.parse(dataAffiliate.getAttribute("data-affiliate"));
            const percentageRadio = document.getElementById("affiliationsSelectPercentage");
            const fixedValueRadio = document.getElementById("affiliationsSelectFixedValue");
            const affiliateValueInput = document.getElementById("affiliateValueInput");
            const affiliateSymbol = document.querySelector(".affiliateSymbol");
            const maxPriceOffersAffiliates = affiliate.product.max_price_offers;

            let fixedValue = ""; // Armazena o valor do input quando "Valor Fixo" está selecionado
            let percentageValue = ""; // Armazena o valor do input quando "Porcentagem" está selecionado          

            if (affiliate.type === "percentage") {
                affiliateValueInput.style.paddingRight = "48px";
                affiliateSymbol.classList.add("append-item-right");
                affiliateSymbol.innerHTML = "%";
            }

            if (affiliate.type === "value") {
                affiliateValueInput.style.paddingLeft = "48px";
                affiliateSymbol.classList.add("append-item-left");
                affiliateSymbol.innerHTML = "R$";
            }

            // Configura input e reseta valor conforme a seleção
            percentageRadio.addEventListener("change", () => {
                if (percentageRadio.checked) {
                    fixedValue = affiliateValueInput.value; // Salva o valor fixo antes de limpar
                    affiliateValueInput.value = percentageValue; // Restaura o último valor percentual salvo
                    updateInputLimits();
                }
            });

            fixedValueRadio.addEventListener("change", () => {
                if (fixedValueRadio.checked) {
                    percentageValue = affiliateValueInput.value; // Salva o valor percentual antes de mudar
                    affiliateValueInput.value = fixedValue; // Restaura o último valor fixo salvo
                    updateInputLimits();
                }
            });

            // Configura input
            const updateInputLimits = () => {
                if (percentageRadio.checked) {
                    affiliateValueInput.min = 0;
                    affiliateValueInput.max = 100;
                    affiliateValueInput.step = "1";
                    affiliateValueInput.placeholder = "0% a 100%";
                    affiliateValueInput.style.paddingRight = "48px";
                    affiliateValueInput.style.paddingLeft = "12px";
                    affiliateSymbol.classList.add("append-item-right");
                    affiliateSymbol.classList.remove("append-item-left");
                    affiliateSymbol.innerHTML = "%";
                } else if (fixedValueRadio.checked && maxPriceOffersAffiliates !== null && maxPriceOffersAffiliates !== undefined) {
                    affiliateValueInput.min = 0;
                    affiliateValueInput.max = maxPriceOffersAffiliates;
                    affiliateValueInput.step = "0.01";
                    affiliateValueInput.placeholder = `0,00 a ${maxPriceOffersAffiliates}`;
                    affiliateValueInput.style.paddingLeft = "48px";
                    affiliateValueInput.style.paddingRight = "12px";
                    affiliateSymbol.classList.add("append-item-left");
                    affiliateSymbol.classList.remove("append-item-right");
                    affiliateSymbol.innerHTML = "R$";
                }

            };

            // Valida o valor ao digitar
            const validateInput = (event) => {
                let value = event.target.value;

                if (percentageRadio.checked) {
                    value = value.replace(/[^0-9]/g, "");
                    let numericValue = parseInt(value, 10);
                    if (isNaN(numericValue) || numericValue < 0) numericValue = 0;
                    if (numericValue > 100) numericValue = 100;
                    event.target.value = numericValue;
                    percentageValue = numericValue; // Atualiza o valor percentual armazenado
                } else if (fixedValueRadio.checked) {
                    maskBrlCurrency(event.target);
                    enforceMaxValue(event.target);
                    fixedValue = event.target.value; // Atualiza o valor fixo armazenado
                }
            };

            // Mascara para valor fixo
            function maskBrlCurrency(input) {
                let value = input.value.replace(/\D/g, "");

                if (value) {
                    value = (parseFloat(value) / 100).toFixed(2);
                    value = value.replace(".", ",");
                    input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                } else {
                    input.value = "";
                }
            }

            // Força o valor fixo a ter maxPriceOffersAffiliates
            function enforceMaxValue(input) {
                let numericValue = parseFloat(input.value.replace(".", "").replace(",", "."));
                if (isNaN(numericValue) || numericValue < 0) {
                    input.value = "0,00";
                } else if (numericValue > maxPriceOffersAffiliates) {
                    input.value = maxPriceOffersAffiliates.toFixed(2).replace(".", ",");
                }
            }

            // Adiciona os listeners para alternar os limites
            percentageRadio.addEventListener("change", updateInputLimits);
            fixedValueRadio.addEventListener("change", updateInputLimits);
            affiliateValueInput.addEventListener("input", validateInput);

            // Inicializa as configurações ao carregar a página
            updateInputLimits();
        });
    </script>
    <script>
        const keyInputCouponDiscount = "affiliate";

        $(document).on("click", ".updateAffiliate", function() {
            const drawer = $("#drawerUpdateAffiliation");
            const data = JSON.parse($(this).attr('data-affiliate'));

            const urlUpdate = $(this).attr('data-urlUpdate');

            drawer.find('form').attr('action', urlUpdate);
            drawer.find(`[name="type"]`).each(function() {
                const inputValue = $(this).val().trim().toUpperCase();
                const dbValue = data.type.trim().toUpperCase();

                if (inputValue === dbValue) {
                    $(this).prop('checked', true);
                }
            });

            drawer.find(`[name="value"]`).val(data.value);
        });
    </script>
@endpush
