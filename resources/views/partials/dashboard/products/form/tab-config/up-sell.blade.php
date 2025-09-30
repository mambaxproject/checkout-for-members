@component('components.toggle', [
    'id' => 'upSells',
    'label' => 'UpSells',
    'isChecked' => $product->upSells->isNotEmpty(),
])
    <button
        class="button button-light mb-6 h-12 w-full rounded-full"
        data-drawer-target="drawerAddUpSell"
        data-drawer-show="drawerAddUpSell"
        data-drawer-placement="right"
        type="button"
    >
        @include('components.icon', [
            'icon' => 'add',
            'custom' => 'text-xl',
        ])
        Adicionar UpSell
    </button>

    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
        <div class="overflow-x-scroll md:overflow-visible">
            <table
                class="table-lg table w-full"
                id="tableUpSells"
            >
                <thead>
                    <tr>
                        <th>Nome do upsell</th>
                        <th>Produto</th>
                        <th>Oferta</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->upSells as $upSell)
                        <tr data-id="{{ $upSell->id }}">
                            <input
                                type="hidden"
                                name="product[upSells][{{ $upSell->id }}][id]"
                                value="{{ $upSell->id }}"
                            />

                            <td>{{ $upSell->name }}</td>
                            <td>{{ $upSell->product->name }}</td>
                            <td>{{ $upSell->product_offer->name }}</td>
                            <td class="text-end">
                                @component('components.dropdown-button', [
                                    'id' => 'dropdownMoreTableUpsell' . $loop->iteration,
                                    'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                    'custom' => 'text-xl',
                                ])
                                    <ul>
                                        <li>
                                            @php
                                                $html = view('components.upSell.copyUpSellCode', ['upsell' => $upSell])->render();
                                            @endphp
                                            <a
                                                class="copyClipboard flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-clipboard-text="{{ $html }}"
                                                title="Copiar código UpSell"
                                                href="javascript:void(0)"
                                            >
                                                Copiar código
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="viewUpSell flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-upSell="{{ $upSell }}"
                                                href="javascript:void(0)"
                                                title="Visualizar"
                                                data-drawer-target="drawerViewUpSell"
                                                data-drawer-show="drawerViewUpSell"
                                                data-drawer-placement="right"
                                            >
                                                Visualizar
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="editUpSell flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-upSell="{{ $upSell }}"
                                                href="javascript:void(0)"
                                                data-drawer-target="drawerAddUpSell"
                                                data-drawer-show="drawerAddUpSell"
                                                data-drawer-placement="right"
                                            >
                                                Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="duplicateUpSell flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-upSell="{{ $upSell }}"
                                                href="javascript:void(0)"
                                                data-drawer-target="drawerAddUpSell"
                                                data-drawer-show="drawerAddUpSell"
                                                data-drawer-placement="right"
                                            >
                                                Duplicar
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
        </div>
    </div>
@endcomponent

@push('floating')
    <!-- FORM -->
    @component('components.drawer', [
        'id' => 'drawerAddUpSell',
        'title' => 'Adicionar UpSell',
        'custom' => 'max-w-2xl',
    ])
        <div class="inputsUpSell">

            <input
                type="hidden"
                id="product[upSells][id]"
                name="product[upSells][id]"
                value=""
            />

            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-12">
                    <label for="product[upSells][name]">Nome do Upsell</label>
                    <input
                        type="text"
                        id="product[upSells][name]"
                        name="product[upSells][name]"
                        placeholder="Digite do nome do Upsell"
                        required
                    >
                </div>

                <div class="col-span-12">
                    <label for="product[upSells][product_id]">Produto UpSell</label>
                    <select
                        name="product[upSells][product_id]"
                        id="product[upSells][product_id]"
                        required
                    >
                        <option value="">Selecione</option>
                        @foreach ($productsShop as $productShop)
                            <option
                                value="{{ $productShop->id }}"
                                data-offers="{{ $productShop->offers }}"
                            >
                                {{ $productShop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="product[upSells][product_offer_id]">Oferta UpSell</label>
                    <select
                        name="product[upSells][product_offer_id]"
                        id="product[upSells][product_offer_id]"
                        required
                    >
                        <option value="">Selecione o produto primeiro</option>
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="product[upSells][when_offer]">Quando Oferecer</label>
                    <select
                        name="product[upSells][when_offer]"
                        id="product[upSells][when_offer]"
                    >
                        @foreach (config('products.whenOfferUpSell') as $item)
                            <option value="{{ $item['value'] }}">
                                {{ $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="product[upSells][when_accept]">Ao aceita upsell</label>
                    <select
                        name="product[upSells][when_accept]"
                        id="product[upSells][when_accept]"
                        class="upsell-select"
                        data-target="acceptingUpsells"
                    >
                        @foreach (config('products.whenAcceptUpSell') as $item)
                            <option value="{{ $item['value'] }}">
                                {{ $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div
                    class="col-span-12 hidden"
                    id="acceptingUpsells"
                >
                    <label for="product[upSells][attributes][urlAnotherUpSell]">URL da próxima upsell</label>
                    <div class="append">
                        <input
                            type="url"
                            id="product[upSells][attributes][urlAnotherUpSell]"
                            name="product[upSells][attributes][urlAnotherUpSell]"
                            class="pl-12"
                            placeholder="https://example.com.br"
                        >
                        <div class="append-item-left w-12">
                            @include('components.icon', [
                                'icon' => 'link',
                                'custom' => 'text-xl',
                            ])
                        </div>
                    </div>
                </div>

                <div class="col-span-12">
                    <label for="">Ao recusar upsell</label>
                    <select
                        name="product[upSells][when_reject]"
                        id="product[upSells][when_reject]"
                        class="upsell-select"
                        data-target="refusingUpsells"
                    >
                        @foreach (config('products.whenRejectUpSell') as $item)
                            <option value="{{ $item['value'] }}">
                                {{ $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div
                    class="col-span-12 hidden"
                    id="refusingUpsells"
                >
                    <label for="product[upSells][attributes][urlDownSell]">URL da downsell</label>
                    <div class="append">
                        <input
                            type="url"
                            id="product[upSells][attributes][urlDownSell]"
                            name="product[upSells][attributes][urlDownSell]"
                            class="pl-12"
                            placeholder="https://example.com.br"
                        >
                        <div class="append-item-left w-12">
                            @include('components.icon', [
                                'icon' => 'link',
                                'custom' => 'text-xl',
                            ])
                        </div>
                    </div>
                </div>

                <div class="col-span-12">

                    <h3>Configuraçõe de layout</h3>

                </div>

                <div class="col-span-12">
                    <label for="product[upSells][text_accept]">Texto de aceite</label>
                    <input
                        type="text"
                        id="product[upSells][text_accept]"
                        name="product[upSells][text_accept]"
                        placeholder="Sim, eu aceito essa oferta especial!"
                        value="Sim, eu aceito essa oferta especial!"
                        required
                    >
                </div>

                <div class="col-span-12">
                    <label for="product[upSells][text_reject]">Texto de recusa</label>
                    <input
                        type="text"
                        id="product[upSells][text_reject]"
                        name="product[upSells][text_reject]"
                        placeholder="Não, eu recuso essa oferta"
                        value="Não, eu recuso essa oferta"
                        required
                    >
                </div>

                <div class="col-span-12">

                    <div class="space-y-3">

                        @include('components.form.toggle', [
                            'id' => 'product[upSells][attributes][showProductTitle]',
                            'name' => 'product[upSells][attributes][showProductTitle]',
                            'label' => 'Mostrar nome do produto',
                            'checked' => true,
                        ])

                        @include('components.form.toggle', [
                            'id' => 'product[upSells][attributes][showProductPrice]',
                            'name' => 'product[upSells][attributes][showProductPrice]',
                            'label' => 'Mostrar valor do produto',
                            'checked' => true,
                        ])

                        @include('components.form.toggle', [
                            'id' => 'product[upSells][attributes][showProductImage]',
                            'name' => ' product[upSells][attributes][showProductImage]',
                            'label' => 'Mostrar imagem do produto',
                            'checked' => true,
                        ])

                    </div>

                </div>

                <div class="col-span-12">
                    <label
                        class="flex items-center gap-2"
                        for="product[upSells][color_button_accept]"
                    >
                        <input
                            type="color"
                            id="product[upSells][color_button_accept]"
                            name="product[upSells][color_button_accept]"
                            value="#34cc33"
                        >
                        Cor do botão
                    </label>
                </div>

                <div class="col-span-12">
                    <div class="">

                        <p class="mb-1 text-sm font-semibold">Pré Visualização</p>

                        <div class="flex w-full flex-col items-center justify-center gap-2 rounded-lg border border-neutral-200 p-6">

                            <div class="space-y-3">

                                <figure class="showProductImage">

                                    <img
                                        class="mx-auto rounded-lg"
                                        src="https://placehold.co/180x120"
                                        alt="Nome do produto"
                                        loading="lazy"
                                    >

                                </figure>

                                <div class="space-y-2">

                                    <div class="space-y-0.5">

                                        <h3 class="showProductName text-center text-lg">Nome do Produto</h3>

                                        <p class="showProductPrice text-center text-sm text-neutral-400">R$ 123,00</p>

                                    </div>

                                    <div class="space-y-1">

                                        <div
                                            class="buttonAcceptUpSellTemplate button h-12 rounded-full text-white"
                                            href="#"
                                        >
                                            Sim, eu aceito essa oferta especial!
                                        </div>

                                        <div
                                            class="buttonRejectUpSellTemplate button h-12 rounded-full hover:text-danger-500"
                                            href="#"
                                        >
                                            Não, eu recuso essa oferta
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

        <button
            class="addUpSell button button-primary mt-2 h-12 w-full gap-1 rounded-full"
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
        'id' => 'drawerViewUpSell',
        'title' => 'Ver up sell',
        'custom' => 'max-w-2xl',
    ])
        <div class="grid grid-cols-12 gap-x-4 divide-y divide-neutral-100">
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Nome do Upsell</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][name]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Produto UpSell</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][product_name]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Oferta UpSell</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][offer_name]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Quando Oferecer</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][when_offer]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Ao aceita upsell</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][when_accept]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Ao recusar upsell</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][when_reject]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Texto de aceite</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][text_accept]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Texto de recusa</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[upSells][text_reject]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">

                    <h5 class="font-medium">Pré Visualização</h5>
                    <div class="flex h-[200px] w-full flex-col items-center justify-center gap-2 rounded-lg bg-neutral-100">

                        <button
                            class="buttonAcceptUpSell button h-12 rounded-full text-white"
                            type="button"
                        >
                            Sim, eu aceito essa oferta especial!
                        </button>

                        <button
                            class="buttonRejectUpSell button h-12 rounded-full hover:text-danger-500"
                            type="button"
                        >
                            Não, eu recuso essa oferta
                        </button>

                    </div>

                </div>
            </div>
        </div>
    @endcomponent
@endpush

@push('custom-script')
    <script>
        $(document).ready(function() {
            $('.upsell-select').change(function() {
                let targetId = $(this).data('target');
                let targetContainer = $('#' + targetId);
                let selectedValue = $(this).val();

                targetContainer.toggle(selectedValue === "OFFER_ANOTHER_UPSELL" || selectedValue === "OFFER_DOWNSELL");
            });

            $('.upsell-select').trigger('change');
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.buttonAcceptUpSellTemplate').css('background-color', "#34cc33");

            $('#product\\[upSells\\]\\[text_accept\\]').on('input', function() {
                const textAccept = $(this).val();
                $('.buttonAcceptUpSellTemplate').text(textAccept);
            });

            $('#product\\[upSells\\]\\[text_reject\\]').on('input', function() {
                const textReject = $(this).val();
                $('.buttonRejectUpSellTemplate').text(textReject);
            });

            $('#product\\[upSells\\]\\[color_button_accept\\]').on('input', function() {
                const color = $(this).val();
                $('.buttonAcceptUpSellTemplate').css('background-color', color);
            });

            $('#product\\[upSells\\]\\[attributes\\]\\[showProductTitle\\]').change(function() {
                $('.showProductName').toggle(this.checked);
            });

            $('#product\\[upSells\\]\\[attributes\\]\\[showProductPrice\\]').change(function() {
                $('.showProductPrice').toggle(this.checked);
            });

            $('#product\\[upSells\\]\\[attributes\\]\\[showProductImage\\]').change(function() {
                $('.showProductImage').toggle(this.checked);
            });
        })
    </script>
    <script>
        const keyInputUpSell = "upSells";

        $(document).on("click", ".viewUpSell", function() {
            const drawer = $("#drawerViewUpSell");
            const data = JSON.parse($(this).attr('data-upSell'));
            console.log(data);


            // Função para definir o texto de um campo no drawer
            const setTextField = (field, value) => {
                drawer.find(`#productView\\[${keyInputUpSell}\\]\\[${field}\\]`).text(value);
            };

            // Função para definir links em casos específicos
            const setLinkField = (field, url) => {
                drawer.find(`#productView\\[${keyInputUpSell}\\]\\[${field}\\]`).html(`
                    <a class="text-primary" href="${url}">${url}</a>
                `);
            };

            // Preenchendo campos do drawer com os dados
            setTextField('name', data.name);
            setTextField('product_name', data.product.name);
            setTextField('offer_name', data.product_offer.name);
            setTextField('text_accept', data.text_accept);
            setTextField('text_reject', data.text_reject);

            // Tratamento para o campo AFTER_ORDER_WITH_CREDIT_CARD
            if (data.when_offer === 'AFTER_ORDER_WITH_CREDIT_CARD') {
                setTextField('when_offer', 'Após a finalização do pedido (somente para compras por cartão de crédito)');
            }

            // Tratamento para o campo OFFER_ANOTHER_UPSELL
            if (data.when_accept === 'OFFER_ANOTHER_UPSELL') {
                setLinkField('when_accept', data.attributes.urlAnotherUpSell);
            } else {
                setTextField('when_accept', 'Redireciona para página de obrigado');
            }

            // Tratamento para o campo OFFER_DOWNSELL
            if (data.when_reject === 'OFFER_DOWNSELL') {
                setLinkField('when_reject', data.attributes.urlDownSell);
            } else {
                setTextField('when_reject', 'Redireciona para página de obrigado');
            }

            $('.buttonAcceptUpSell').css('background-color', data.color_button_accept);
            $('.buttonAcceptUpSell').text(data.text_accept);
            $('.buttonRejectUpSell').text(data.text_reject);

        });

        $(document).on("click", ".addUpSell", function() {
            const $table = $("#tableUpSells");
            const $inputs = $(".inputsUpSell input, .inputsUpSell select");
            let idUpSell = $(`#product\\[${keyInputUpSell}\\]\\[id\\]`).val();
            let isEdit = !!idUpSell;

            let isValid = $inputs.toArray().every(input => !$(input).prop("required") || $(input).val());

            if (!isValid) {
                notyf.info("Preencha todos os campos obrigatórios");
                return false;
            }

            if (isEdit) {
                let $tr = $table.find(`tbody tr[data-id="${idUpSell}"]`);

                $tr.find("td").eq(0).text(`${$(`#product\\[${keyInputUpSell}\\]\\[name\\]`).val()}`);
                $tr.find("td").eq(1).text(`${$(`#product\\[${keyInputUpSell}\\]\\[product_id\\] option:selected`).text()}`);
                $tr.find("td").eq(2).text(`${$(`#product\\[${keyInputUpSell}\\]\\[product_offer_id\\] option:selected`).text()}`);

                for (let input of $inputs) {
                    if (input.name && input.value) {
                        if (input.type === "radio" || input.type === "checkbox") {
                            if (!input.checked) {
                                continue;
                            }
                        }

                        $tr.append(
                            `<input type="hidden" name="${input.name.replace(`product[${keyInputUpSell}]`, `product[${keyInputUpSell}][${idUpSell}]`)}" value="${input.value}" />`
                        );
                    }
                }

                $inputs.filter("input[type='text']").val("");

                $("#drawerAddUpSell .closeButton").trigger("click");

                return;
            }

            $table.find("tbody").append(`
                <tr>
                    <td>${$(`#product\\[${keyInputUpSell}\\]\\[name\\]`).val()}</td>
                    <td>${$(`#product\\[${keyInputUpSell}\\]\\[product_id\\] option:selected`).text()}</td>
                    <td>${$(`#product\\[${keyInputUpSell}\\]\\[product_offer_id\\] option:selected`).text()}</td>
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

                    if (input.type === "radio" || input.type === "checkbox") {
                        if (!input.checked) {
                            continue;
                        }
                    }

                    $table.find("tbody tr").eq(index).append(
                        `<input type="hidden" name="${input.name.replace(`product[${keyInputUpSell}]`, `product[${keyInputUpSell}][${index}]`)}" value="${input.value}" />`
                    );
                }
            }

            $inputs.filter("input[type='text']").val("");

            $("#drawerAddUpSell .closeButton").trigger("click");
        });

        $(document).on("click", ".editUpSell", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataUpSell = $(this).data("upsell");

            drawer.find(".titleDrawer").text("Editar dados UpSell");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[id\\]`).val(dataUpSell.id);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[name\\]`).val(dataUpSell.name);

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_id\\] option[value="${dataUpSell.product_id}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_id\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_offer_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_offer_id\\] option[value="${dataUpSell.product_offer_id}"]`).prop('selected', true);

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_offer\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_offer\\] option[value="${dataUpSell.when_offer}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_offer\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_accept\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_accept\\] option[value="${dataUpSell.when_accept}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_accept\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_reject\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_reject\\] option[value="${dataUpSell.when_reject}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_reject\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[text_accept\\]`).val(dataUpSell.text_accept);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[text_reject\\]`).val(dataUpSell.text_reject);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[color_button_accept\\]`).val(dataUpSell.color_button_accept);

            drawer.find('input[type="checkbox"]').prop('checked', false);

            if (dataUpSell.attributes) {
                for (let key in dataUpSell.attributes) {
                    let value = dataUpSell.attributes[key];
                    let input = drawer.find(`#product\\[${keyInputUpSell}\\]\\[attributes\\]\\[${key}\\]`);

                    if (input.is("select")) {
                        input.find(`option[value="${value}"]`).prop('selected', true);
                    } else if (input.is('input[type="checkbox"]')) {
                        input.prop('checked', Boolean(value));
                        $('.'+key).toggle(Boolean(value));
                    } else {
                        input.val(value);
                    }
                }
            }

            drawer.find(".addUpSell").text("Atualizar");
        });

        $(document).on("click", ".duplicateUpSell", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataUpSell = $(this).data("upsell");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_id\\] option[value="${dataUpSell.product_id}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_id\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_offer_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[product_offer_id\\] option[value="${dataUpSell.product_offer_id}"]`).prop('selected', true);

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_offer\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_offer\\] option[value="${dataUpSell.when_offer}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_offer\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_accept\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_accept\\] option[value="${dataUpSell.when_accept}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_accept\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_reject\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_reject\\] option[value="${dataUpSell.when_reject}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[when_reject\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputUpSell}\\]\\[text_accept\\]`).val(dataUpSell.text_accept);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[text_reject\\]`).val(dataUpSell.text_reject);
            drawer.find(`#product\\[${keyInputUpSell}\\]\\[color_button_accept\\]`).val(dataUpSell.color_button_accept);

            drawer.find('input[type="checkbox"]').prop('checked', false);

            if (dataUpSell.attributes) {
                for (let key in dataUpSell.attributes) {
                    let value = dataUpSell.attributes[key];
                    let input = drawer.find(`#product\\[${keyInputUpSell}\\]\\[attributes\\]\\[${key}\\]`);

                    if (input.is("select")) {
                        input.find(`option[value="${value}"]`).prop('selected', true);
                    } else if (input.is('input[type="checkbox"]')) {
                        input.prop('checked', Boolean(value));
                    } else {
                        input.val(value);
                    }
                }
            }
        });

        $(document).on("change", "#product\\[upSells\\]\\[product_id\\]", function() {
            let offers = $(this).find("option:selected").data("offers");

            let $select = $(`#product\\[${keyInputUpSell}\\]\\[product_offer_id\\]`);
            $select.empty();

            $select.append(`<option value="">Selecione</option>`);

            for (let offer of offers) {
                $select.append(`<option value="${offer.id}">${offer.name}</option>`);
            }

            $select.trigger("change");
        });
    </script>
@endpush
