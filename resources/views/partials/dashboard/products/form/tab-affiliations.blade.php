<div
    class="tab-content hidden"
    id="tab-affiliations"
    data-tab="tab-affiliations"
>

    <div class="space-y-4 md:space-y-10">
        <form
            action="{{ route('dashboard.products.update', $product) }}"
            method="POST"
            class="formDataProduct"
        >

            <input
                type="hidden"
                name="tab"
                value="affiliations"
            />

            <div class="space-y-4 md:space-y-10">
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8">

                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                            <h3>Configurações</h3>

                            <a
                                class="button button-primary h-12 gap-1 rounded-full"
                                href="{{ route('dashboard.affiliates.index') }}"
                                title="Ver afiliados"
                            >
                                Ver afiliados
                            </a>

                        </div>

                        @component('components.toggle', [
                            'id' => 'affiliationConfig1',
                            'label' => 'Habilitar programa de afiliados',
                            'name' => 'product[attributes][affiliate][enabled]',
                            'value' => true,
                            'isChecked' => $product->getValueSchemalessAttributes('affiliate.enabled'),
                        ])
                            <div class="grid grid-cols-12 gap-6">

                                <div class="col-span-12">

                                    <div class="space-y-4">

                                        <div class="">

                                            @include('components.toggle', [
                                                'id' => 'affiliationConfig2',
                                                'label' => 'Aprovar solicitações de afiliação manualmente (Automático por padrão)',
                                                'name' => 'product[attributes][affiliate][approveRequestsManually]',
                                                'value' => true,
                                                'isChecked' => $product->getValueSchemalessAttributes('affiliate.enabled') && $product->getValueSchemalessAttributes('affiliate.approveRequestsManually'),
                                                'contentEmpty' => true,
                                            ])

                                        </div>

                                        <div class="">

                                            @include('components.toggle', [
                                                'id' => 'affiliationConfig3',
                                                'label' => 'Liberar acesso aos dados de contato de compradores para afiliados',
                                                'name' => 'product[attributes][affiliate][allowAccessToCustomersData]',
                                                'value' => true,
                                                'isChecked' => $product->getValueSchemalessAttributes('affiliate.enabled') && $product->getValueSchemalessAttributes('affiliate.allowAccessToCustomersData'),
                                                'contentEmpty' => true,
                                            ])

                                        </div>

                                        <div class="">

                                            @include('components.toggle', [
                                                'id' => 'affiliationConfig4',
                                                'label' => 'Mostrar produto no Marketplace de Afiliados',
                                                'name' => 'product[attributes][affiliate][showProductInMarketplace]',
                                                'value' => true,
                                                'isChecked' => $product->getValueSchemalessAttributes('affiliate.enabled') && $product->getValueSchemalessAttributes('affiliate.showProductInMarketplace'),
                                                'contentEmpty' => true,
                                            ])

                                        </div>

                                    </div>

                                </div>

                                <div class="col-span-12">
                                    <label for="product[attributes][affiliate][emailSupport]">E-mail de suporte para afiliados</label>
                                    <input
                                        type="email"
                                        id="product[attributes][affiliate][emailSupport]"
                                        name="product[attributes][affiliate][emailSupport]"
                                        value="{{ $product->getValueSchemalessAttributes('affiliate.emailSupport') }}"
                                        class="input bg"
                                        placeholder="Digite aqui seu e-mail"
                                    >
                                </div>

                                <div class="col-span-12">
                                    <label for="product[attributes][affiliate][descriptionProduct]">Descrição do produto para afiliados</label>
                                    <textarea
                                        id="product[attributes][affiliate][descriptionProduct]"
                                        name="product[attributes][affiliate][descriptionProduct]"
                                        rows="4"
                                        class="input bg"
                                        placeholder="Digite aqui sua descrição"
                                    >{{ $product->getValueSchemalessAttributes('affiliate.descriptionProduct') }}</textarea>
                                </div>

                                <div class="col-span-12">
                                    <label for="">Escolha uma opção</label>
                                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-6">
                                        <div class="col-span-1">
                                            <label
                                                class="mb-0 w-full cursor-pointer"
                                                for="affiliationsSelectPercentage"
                                            >
                                                <input
                                                    class="peer hidden"
                                                    id="affiliationsSelectPercentage"
                                                    name="product[attributes][affiliate][defaultTypeValue]"
                                                    type="radio"
                                                    value="PERCENTAGE"
                                                    checked
                                                    @checked($product->getValueSchemalessAttributes('affiliate.defaultTypeValue') === 'PERCENTAGE')
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
                                                    name="product[attributes][affiliate][defaultTypeValue]"
                                                    type="radio"
                                                    value="VALUE"
                                                    @checked($product->getValueSchemalessAttributes('affiliate.defaultTypeValue') === 'VALUE')
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
                                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-6">
                                        <div>
                                            <label for="product[attributes][affiliate][defaultValue]">Valor da afiliação:</label>
                                            <div class="append">
                                                <div class="affiliateSymbol w-12"></div>
                                                <input
                                                        class="noScrollInput"
                                                        id="affiliateValueInput"
                                                        name="product[attributes][affiliate][defaultValue]"
                                                        value="{{ $product->getValueSchemalessAttributes('affiliate.defaultValue') }}"
                                                        placeholder="Valor da afiliação"
                                                        type="text"
                                                />
                                            </div>
                                        </div>

                                        <div>
                                            <label for="product[attributes][affiliate][defaultValue]">Selecione a oferta:</label>
                                            <div class="append">
                                                <select id="offers" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                    @foreach($product->offers as $offer)
                                                        <option value="{{$offer->price}}">{{$offer->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="col-span-12 my-2">

                                <div class="col-span-12">

                                    <div class="rounded-xl bg-neutral-100 p-4 md:p-8">

                                        <div class="grid grid-cols-12 gap-4 md:gap-6">

                                            <div class="col-span-12 md:col-span-6">

                                                <h4 class="mb-2 text-base font-semibold">Valores sem afiliado</h4>
                                                <div class="rounded-xl bg-white p-4 md:p-6">
                                                    <ul>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">Preço:</span>
                                                            <strong class="text-sm">R$ <span class="product-price">100,00</span></strong>
                                                        </li>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">Taxa do SuitPay (6,99%):</span>
                                                            <strong class="text-sm">R$ <span class="app-fee">4,99</span></strong>
                                                        </li>
                                                        <hr class="my-2" />
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">Faturamento:</span>
                                                            <strong class="text-sm">R$ <span class="invoicing-no-affiliate">95,01</span></strong>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>

                                            <div class="col-span-12 md:col-span-6">

                                                <h4 class="mb-2 text-base font-semibold">Valores com afiliado</h4>
                                                <div class="rounded-xl bg-white p-4 md:p-6">
                                                    <ul>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">Preço:</span>
                                                            <strong class="text-sm">R$ <span class="product-price">100,00</span></strong>
                                                        </li>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">Taxa do SuitPay (6,99%):</span>
                                                            <strong class="text-sm">R$ <span class="app-fee">4,99</span></strong>
                                                        </li>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">Valor do Afiliado:</span>
                                                            <strong class="text-sm">R$ <span class="affiliate-commission">30,00</span></strong>
                                                        </li>
                                                        <hr class="my-2" />
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">Faturamento:</span>
                                                            <strong class="text-sm">R$ <span class="invoicing">65,01</span></strong>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @if ($product->getValueSchemalessAttributes('affiliate.enabled') && $product->getValueSchemalessAttributes('affiliate.defaultValue'))
                                @component('components.card', ['custom' => 'mt-10 p-6 !bg-neutral-50 md:p-8'])
                                    <div class="space-y-4">

                                        <h3>Copiar link de convite de afiliado</h3>

                                        <div class="">

                                            <label for="">Compartilhe esse link para convidar os seus afiliados</label>
                                            <div class="append">
                                                <input
                                                    type="text"
                                                    value="{{ $product->linkJoinAffiliate }}"
                                                    disabled
                                                />

                                                <button
                                                    class="copyClipboard append-item-right flex w-fit items-center gap-1 rounded-r-md border-y border-r border-neutral-200 bg-neutral-100 px-4 before:pointer-events-none before:absolute before:-left-32 before:top-0 before:h-[46px] before:w-32 before:content-normal before:bg-gradient-to-r before:from-transparent before:to-neutral-100 hover:text-primary md:before:-left-56 md:before:w-56"
                                                    data-clipboard-text="{{ $product->linkJoinAffiliate }}"
                                                    type="button"
                                                >
                                                    @include('components.icon', [
                                                        'icon' => 'content_copy',
                                                        'custom' => 'text-xl hover:text-primary',
                                                    ])
                                                    Copiar
                                                </button>

                                            </div>

                                        </div>

                                    </div>
                                @endcomponent
                            @endif
                        @endcomponent

                    </div>
                @endcomponent

                <button
                    class="button button-primary mx-auto h-12 w-full max-w-xs rounded-full"
                    type="submit"
                >
                    Salvar
                </button>
            </div>

        </form>
    </div>

</div>

@push('custom-script')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const percentageRadio        = document.getElementById("affiliationsSelectPercentage");
            const fixedValueRadio        = document.getElementById("affiliationsSelectFixedValue");
            const affiliateType          = "{{ $product->getValueSchemalessAttributes('affiliate.defaultTypeValue') }}";
            const affiliateValueInput    = document.getElementById("affiliateValueInput");
            const affiliateSymbol        = document.querySelector(".affiliateSymbol");
            const appFee                 = 0.0699;
            const offersSelector         = $('#offers');


            let maxPriceOffersAffiliates = parseFloat(offersSelector.val() - (offersSelector.val() * appFee)).toFixed(2)
            let fixedValue               = '0'; // Armazena o valor do input quando "Valor Fixo" está selecionado
            let percentageValue          = '0'; // Armazena o valor do input quando "Porcentagem" está selecionado

            offersSelector.on('change', function() {
                maxPriceOffersAffiliates  = parseFloat(offersSelector.val() - (offersSelector.val() * appFee)).toFixed(2)
                affiliateValueInput.value = 0
                fixedValue                = '0'
                percentageValue           = '0'
                updateInputLimits();
                updateView();
            });

            function calc() {
                let data = {
                    offerPrice: offersSelector.val(),
                    fee: offersSelector.val() * appFee,
                    invoicingNoAffiliate: parseFloat(offersSelector.val() - (offersSelector.val() * appFee)),
                }

                if ($('#affiliationsSelectPercentage').is(':checked')) {
                    data.commission = (data.invoicingNoAffiliate / 100) * percentageValue
                } else {
                    data.commission = parseFloat(fixedValue.replace('.', '').replace(',', '.'));
                }

                data.invoicing  = data.invoicingNoAffiliate - data.commission;

                return data;
            }

            function updateView() {
                let data = calc();

                $('.product-price').html(currency(data.offerPrice));
                $('.app-fee').html(currency(data.fee.toFixed(2)))
                $('.invoicing-no-affiliate').html(currency(data.invoicingNoAffiliate.toFixed(2)))
                $('.invoicing').html(currency(data.invoicing.toFixed(2)))
                $('.affiliate-commission').html(currency(data.commission.toFixed(2)))

            }

            if (affiliateType === "PERCENTAGE") {
                affiliateValueInput.style.paddingRight = "48px";
                affiliateSymbol.classList.add("append-item-right");
                affiliateSymbol.innerHTML = "%";
            }

            if (affiliateType === "VALUE") {
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
                    updateView();
                }
            });

            fixedValueRadio.addEventListener("change", () => {
                if (fixedValueRadio.checked) {
                    percentageValue = affiliateValueInput.value; // Salva o valor percentual antes de mudar
                    affiliateValueInput.value = fixedValue; // Restaura o último valor fixo salvo
                    updateInputLimits();
                    updateView();
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
                    affiliateValueInput.placeholder = `0,00 a ${maxPriceOffersAffiliates.replace('.', ',')}`;
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

                updateView();
            };

            function currency(value) {
                value = value.replace(/\D/g, "");

                if (value) {
                    value = (parseFloat(value) / 100).toFixed(2);
                    value = value.replace(".", ",");
                    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                return '';
            }

            // Mascara para valor fixo
            function maskBrlCurrency(input) {
                input.value = currency(input.value)
            }

            // Força o valor fixo a ter maxPriceOffersAffiliates
            function enforceMaxValue(input) {
                let numericValue = parseFloat(input.value.replace(".", "").replace(",", "."));
                if (isNaN(numericValue) || numericValue < 0) {
                    input.value = "0,00";
                } else if (numericValue > maxPriceOffersAffiliates) {
                    input.value = parseFloat(maxPriceOffersAffiliates).toFixed(2).replace(".", ",");
                }
            }

            // Adiciona os listeners para alternar os limites
            percentageRadio.addEventListener("change", updateInputLimits);
            fixedValueRadio.addEventListener("change", updateInputLimits);
            affiliateValueInput.addEventListener("input", validateInput);

            // Inicializa as configurações ao carregar a página
            updateInputLimits();
            updateView();
        });
    </script>
@endpush
