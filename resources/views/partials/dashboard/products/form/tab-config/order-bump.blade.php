@component('components.toggle', [
    'id' => 'orderBump',
    'label' => 'OrderBump',
    'isChecked' => $product->orderBumps->isNotEmpty(),
])
    <button
        class="button button-light mb-6 h-12 w-full rounded-full"
        data-drawer-target="drawerAddOrderbumps"
        data-drawer-show="drawerAddOrderbumps"
        data-drawer-placement="right"
        type="button"
    >
        @include('components.icon', ['icon' => 'add', 'custom' => 'text-xl'])
        Adicionar Order Bump
    </button>

    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
        <div class="overflow-x-scroll md:overflow-visible">
            <table
                class="table-lg table w-full"
                id="tableOrderBumps"
            >
                <thead>
                    <tr>
                        <th>Nome do Order Bump</th>
                        <th>Produto</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->orderBumps as $orderBump)
                        <tr data-id="{{ $orderBump->id }}">

                            <input
                                type="hidden"
                                name="product[orderBumps][{{ $orderBump->id }}][id]"
                                value="{{ $orderBump->id }}"
                            />

                            <td>{{ $orderBump->name }}</td>
                            <td>{{ $orderBump->product->name }}</td>
                            <td>{{ $orderBump->statusFormatted }}</td>
                            <td class="text-end">
                                @component('components.dropdown-button', [
                                    'id' => 'dropdownMoreTableOrderBump' . $loop->iteration,
                                    'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                    'custom' => 'text-xl',
                                ])
                                    <ul>
                                        <li>
                                            <a
                                                class="viewOrderBump flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-orderBump="{{ $orderBump }}"
                                                href="javascript:void(0)"
                                                title="Visualizar"
                                                data-drawer-target="drawerViewOrderBump"
                                                data-drawer-show="drawerViewOrderBump"
                                                data-drawer-placement="right"
                                            >
                                                Visualizar
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="editOrderBump flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-orderBump="{{ $orderBump }}"
                                                href="javascript:void(0)"
                                                data-drawer-target="drawerAddOrderbumps"
                                                data-drawer-show="drawerAddOrderbumps"
                                                data-drawer-placement="right"
                                            >
                                                Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="duplicateOrderBump flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-orderBump="{{ $orderBump }}"
                                                href="javascript:void(0)"
                                                data-drawer-target="drawerAddOrderbumps"
                                                data-drawer-show="drawerAddOrderbumps"
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
        'id' => 'drawerAddOrderbumps',
        'title' => 'Adicionar Order Bump',
        'custom' => 'max-w-2xl',
    ])
        <div class="inputsOrderBump grid grid-cols-12 gap-4">
            <input
                type="hidden"
                id="product[orderBumps][id]"
                name="product[orderBumps][id]"
                value=""
            />

            <div class="col-span-12">
                <label for="product[orderBumps][name]">Nome do Order Bump</label>
                <input
                    type="text"
                    id="product[orderBumps][name]"
                    name="product[orderBumps][name]"
                    placeholder="Digite o nome do order bump"
                    required
                />
            </div>

            <div class="col-span-12">
                <label for="product[orderBumps][product_id]">Produto</label>
                <select
                    name="product[orderBumps][product_id]"
                    id="product[orderBumps][product_id]"
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

                @component('components.toggle', [
                    'id' => 'showOrderbumpsImageProduct',
                    'label' => 'Exibir imagem do produto',
                    'margin' => '!mb-0',
                    'contentEmpty' => false,
                ])
                @endcomponent

            </div>

            <div class="col-span-12">
                <label for="">Oferta</label>
                <select
                    name="product[orderBumps][product_offer_id]"
                    id="product[orderBumps][product_offer_id]"
                    required
                >
                    <option value="">Selecione o produto primeiro</option>
                </select>
            </div>

            <div class="col-span-12">
                <label for="product[orderBumps][title_cta]">Chamada</label>
                <input
                    type="text"
                    id="product[orderBumps][title_cta]"
                    name="product[orderBumps][title_cta]"
                    placeholder="Sim, eu aceito essa oferta especial!"
                    value="Sim, eu aceito essa oferta especial!"
                    required
                />
            </div>

            <div class="col-span-12">
                <label for="product[orderBumps][description]">Descrição</label>
                <textarea
                    rows="4"
                    id="product[orderBumps][description]"
                    name="product[orderBumps][description]"
                ></textarea>
            </div>

            <div class="col-span-12">
                <label for="product[orderBumps][promotional_price]">Preço promocional</label>
                <div class="append">
                    <input
                        type="text"
                        id="product[orderBumps][promotional_price]"
                        name="product[orderBumps][promotional_price]"
                        class="pl-12"
                        placeholder="0,00"
                        autocomplete="off"
                        oninput="setCurrencyMask(this)"
                        required
                    />
                    <span class="append-item-left w-12">R$</span>
                </div>
            </div>

            <div class="col-span-12">

                <h4>Métodos de pagamento</h4>
                <hr class="my-2">

                <div class="mt-4 space-y-4">
                    @foreach (\App\Enums\PaymentMethodEnum::getDescriptions() as $paymentMethod)
                        <label
                            for="paymentMethodOrderBump_{{ $paymentMethod['value'] }}"
                            class="flex cursor-pointer items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                id="paymentMethodOrderBump_{{ $paymentMethod['value'] }}"
                                name="product[orderBumps][payment_methods][]"
                                value="{{ $paymentMethod['value'] }}"
                                checked
                            />

                            {{ $paymentMethod['name'] }}
                        </label>
                    @endforeach
                </div>

            </div>
        </div>

        <button
            class="addOrderBump button button-primary mt-8 h-12 w-full gap-1 rounded-full"
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
        'id' => 'drawerViewOrderBump',
        'title' => 'Ver order bump',
        'custom' => 'max-w-2xl translate-x-0',
    ])
        <div class="grid grid-cols-12 gap-x-4 divide-y divide-neutral-100">
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Nome do Order Bump</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[orderBumps][name]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Produto</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[orderBumps][product_name]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Oferta</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[orderBumps][offer]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Chamada</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[orderBumps][title_cta]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Descrição</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[orderBumps][description]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Preço promocional</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[orderBumps][promotional_price]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-semibold">Métodos de pagamento</h5>
                    <div
                        class="rounded-xl bg-neutral-100 p-4"
                        id="productView[orderBumps][payment_methods]"
                    >
                    </div>
                </div>
            </div>
        </div>
    @endcomponent
@endpush

@push('custom-script')
    <script>
        const keyInputOrderBump = "orderBumps";

        $(document).on("click", ".viewOrderBump", function() {
            const drawer = $("#drawerViewOrderBump");
            const data = JSON.parse($(this).attr('data-orderBump'));

            // Helper function to set text for drawer fields
            const setTextField = (field, value) => {
                drawer.find(`#productView\\[${keyInputOrderBump}\\]\\[${field}\\]`).text(value);
            };

            // Set basic product information
            setTextField('name', data.name);
            setTextField('product_name', data.product.name);
            setTextField('title_cta', data.title_cta);
            setTextField('description', data.description);
            setTextField('offer', data.product_offer.name);

            const resultPromotionalPrice = formatCurrencyBR(data.promotional_price);
            setTextField('promotional_price', `R$ ${resultPromotionalPrice}`);

            // Generate list of payment methods
            const paymentMethodsList = generateList(data.payment_methods, paymentMethodTemplate, 'space-y-1');

            // Insert the payment methods into the drawer
            drawer.find(`#productView\\[${keyInputOrderBump}\\]\\[payment_methods\\]`).html(paymentMethodsList);
        });

        $(document).on("click", ".addOrderBump", function() {
            const $table = $("#tableOrderBumps");
            const $inputs = $(".inputsOrderBump input, .inputsOrderBump textarea, .inputsOrderBump select");
            let idOrderBump = $(`#product\\[${keyInputOrderBump}\\]\\[id\\]`).val();
            let isEdit = !!idOrderBump;

            let isValid = $inputs.toArray().every(input => !$(input).prop("required") || $(input).val());

            if (!isValid) {
                notyf.info("Preencha todos os campos obrigatórios");
                return false;
            }

            if (isEdit) {
                let $tr = $table.find(`tbody tr[data-id="${idOrderBump}"]`);

                $tr.find("td").eq(0).text(`${$(`#product\\[${keyInputOrderBump}\\]\\[name\\]`).val()}`);
                $tr.find("td").eq(1).text(`${$(`#product\\[${keyInputOrderBump}\\]\\[product_id\\] option:selected`).text()}`);

                for (let input of $inputs) {
                    if (input.name && input.value) {
                        if (input.type === "radio" || input.type === "checkbox") {
                            if (!input.checked) {
                                continue;
                            }
                        }

                        $tr.append(
                            `<input type="hidden" name="${input.name.replace(`product[${keyInputOrderBump}]`, `product[${keyInputOrderBump}][${idOrderBump}]`)}" value="${input.value}" />`
                        );
                    }
                }

                $inputs.filter("input[type='text']").val("");

                $("#drawerAddOrderbumps .closeButton").trigger("click");

                return;
            }

            $table.find("tbody").append(`
                <tr>
                    <td>${$(`#product\\[${keyInputOrderBump}\\]\\[name\\]`).val()}</td>
                    <td>${$(`#product\\[${keyInputOrderBump}\\]\\[product_id\\] option:selected`).text()}</td>
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
                        `<input type="hidden" name="${input.name.replace(`product[${keyInputOrderBump}]`, `product[${keyInputOrderBump}][${index}]`)}" value="${input.value}" />`
                    );
                }
            }

            $inputs.filter("input[type='text']").val("");

            $("#drawerAddOrderbumps .closeButton").trigger("click");
        });

        $(document).on("click", ".editOrderBump", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataOrderBump = $(this).data("orderbump");

            drawer.find(".titleDrawer").text("Editar dados order bump");

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[id\\]`).val(dataOrderBump.id);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[name\\]`).val(dataOrderBump.name);

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_id\\] option[value="${dataOrderBump.product_id}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_id\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_offer_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_offer_id\\] option[value="${dataOrderBump.product_offer_id}"]`).prop('selected', true);

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[description\\]`).val(dataOrderBump.description);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[title_cta\\]`).val(dataOrderBump.title_cta);

            drawer.find(`input[name="product[${keyInputOrderBump}][payment_methods][]"]`).prop('checked', false);
            for (let paymentMethod of dataOrderBump.payment_methods) {
                drawer.find(`#paymentMethodOrderBump_${paymentMethod}`).prop('checked', true);
            }

            const resultPromotionalPrice = formatCurrencyBR(dataOrderBump.promotional_price)
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[promotional_price\\]`).val(resultPromotionalPrice);

            drawer.find(".addOrderBump").text("Atualizar");
        });

        $(document).on("click", ".duplicateOrderBump", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataOrderBump = $(this).data("orderbump");

            drawer.find(".titleDrawer").text("Duplicar order bump");

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[name\\]`).val(dataOrderBump.name + " - Cópia");

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_id\\] option[value="${dataOrderBump.product_id}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_id\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_offer_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[product_offer_id\\] option[value="${dataOrderBump.product_offer_id}"]`).prop('selected', true);

            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[description\\]`).val(dataOrderBump.description);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[title_cta\\]`).val(dataOrderBump.title_cta);
            drawer.find(`#product\\[${keyInputOrderBump}\\]\\[promotional_price\\]`).val(dataOrderBump.promotional_price);

            drawer.find(`input[name="product[${keyInputOrderBump}][payment_methods][]"]`).prop('checked', false);
            for (let paymentMethod of dataOrderBump.payment_methods) {
                drawer.find(`#paymentMethodOrderBump_${paymentMethod}`).prop('checked', true);
            }

            drawer.find("#addOrderBump").text("Adicionar");
        });

        $(document).on("change", "#product\\[orderBumps\\]\\[product_id\\]", function() {
            let offers = $(this).find("option:selected").data("offers");

            let $select = $("#product\\[orderBumps\\]\\[product_offer_id\\]");
            $select.empty();

            $select.append(`<option value="">Selecione</option>`);

            for (let offer of offers) {
                $select.append(`<option value="${offer.id}">${offer.name}</option>`);
            }

            $select.trigger("change");
        });
    </script>
@endpush
