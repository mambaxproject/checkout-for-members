<div
    class="tab-content hidden"
    id="tab-info"
    data-tab="tab-info"
>

    <form
        class="formDataProduct"
        id="formDataProductInfo"
        action="{{ route('dashboard.products.update', $product) }}"
        method="POST"
    >

        <input
            type="hidden"
            name="tab"
            value="info"
        />

        <div class="space-y-4 md:space-y-10">

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-8">

                    <h3>Informações Gerais</h3>

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12 lg:col-span-6">
                            <label id="product[name]">Nome do Produto</label>
                            <input
                                class=""
                                type="text"
                                id="product[name]"
                                name="product[name]"
                                value="{{ old('product.name', $product->name ?? '') }}"
                                placeholder="Digite o nome do seu produto"
                                required
                            />
                        </div>

                        <div class="col-span-12 lg:col-span-6">
                            <label id="product[category_id]">Categoria do Produto</label>
                            <select
                                class=""
                                name="product[category_id]"
                                id="product[category_id]"
                                required
                            >
                                <option value="">Selecione</option>
                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('product.category_id', $product->category_id ?? '') == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">
                            <label
                                for="product[attributes][externalSalesLink]"
                                class="label-input"
                            >
                                Página de vendas
                            </label>
                            <div class="append">
                                <input
                                    class="pl-12"
                                    id="product[attributes][externalSalesLink]"
                                    name="product[attributes][externalSalesLink]"
                                    value="{{ old('product.attributes.externalSalesLink', $product->attributes->externalSalesLink ?? '') }}"
                                    placeholder="Link"
                                    type="url"
                                    required
                                />

                                <span class="append-item-left w-12">
                                    @include('components.icon', [
                                        'icon' => 'link ',
                                        'custom' => 'text-xl',
                                    ])
                                </span>
                            </div>
                        </div>

                        <div class="col-span-12 lg:col-span-12">
                            <label id="product[description]">Descrição</label>
                            <textarea
                                rows="6"
                                id="product[description]"
                                name="product[description]"
                                minlength="150"
                                maxlength="500"
                                placeholder="Explique o seu produto em no mínimo 150 caracteres e no máximo 500"
                                oninput="setCharacterLimit(this)"
                                required
                            >{{ old('product.description', $product->description ?? '') }}</textarea>
                            <p
                                class="error-msg"
                                id="error-msg-product[description]"
                            >
                            </p>
                        </div>

                        <div class="col-span-12">
                            <label for="">Imagem do produto (Obrigatório *)</label>
                            @include('components.dropzone', [
                                'id' => 'media[featuredImage]',
                                'name' => 'media[featuredImage]',
                                'accept' => 'image/*',
                                'required' => $product->getMedia('featuredImage')->isEmpty(),
                            ])
                            <p class="mt-1 text-sm text-neutral-400">Adicione uma image do produto com formato de 800x600px com máximo de 2mb</p>
                        </div>

                        @if ($product->getMedia('featuredImage')->isNotEmpty())
                            <div class="col-span-12">
                                <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                    <div class="overflow-x-scroll md:overflow-visible">
                                        <table class="table-lg table w-full">
                                            <thead>
                                                <tr>
                                                    <th>Foto</th>
                                                    <th>Nome</th>
                                                    <th>Extensão</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product->getMedia('featuredImage') as $media)
                                                    <tr>
                                                        <td>
                                                            <a
                                                                title="Ver Foto"
                                                                href="{{ $media->getUrl() }}"
                                                                target="_blank"
                                                            >
                                                                <img
                                                                    class="h-16 w-16 rounded-lg object-cover"
                                                                    src="{{ $media->getUrl() }}"
                                                                    alt="{{ $media->name }}"
                                                                    loading="lazy"
                                                                />
                                                            </a>
                                                        </td>
                                                        <td>{{ $media->name }}</td>
                                                        <td>
                                                            <span class="rounded-md bg-neutral-600 px-3 py-2 text-xs font-semibold uppercase text-white md:mr-[20%]">
                                                                {{ $media->extension }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            @component('components.dropdown-button', [
                                                                'id' => 'dropdownMoreTablefeaturedImage' . $loop->iteration,
                                                                'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                'customContainer' => 'ml-auto w-fit',
                                                                'custom' => 'text-xl',
                                                            ])
                                                                <ul>
                                                                    <li>
                                                                        <a
                                                                            class="removeMedia flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                            data-id="{{ $media->id }}"
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
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
            @endcomponent

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-8">

                    <h3>Garantia e valores</h3>

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">
                            <label for="product[guarantee]">Garantia</label>
                            <select
                                class=""
                                name="product[guarantee]"
                                id="product[guarantee]"
                                required
                            >
                                @foreach (config('products.guarantee') as $guarantee)
                                    <option
                                        value="{{ $guarantee['value'] }}"
                                        @selected(old('product.guarantee', $product->guarantee ?? 7) == $guarantee['value'])
                                    >
                                        {{ $guarantee['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">
                            <label for="">Escolha uma opção</label>
                            <div
                                class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-6"
                                id="paymentTypeInputs"
                            >
                                @php
                                    $paymentTypes = \App\Enums\PaymentTypeProductEnum::getDescriptions();
                                    if (!user()->shop()->hasCreditCardPaymentEnabled) {
                                        $paymentTypes = array_filter($paymentTypes, function ($item) {
                                            return $item['value'] != \App\Enums\PaymentTypeProductEnum::RECURRING->name;
                                        });
                                    }
                                @endphp

                                @foreach ($paymentTypes as $key => $item)
                                    <div class="inputsPaymentType_{{ $item['value'] }} col-span-1">
                                        <label
                                            class="mb-0 w-full cursor-pointer"
                                            for="paymentType_{{ $item['value'] }}"
                                        >
                                            <input
                                                type="radio"
                                                class="peer hidden"
                                                id="paymentType_{{ $item['value'] }}"
                                                name="product[paymentType]"
                                                value="{{ $item['value'] }}"
                                                onchange="toggleContent('payment_{{ $item['value'] }}_content')"
                                                @checked(old('product.paymentType', $product->paymentType ?? \App\Enums\PaymentTypeProductEnum::UNIQUE->name) === $item['value'])
                                            >
                                            <div class="flex w-full items-center gap-2 rounded-lg border border-neutral-200 p-6 peer-checked:border-primary peer-checked:[&>span>i]:block peer-checked:[&>span]:border-primary peer-checked:[&>span]:bg-primary">
                                                <span class="flex h-6 w-6 items-center justify-center rounded-full border">
                                                    @include('components.icon', [
                                                        'icon' => 'check',
                                                        'custom' => 'text-xl text-white hidden',
                                                    ])
                                                </span>
                                                {{ $item['name'] }}
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- TYPE UNIQUE --}}
                        <div class="payment_UNIQUE_content col-span-12 hidden">
                            <button
                                class="button button-light ml-auto h-12 gap-1 rounded-full"
                                id="buttonAddOfferPaymentUnique"
                                data-drawer-target="drawerAddOfferPaymentUnique"
                                data-drawer-show="drawerAddOfferPaymentUnique"
                                data-drawer-placement="right"
                                type="button"
                            >
                                @include('components.icon', ['icon' => 'add', 'custom' => 'text-xl'])
                                Adicionar oferta
                            </button>
                        </div>

                        <div class="payment_UNIQUE_content col-span-12">
                            <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                <div class="overflow-x-scroll md:overflow-visible">
                                    <table
                                        class="table-lg table w-full"
                                        id="tableOffersPaymentUnique"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Nome da oferta</th>
                                                <th>Valor da oferta</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->offersPaymentUnique as $offerPaymentUnique)
                                                <tr data-id="{{ $offerPaymentUnique->id }}">
                                                    <input
                                                        type="hidden"
                                                        name="product[offersPaymentUnique][{{ $offerPaymentUnique->id }}][id]"
                                                        value="{{ $offerPaymentUnique->id }}"
                                                    />
                                                    <td>{{ $offerPaymentUnique->name }}</td>
                                                    <td>{{ $offerPaymentUnique->brazilianPrice }}</td>
                                                    <td class="text-end">
                                                        @component('components.dropdown-button', [
                                                            'id' => 'dropdownMoreTableAffiliationsSingle' . $loop->iteration,
                                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                            'custom' => 'text-xl',
                                                        ])
                                                            <ul>
                                                                <li>
                                                                    <a
                                                                        class="editOfferPaymentUnique flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                        data-data-offer="{{ $offerPaymentUnique }}"
                                                                        href="javascript:void(0)"
                                                                        data-drawer-target="drawerAddOfferPaymentUnique"
                                                                        data-drawer-show="drawerAddOfferPaymentUnique"
                                                                        data-drawer-placement="right"
                                                                    >
                                                                        Editar
                                                                    </a>
                                                                </li>
                                                                <hr class="my-1 border-neutral-100">
                                                                <li>
                                                                    <a
                                                                        class="deleteRow flex items-center rounded-lg px-3 py-2 hover:bg-danger-100"
                                                                        data-url="{{ route('dashboard.products.destroy', $offerPaymentUnique) }}"
                                                                        href="javascript:void(0)"
                                                                    >
                                                                        Excluir
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        @endcomponent
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- TYPE RECURRING --}}
                        <div class="payment_RECURRING_content col-span-12 hidden">
                            <button
                                class="button button-light ml-auto h-12 gap-1 rounded-full"
                                id="buttonAddOfferPaymentRecurring"
                                data-drawer-target="drawerAddOfferPaymentRecurring"
                                data-drawer-show="drawerAddOfferPaymentRecurring"
                                data-drawer-placement="right"
                                type="button"
                            >
                                @include('components.icon', ['icon' => 'add', 'custom' => 'text-xl'])
                                Adicionar oferta
                            </button>
                        </div>

                        <div class="payment_RECURRING_content content col-span-12 hidden">
                            <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                <div class="overflow-x-scroll md:overflow-visible">
                                    <table
                                        class="table-lg table w-full"
                                        id="tableOffersPaymentRecurring"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Nome da oferta</th>
                                                <th>Preço da oferta</th>
                                                <th>Preço da 1º compra</th>
                                                <th>Nº de cobranças</th>
                                                <th>Periodicidade</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->offersPaymentRecurring as $offerPaymentRecurring)
                                                <tr data-id="{{ $offerPaymentRecurring->id }}">
                                                    <input
                                                        type="hidden"
                                                        name="product[offersPaymentRecurring][{{ $offerPaymentRecurring->id }}][id]"
                                                        value="{{ $offerPaymentRecurring->id }}"
                                                    />
                                                    <td>{{ $offerPaymentRecurring->name }}</td>
                                                    <td>{{ $offerPaymentRecurring->brazilianPrice }}</td>
                                                    <td>{{ $offerPaymentRecurring->brazilianPriceFirstPayment }}</td>
                                                    <td>{{ $offerPaymentRecurring->numberPaymentsRecurringPaymentFormatted }}</td>
                                                    <td>{{ $offerPaymentRecurring->cyclePaymentTranslated }}</td>
                                                    <td class="text-end">
                                                        @component('components.dropdown-button', [
                                                            'id' => 'dropdownMoreTableAffiliationsRecurrent' . $offerPaymentRecurring->id,
                                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                            'custom' => 'text-xl',
                                                        ])
                                                            <ul>
                                                                <li>
                                                                    <a
                                                                        class="editOfferPaymentRecurring flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                        data-data-offer="{{ $offerPaymentRecurring }}"
                                                                        href="javascript:void(0)"
                                                                        data-drawer-target="drawerAddOfferPaymentRecurring"
                                                                        data-drawer-show="drawerAddOfferPaymentRecurring"
                                                                        data-drawer-placement="right"
                                                                    >
                                                                        Editar
                                                                    </a>
                                                                </li>
                                                                <hr class="my-1 border-neutral-100">
                                                                <li>
                                                                    <a
                                                                        class="deleteRow flex w-full items-center rounded-lg px-3 py-2 text-danger-500 hover:bg-danger-50"
                                                                        data-url="{{ route('dashboard.products.destroy', $offerPaymentRecurring) }}"
                                                                        href="javascript:void(0)"
                                                                    >
                                                                        Excluir
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        @endcomponent
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @endcomponent

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-8">

                    <h3>Suporte</h3>

                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <label
                                for="product[attributes][emailSupport]"
                                class="label-input"
                            >E-mail de suporte</label>
                            <div class="append">
                                <input
                                    type="email"
                                    class="pl-12"
                                    name="product[attributes][emailSupport]"
                                    id="product[attributes][emailSupport]"
                                    value="{{ old('product.attributes.emailSupport', $product->attributes->emailSupport ?? '') }}"
                                    placeholder="example@example.com"
                                    min="5"
                                    maxlength="255"
                                    required
                                />

                                <span class="append-item-left w-12">
                                    @include('components.icon', [
                                        'icon' => 'mail',
                                        'custom' => 'text-xl',
                                    ])
                                </span>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <label
                                for="product[attributes][nameShop]"
                                class="label-input"
                            >
                                Nome Exibido do Produtor
                            </label>
                            <div class="append">
                                <input
                                    type="text"
                                    class="pl-12"
                                    name="product[attributes][nameShop]"
                                    id="product[attributes][nameShop]"
                                    value="{{ old('product.attributes.nameShop', $product->attributes->nameShop ?? '') }}"
                                    placeholder="João da Silva"
                                    min="3"
                                    maxlength="255"
                                    required
                                />

                                <span class="append-item-left w-12">
                                    @include('components.icon', [
                                        'icon' => 'person',
                                        'custom' => 'text-xl',
                                    ])
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            @endcomponent

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-8">

                    <h3>Anexo</h3>

                    @component('components.toggle', [
                        'id' => 'toggleAddAnexo',
                        'label' => 'Anexo',
                        'isChecked' => $product->getValueSchemalessAttributes('allowAttachment') && $product->getMedia('attachment')->isNotEmpty(),
                    ])
                        <div class="grid grid-cols-12 gap-5">

                            <input
                                type="hidden"
                                name="product[attributes][allowAttachment]"
                                value="{{ ($product->getValueSchemalessAttributes('allowAttachment') ?? false) && $product->getMedia('attachment')->isNotEmpty() }}"
                            />

                            <div class="col-span-12">
                                <label for="media[attachment][name]">Nome do Anexo</label>
                                <input
                                    type="text"
                                    id="media[attachment][name]"
                                    name="media[attachment][name]"
                                    value="{{ old('media.attachment.name', $product->getFirstMedia('attachment')->name ?? '') }}"
                                    placeholder="Digite o nome do Anexo"
                                />
                            </div>

                            <div class="col-span-12">
                                <label for="media[attachment][description]">Descrição do Anexo</label>
                                <textarea
                                    id="media[attachment][description]"
                                    name="media[attachment][description]"
                                    rows="6"
                                    minlength="150"
                                    maxlength="250"
                                    placeholder="Descreva seu produto de forma clara e objetiva, utilizando no mínimo 150 e no máximo 250 caracteres."
                                    oninput="setCharacterLimit(this)"
                                >{{ $product->getFirstMedia('attachment')?->getCustomProperty('description', '') }}</textarea>
                                <p
                                    class="error-msg"
                                    id="error-msg-media[attachment][description]"
                                ></p>
                            </div>

                            <div class="col-span-12">
                                @include('components.dropzone-chunking')
                            </div>

                            @if ($product->getMedia('attachment')->isNotEmpty())
                                <div class="col-span-12">
                                    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                        <div class="overflow-x-scroll md:overflow-visible">
                                            <table class="table-lg table w-full">
                                                <thead>
                                                    <tr>
                                                        <th>Anexo</th>
                                                        <th>Nome</th>
                                                        <th>Extensão</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->getMedia('attachment') as $media)
                                                        <tr class="attachmentMedia">
                                                            <td>
                                                                <a
                                                                    href="{{ $media->getUrl() }}"
                                                                    title="Ver anexo"
                                                                    data-tooltip-text="Ver anexo"
                                                                    target="_blank"
                                                                >
                                                                    @include('components.icon', [
                                                                        'icon' => 'description',
                                                                        'custom' => 'text-2xl',
                                                                    ])
                                                                </a>
                                                            </td>
                                                            <td>{{ $media->name }}</td>
                                                            <td>
                                                                <span class="rounded-md bg-neutral-600 px-3 py-2 text-xs font-semibold uppercase text-white md:mr-[20%]">
                                                                    {{ $media->extension }}
                                                                </span>
                                                            </td>
                                                            <td class="text-end">
                                                                @component('components.dropdown-button', [
                                                                    'id' => 'dropdownMoreTableAnexofeaturedImage' . $loop->iteration,
                                                                    'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                    'customContainer' => 'ml-auto w-fit',
                                                                    'custom' => 'text-xl',
                                                                ])
                                                                    <ul>
                                                                        <li>
                                                                            <a
                                                                                class="removeMedia flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                data-id="{{ $media->id }}"
                                                                                data-type="anexo"
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
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endcomponent

                </div>
            @endcomponent

            <button
                class="form-button-submit button button-primary mx-auto h-12 w-full max-w-xs rounded-full"
                type="submit"
            >
                Salvar
            </button>

        </div>

    </form>

</div>

@push('floating')
    @component('components.drawer', [
        'id' => 'drawerAddOfferPaymentUnique',
        'title' => 'Adicionar oferta',
        'custom' => 'max-w-2xl',
    ])
        <div class="inputsOfferPaymentUnique grid grid-cols-12 gap-6">
            <input
                type="hidden"
                id="product[offersPaymentUnique][id]"
                name="product[offersPaymentUnique][id]"
                value=""
            />

            <input
                type="hidden"
                name="product[offersPaymentUnique][shop_id]"
                value="{{ $product->shop_id }}"
            />

            <input
                type="hidden"
                name="product[offersPaymentUnique][parent_id]"
                value="{{ $product->id }}"
            />

            <input
                type="hidden"
                name="product[offersPaymentUnique][paymentType]"
                value="{{ \App\Enums\PaymentTypeProductEnum::UNIQUE->name }}"
            />

            <div class="col-span-12">

                <label for="product[offersPaymentUnique][name]">Nome da Oferta</label>
                <input
                    class=""
                    id="product[offersPaymentUnique][name]"
                    name="product[offersPaymentUnique][name]"
                    placeholder="Digite o nome da oferta"
                    required
                    type="text"
                />

            </div>

            <div class="col-span-12">

                <label for="product[offersPaymentUnique][price]">Valor da Oferta</label>
                <div class="append">
                    <input
                        class="pl-12"
                        id="product[offersPaymentUnique][price]"
                        name="product[offersPaymentUnique][price]"
                        placeholder="0,00"
                        autocomplete="off"
                        oninput="setCurrencyMask(this)"
                        required
                        type="text"
                    />
                    <span class="append-item-left w-12">R$</span>
                </div>

            </div>

        </div>

        <button
            class="button button-primary mt-8 h-12 w-full gap-1 rounded-full"
            id="addOfferPaymentUnique"
            type="button"
        >
            @include('components.icon', [
                'icon' => 'add',
                'custom' => 'text-xl',
            ])
            Adicionar
        </button>
    @endcomponent

    @component('components.drawer', [
        'id' => 'drawerAddOfferPaymentRecurring',
        'title' => 'Adicionar oferta',
        'custom' => 'max-w-2xl',
    ])
        <div class="inputsOfferPaymentRecurring grid grid-cols-12 gap-6">
            <input
                type="hidden"
                id="product[offersPaymentRecurring][id]"
                name="product[offersPaymentRecurring][id]"
                value=""
            />

            <input
                type="hidden"
                name="product[offersPaymentRecurring][shop_id]"
                value="{{ $product->shop_id }}"
            />

            <input
                type="hidden"
                name="product[offersPaymentRecurring][parent_id]"
                value="{{ $product->id }}"
            />

            <input
                type="hidden"
                name="product[offersPaymentRecurring][paymentType]"
                value="{{ \App\Enums\PaymentTypeProductEnum::RECURRING->name }}"
            />

            <div class="col-span-12">

                <label for="product[offersPaymentRecurring][name]">Nome da Oferta</label>
                <input
                    class=""
                    id="product[offersPaymentRecurring][name]"
                    name="product[offersPaymentRecurring][name]"
                    placeholder="Digite o nome da oferta"
                    type="text"
                    required
                />

            </div>

            <div class="col-span-12">

                <label for="product[offersPaymentRecurring][price]">Preço</label>
                <div class="append">
                    <input
                        class="pl-12"
                        id="product[offersPaymentRecurring][price]"
                        name="product[offersPaymentRecurring][price]"
                        placeholder="0,00"
                        autocomplete="off"
                        oninput="setCurrencyMask(this)"
                        type="text"
                        required
                    >
                    <span class="append-item-left w-12">R$</span>
                </div>

            </div>

            <div class="col-span-12">

                <label for="product[offersPaymentRecurring][cyclePayment]">Periodicidade</label>
                <select
                    class=""
                    id="product[offersPaymentRecurring][cyclePayment]"
                    name="product[offersPaymentRecurring][cyclePayment]"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach (\App\Enums\CyclePaymentProductEnum::getDescriptions() as $key => $item)
                        <option
                            value="{{ $item['value'] }}"
                            @selected(old('product.offersPaymentRecurring.cyclePayment', $item['value']) == \App\Enums\CyclePaymentProductEnum::MONTHLY->name)
                        >
                            {{ $item['name'] }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="col-span-12">

                <label for="product[offersPaymentRecurring][renewsRecurringPayment]">Renovação automática</label>
                <select
                    class=""
                    id="product[offersPaymentRecurring][renewsRecurringPayment]"
                    name="product[offersPaymentRecurring][renewsRecurringPayment]"
                    onchange="toggleNumberOfCharges(this)"
                    required
                >
                    @foreach (config('products.renewsRecurringPayment') as $key => $item)
                        <option
                            value="{{ $item['value'] }}"
                            @selected(old('product.offersPaymentRecurring.renewsRecurringPayment', $item['value']) == 'CUSTOMER_CANCEL')
                        >
                            {{ $item['name'] }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div
                class="col-span-12 hidden"
                id="NumberOfCharges"
            >

                @include('components.form.increment', [
                    'id' => 'product[offersPaymentRecurring][numberPaymentsRecurringPayment]',
                    'name' => 'product[offersPaymentRecurring][numberPaymentsRecurringPayment]',
                    'label' => 'Número de cobranças',
                    'min' => 0,
                    'max' => 100,
                ])

            </div>

            <div class="col-span-12">

                @component('components.toggle', [
                    'id' => 'differentValueOnFirstCharge',
                    'label' => 'Valor diferente na primeira cobrança',
                    'value' => '',
                    'name' => '',
                    'checked' => false,
                ])
                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">

                            <label for="">Valor da primeira cobrança</label>
                            <div class="append">
                                <input
                                    class="pl-12"
                                    id="product[offersPaymentRecurring][priceFirstPayment]"
                                    name="product[offersPaymentRecurring][priceFirstPayment]"
                                    placeholder="0,00"
                                    autocomplete="off"
                                    oninput="setCurrencyMask(this)"
                                    type="text"
                                >
                                <span class="append-item-left w-12">R$</span>
                            </div>

                        </div>

                    </div>
                @endcomponent

            </div>

        </div>

        <button
            class="button button-primary mt-8 h-12 w-full gap-1 rounded-full"
            id="addOfferPaymentRecurring"
            type="button"
        >
            @include('components.icon', [
                'icon' => 'add',
                'custom' => 'text-xl',
            ])
            Adicionar
        </button>
    @endcomponent

    {{-- MODAL SUCCESS --}}
    @component('components.modal', [
        'id' => 'newSubmitApprove',
        'title' => '',
    ])
        <div class="-mt-5 px-20 pb-16 pt-0">

            @include('components.icon-animation', [
                'icon' => asset('images/dashboard/animates/alert.json'),
                'width' => '200px',
                'height' => '200px',
                'colorPrimary' => '#ffc738',
                'colorSecondary' => '#faf9d1',
            ])

            <div class="">

                <h3 class="mb-2 text-center font-semibold">Atenção!!</h3>
                <p
                    id="dynamicModalMessage"
                    class="text-center"
                ></p>

                <div class="mt-8 flex items-center justify-center gap-2">

                    <button
                        class="cancelNewSubmitApprove button button-outline-light h-12 rounded-full"
                        type="button"
                    >
                        Cancelar alterações
                    </button>

                    <button
                        class="saveNewSubmitApprove button button-primary h-12 rounded-full"
                        type="Continuar"
                    >
                        Submeter para aprovação
                    </button>

                </div>

            </div>
        </div>
    @endcomponent
@endpush

@push('custom-script')
    <script src="{{ asset('js/dashboard/validation/character-limit.js') }}"></script>

    <script>
        // Apaga id do input (product[offersPaymentUnique][id]) ao clicar no botão de adicionar oferta
        const buttonAddOfferPaymentUnique = document.getElementById('buttonAddOfferPaymentUnique');
        buttonAddOfferPaymentUnique.addEventListener('click', function() {
            const productOffersPaymentUniqueId = document.querySelector('input[name="product[offersPaymentUnique][id]"]');
            productOffersPaymentUniqueId.value = '';
        });

        // Apaga id do input (product[offersPaymentRecurring][id]) ao clicar no botão de adicionar oferta
        const buttonAddOfferPaymentRecurring = document.getElementById('buttonAddOfferPaymentRecurring');
        buttonAddOfferPaymentRecurring.addEventListener('click', function() {
            const productOffersPaymentRecurringId = document.querySelector('input[name="product[offersPaymentRecurring][id]"]');
            productOffersPaymentRecurringId.value = '';
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const attachmentToggle = document.getElementById('toggleAddAnexo');
            const attachmentInput = document.querySelector('input[name="product[attributes][allowAttachment]"]');
            attachmentInput.value = attachmentToggle.checked ? '1' : '0';

            attachmentToggle.addEventListener('change', function() {
                attachmentInput.value = attachmentToggle.checked ? '1' : '0';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let form = document.querySelector('#formDataProductInfo');
            let isValidAttachment = true;

            // Validação de adicionar anexo
            if (form) {
                const formButtonSubmit = document.querySelector('.form-button-submit');
                formButtonSubmit.addEventListener('click', function(event) {
                    isValidAttachment = true;

                    const attachmentToggle = document.getElementById('toggleAddAnexo');
                    if (attachmentToggle.checked) {
                        // Valida nome do anexo
                        const attachmentName = document.getElementById("media[attachment][name]");
                        if (!attachmentName.value.trim()) {
                            notyf.info("Por favor, preencha o nome do anexo.");
                            isValidAttachment = false;
                        }

                        // Valida descrição do anexo
                        const attachmentDescription = document.getElementById("media[attachment][description]");
                        if (!attachmentDescription.value.trim()) {
                            notyf.info("Por favor, preencha a descrição do anexo.");
                            isValidAttachment = false;
                        } else if (attachmentDescription.value.trim().length < 150) {
                            notyf.info("A descrição do anexo deve ter no mínimo 150 caracteres.");
                            isValidAttachment = false;
                        }

                        // Valida imagem do anexo
                        const hasAttachment = document.querySelector('tr.attachmentMedia');
                        const attachmentMedia = document.querySelector('[name="media[attachment]"]');
                        if (!hasAttachment && !attachmentMedia.files.length) {
                            notyf.info("Por favor, adicione uma imagem ou PDF do anexo.");
                            isValidAttachment = false;
                        }

                        // Previne envio do formulário
                        if (!isValidAttachment) {
                            event.preventDefault();
                        }
                    }
                });
            }

            // Validação de submeter a aparovação
            let productSituation = "{{ $product->situation }}"
            if (productSituation === "PUBLISHED" || productSituation === "DISABLE") {
                let hasChanges = false;
                let changedOffer = false;
                let changedAttachment = false;
                let changedAttachmentFile = false;


                function markChanges() {
                    hasChanges = true;
                }

                function markChangesOffer() {
                    changedOffer = true;
                }

                function markChangesAttachment() {
                    changedAttachment = true;
                }

                function markChangesAttachmentFile() {
                    changedAttachmentFile = true;
                }

                // Update Offers
                const offersPaymentUnique = document.querySelectorAll(
                    'input[name^="product[offersPaymentUnique]"]');
                offersPaymentUnique.forEach(input => {
                    input.addEventListener('change', () => {
                        markChanges()
                        markChangesOffer()
                    });
                });

                const offersPaymentRecurring = document.querySelectorAll(
                    'input[name^=\"product[offersPaymentRecurring]\"]');
                offersPaymentRecurring.forEach(input => {
                    input.addEventListener('change', () => {
                        markChanges();
                        markChangesOffer();
                    });
                });

                // Update Anexos
                const attachmentActive = document.getElementById('toggleAddAnexo');

                if (attachmentActive) {
                    attachmentActive.addEventListener('change', () => {
                        markChanges();
                        markChangesAttachment();
                    });
                }


                const attachmentInputs = document.querySelectorAll(
                    'input[name^="media[attachment][name]"], textarea[name^="media[attachment][description]"], input[name^="media[attachmentFromChuncking]"]'
                );

                attachmentInputs.forEach(input => {
                    input.addEventListener('change', () => {
                        markChanges();
                        markChangesAttachment();
                    });
                });


                const hiddenInput = document.querySelector('input[name="media[attachmentFromChuncking]"]');

                const hiddenInputObserver = new MutationObserver((mutationsList) => {
                    for (const mutation of mutationsList) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                            markChanges();
                            markChangesAttachmentFile();
                        }
                    }
                });

                document.addEventListener('click', function(event) {
                    const target = event.target;
                    const chunkingRemoveAttachment = target.closest('[data-chunking-remove]') !== null;

                    if (target.dataset.action === 'remove' && chunkingRemoveAttachment) {
                        changedAttachmentFile = false
                        let canRemoveMarkChanges = changedOffer || changedAttachment;

                        if (!canRemoveMarkChanges) {
                            hasChanges = false;
                        }
                    }
                });

                hiddenInputObserver.observe(hiddenInput, {
                    attributes: true
                });

                const attachmentMedias = document.querySelectorAll('.removeMedia');
                attachmentMedias.forEach(button => {
                    button.addEventListener('click', markChanges);
                });

                // Form
                if (form) {
                    const formButtonSubmit = document.querySelector(".form-button-submit");
                    formButtonSubmit.addEventListener('click', function(event) {
                        if (hasChanges && isValidAttachment) {
                            event.preventDefault();
                            showModalMessagesDynamic();


                            const modal = document.getElementById('newSubmitApprove');
                            if (modal) {
                                const modalInstance = new Modal(modal);
                                modalInstance.show();

                                // Fecha modal pelo botão
                                const buttonModalHide = document.querySelector('[data-modal-hide="newSubmitApprove"]');
                                if (buttonModalHide) {
                                    buttonModalHide.addEventListener('click', function() {
                                        modalInstance.hide();
                                    });
                                }

                                // Cancela envio para nova aprovação
                                const cancelNewSubmitApprove = document.querySelector('.cancelNewSubmitApprove');
                                if (cancelNewSubmitApprove) {
                                    cancelNewSubmitApprove.addEventListener('click', function() {
                                        hasChanges = false;
                                        location.reload();
                                    });
                                }

                                // Envia para nova aprovação
                                const saveNewSubmitApprove = document.querySelector('.saveNewSubmitApprove');
                                if (saveNewSubmitApprove) {
                                    saveNewSubmitApprove.addEventListener('click', function() {
                                        hasChanges = false;
                                        formButtonSubmit.click();
                                        localStorage.setItem('productId{{ $product->id }}SubmittedForNewApproval', 'true');
                                        modalInstance.hide();
                                    })
                                }
                            }
                        }
                    });
                }

                function showModalMessagesDynamic() {
                    const dynamicMessageContainer = document.getElementById('dynamicModalMessage');
                    let changesList = [];

                    if (changedOffer) {
                        changesList.push("- Alterações detectadas em: <strong>OFERTAS</strong>");
                    }

                    if (changedAttachment) {
                        changesList.push("- Alterações detectadas nos detalhes do: <strong>ANEXO</strong>");
                    }

                    if (changedAttachmentFile) {
                        changesList.push("- Alterações detectadas nos arquivos do: <strong>ANEXO</strong>");
                    }

                    const mensagemFinal = `
                    <p>As seguintes alterações serão enviadas para análise do time de compliance:</p>
                    <br>
                    ${changesList.map(item => `<p>${item}</p>`).join('')}
                    <br>
                    <p>O produto continuará funcionando com a versão atual enquanto a nova versão aguarda aprovação.</p>
                    `;

                    if (hasChanges) {
                        dynamicMessageContainer.innerHTML = mensagemFinal;
                    }
                }
            }
        });
    </script>

    <script>
        // Função para alternar o conteúdo de acordo com o tipo
        function toggleContent(contentType) {
            const uniqueContentSelector = '.payment_UNIQUE_content';
            const recurringContentSelector = '.payment_RECURRING_content';

            $(uniqueContentSelector).toggle(contentType === "payment_UNIQUE_content");
            $(recurringContentSelector).toggle(contentType !== "payment_UNIQUE_content");
        }

        // Função para exibir ou ocultar o campo de número de parcelas
        function toggleNumberOfCharges(selectElement) {
            const isFixedQtyCharges = selectElement.value === 'FIXED_QTY_CHARGES';
            $('#NumberOfCharges').toggle(isFixedQtyCharges);
        }

        // Observador para monitorar mudanças no atributo 'aria-hidden'
        function setupDrawerObserver(drawerId, chargesFieldSelector) {
            const drawer = document.getElementById(drawerId);

            if (!drawer) {
                console.error(`Drawer com ID "${drawerId}" não encontrado.`);
                return;
            }

            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (
                        mutation.type === "attributes" &&
                        mutation.attributeName === "aria-hidden"
                    ) {
                        const isHidden = drawer.getAttribute("aria-hidden") === "true";

                        if (isHidden) {
                            $(chargesFieldSelector).hide();
                        }
                    }
                });
            });

            observer.observe(drawer, {
                attributes: true
            });
        }

        // Inicialização do código
        $(document).ready(() => {
            // Configura o observador no drawer específico
            setupDrawerObserver("drawerAddOfferPaymentRecurring", "#NumberOfCharges");
        });

        // // Monitorar eventos do drawer do Flowbite
        // $(document).ready(function() {
        //     const drawerSelector = '#drawerAddOfferPaymentRecurring'; // Substitua pelo ID do seu drawer

        //     // Ações ao abrir e fechar o drawer
        //     $(drawerSelector).on('transitionend', function() {
        //         const isOpen = !$(this).hasClass('hidden');
        //         console.log(`Drawer está ${isOpen ? 'aberto' : 'fechado'}`);
        //     });

        //     // Ou monitorar diretamente a classe "hidden"
        //     const observer = new MutationObserver((mutations) => {
        //         mutations.forEach((mutation) => {
        //             const target = $(mutation.target);
        //             if (target.is('#drawerAddOfferPaymentRecurring')) {
        //                 const isOpen = !target.hasClass('hidden');
        //                 console.log(`Drawer está ${isOpen ? 'aberto' : 'fechado'}`);
        //             }
        //         });
        //     });

        //     // Observar mudanças no atributo de classe do drawer
        //     observer.observe(document.querySelector(drawerSelector), {
        //         attributes: true,
        //         attributeFilter: ['class']
        //     });
        // });
    </script>

    <script>
        $(document).on("click", "#addOfferPaymentUnique", function() {
            const $table = $("#tableOffersPaymentUnique");
            const $inputs = $(".inputsOfferPaymentUnique input, .inputsOfferPaymentUnique select");
            let idOffer = $("#product\\[offersPaymentUnique\\]\\[id\\]").val();
            let isEdit = !!idOffer;

            let isValid = $inputs.toArray().every(input => !$(input).prop("required") || $(input).val());

            var validatePrice = $("#product\\[offersPaymentUnique\\]\\[price\\]").val();

            // Remove os separadores de milhar (pontos) e substitui a vírgula decimal por ponto
            validatePrice = validatePrice.replace(/\./g, '').replace(',', '.');
            validatePrice = parseFloat(validatePrice);

            if (isNaN(validatePrice) || validatePrice < 5.00) {
                notyf.error("O valor da oferta não pode ser menor que R$ 5,00");
                return false;
            }

            if (!isValid) {
                notyf.info("Preencha todos os campos obrigatórios");
                return false;
            }

            if (isEdit) {
                let $tr = $table.find(`tbody tr[data-id="${idOffer}"]`);

                $tr.find("td").eq(0).text($("#product\\[offersPaymentUnique\\]\\[name\\]").val());
                $tr.find("td").eq(1).text('R$ ' + $("#product\\[offersPaymentUnique\\]\\[price\\]").val());

                for (let input of $inputs) {
                    if (input.name && input.value) {
                        $tr.append(
                            `<input type="hidden" name="${input.name.replace("product[offersPaymentUnique]", `product[offersPaymentUnique][${idOffer}]`)}" value="${input.value}" />`
                        );
                    }
                }

                $inputs.filter("input[type='text']").val("");

                $("#drawerAddOfferPaymentUnique .closeButton").trigger("click");

                return;
            }

            $table.find("tbody").append(`
                <tr>
                    <td>${$("#product\\[offersPaymentUnique\\]\\[name\\]").val()}</td>
                    <td>${$("#product\\[offersPaymentUnique\\]\\[price\\]").val()}</td>
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

            let index = $table.find("tbody tr").length - 1;

            for (let input of $inputs) {
                if (input.name && input.value) {
                    $table.find("tbody tr").eq(index).append(
                        `<input type="hidden" name="${input.name.replace("product[offersPaymentUnique]", `product[offersPaymentUnique][${index}]`)}" value="${input.value}" />`
                    );
                }
            }

            $inputs.filter("input[type='text']").val("");

            $("#drawerAddOfferPaymentUnique .closeButton").trigger("click");
        });
    </script>

    <script>
        $(document).on("click", ".editOfferPaymentUnique", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataOffer = $(this).data("dataOffer");

            drawer.find(".titleDrawer").text("Editar oferta");

            drawer.find("#product\\[offersPaymentUnique\\]\\[id\\]").val(dataOffer.id);
            drawer.find("#product\\[offersPaymentUnique\\]\\[name\\]").val(dataOffer.name);
            drawer.find("#product\\[offersPaymentUnique\\]\\[price\\]").val(dataOffer.price);

            maskBrlCurrency(document.querySelector("#product\\[offersPaymentUnique\\]\\[price\\]"));

            drawer.find("#addOfferPaymentUnique").text("Atualizar");
        });
    </script>

    <script>
        const keyInputOffersPaymentRecurring = "offersPaymentRecurring";

        $(document).on("click", "#addOfferPaymentRecurring", function() {
            const $table = $("#tableOffersPaymentRecurring");
            const $inputs = $(".inputsOfferPaymentRecurring input, .inputsOfferPaymentRecurring select");
            let idOffer = $(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[id\\]`).val();
            let isEdit = !!idOffer;

            let isValid = $inputs.toArray().every(input => !$(input).prop("required") || $(input).val());

            if (!isValid) {
                notyf.info("Preencha todos os campos obrigatórios");
                return false;
            }

            // $this->renewsRecurringPayment == 'FIXED_QTY_CHARGES' && !empty($this->numberPaymentsRecurringPayment)
            //     ? $this->numberPaymentsRecurringPayment
            //     : "Até o cliente cancelar"

            let renewsRecurringPaymentFormatted = $(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[renewsRecurringPayment\\]`).val() === 'FIXED_QTY_CHARGES' ?
                $(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[numberPaymentsRecurringPayment\\]`).val() :
                $(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[renewsRecurringPayment\\] option:selected`).text();

            if (isEdit) {
                let $tr = $table.find(`tbody tr[data-id="${idOffer}"]`);

                $tr.find("td").eq(0).text($(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[name\\]`).val());
                $tr.find("td").eq(1).text('R$ ' + $(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[price\\]`).val());
                $tr.find("td").eq(2).text($(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[priceFirstPayment\\]`).val() > 0 ? 'R$ ' + $(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[priceFirstPayment\\]`).val() : '-');
                $tr.find("td").eq(3).text(`${renewsRecurringPaymentFormatted}`);
                $tr.find("td").eq(4).text(`${$(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[cyclePayment\\] option:selected`).text()}`);

                for (let input of $inputs) {
                    if (input.name && input.value) {
                        $tr.append(
                            `<input type="hidden" name="${input.name.replace(`product[${keyInputOffersPaymentRecurring}]`, `product[${keyInputOffersPaymentRecurring}][${idOffer}]`)}" value="${input.value}" />`
                        );
                    }
                }

                $inputs.filter("input[type='text']").val("");

                $("#drawerAddOfferPaymentRecurring .closeButton").trigger("click");

                return;
            }

            $table.find("tbody").append(`
                <tr>
                    <td>${$(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[name\\]`).val()}</td>
                    <td>${$(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[price\\]`).val()}</td>
                    <td>${$(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[priceFirstPayment\\]`).val() > 0 ? 'R$ ' + $(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[priceFirstPayment\\]`).val() : '-'}</td>
                    <td>${renewsRecurringPaymentFormatted}</td>
                    <td>${$(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[cyclePayment\\] option:selected`).text()}</td>
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

            let index = $table.find("tbody tr").length - 1;

            for (let input of $inputs) {
                if (input.name && input.value) {
                    $table.find("tbody tr").eq(index).append(
                        `<input type="hidden" name="${input.name.replace("product[offersPaymentRecurring]", `product[offersPaymentRecurring][${index}]`)}" value="${input.value}" />`
                    );
                }
            }

            $inputs.filter("input[type='text']").val("");

            $("#drawerAddOfferPaymentRecurring .closeButton").trigger("click");
        });

        $(document).on("click", ".editOfferPaymentRecurring", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataOffer = $(this).data("dataOffer");

            drawer.find(".titleDrawer").text("Editar oferta");

            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[id\\]`).val(dataOffer.id);
            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[name\\]`).val(dataOffer.name);
            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[price\\]`).val(dataOffer.price);
            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[cyclePayment\\]`).val(dataOffer.cyclePayment);

            maskBrlCurrency(document.querySelector("#product\\[offersPaymentRecurring\\]\\[price\\]"));
            maskBrlCurrency(document.querySelector("#product\\[offersPaymentRecurring\\]\\[priceFirstPayment\\]"));
            drawer.find("#differentValueOnFirstCharge").prop("checked", Boolean(dataOffer.priceFirstPayment > 0)).trigger('change');

            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[priceFirstPayment\\]`)
                .val((dataOffer.priceFirstPayment && dataOffer.priceFirstPayment > 0) ? dataOffer.priceFirstPayment : '-');

            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[renewsRecurringPayment\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[renewsRecurringPayment\\] option[value="${dataOffer.renewsRecurringPayment}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[renewsRecurringPayment\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputOffersPaymentRecurring}\\]\\[numberPaymentsRecurringPayment\\]`).val(dataOffer.numberPaymentsRecurringPayment);

            drawer.find("#addOfferPaymentRecurring").text("Atualizar");
        });

        $("#differentValueOnFirstCharge").change(function() {
            if (!$(this).is(":checked")) {
                $("#product\\[offersPaymentRecurring\\]\\[priceFirstPayment\\]").val(0);
            }
        });
    </script>

    <script>
        $(document).on("click", ".removeMedia", function() {
            let id = $(this).data("id");
            let type = $(this).data("type");

            if (type === "anexo") {
                localStorage.setItem('confirmRemoveAnexo', 'true');
            }

            $(".formDataProduct").append(`
                <input type="hidden" name="removeMedia[]" value="${id}" />
            `);

            $(this).closest("tr").remove();
        });
    </script>

    <script>
        function validateProductName($input, name, id = null) {
            $.ajax({
                url: "{{ route('dashboard.products.checkUniqueProductName') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    name: name,
                    id: id,
                    parent_id: {{ $product->id }}
                },
                success: function(response) {
                    if (response.exists) {
                        $input.val("").focus();
                        notyf.error("Já existe uma oferta com esse nome.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição:", error);
                }
            });
        }

        $(document).on("change", "input[name='product[offersPaymentUnique][name]'], input[name^='product[offersPaymentRecurring][name]']", function() {
            let $input = $(this);
            let productName = $input.val();
            let productId = $input.closest(".inputsOfferPaymentUnique").find("input[name^='product[offersPaymentUnique]']").val() ||
                $input.closest(".inputsOfferPaymentRecurring").find("input[name^='product[offersPaymentRecurring]']").val();

            validateProductName(
                $input,
                productName,
                productId
            );
        });
    </script>

    <script>
        window.onload = (event) => {
            document.querySelector('input[name="product[paymentType]"]:checked').dispatchEvent(new Event('change'));
        };
    </script>
@endpush
