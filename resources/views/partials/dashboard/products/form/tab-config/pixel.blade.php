@component('components.toggle', [
    'id' => 'pixel',
    'label' => 'Pixel',
    'isChecked' => $product->pixels->isNotEmpty(),
])
    <button
        class="button button-light mb-6 h-12 w-full rounded-full"
        data-drawer-target="drawerAddPixel"
        data-drawer-show="drawerAddPixel"
        data-drawer-placement="right"
        type="button"
    >
        @include('components.icon', [
            'icon' => 'add',
            'custom' => 'text-xl',
        ])
        Adicionar pixel
    </button>

    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
        <div class="overflow-x-scroll md:overflow-visible">
            <table
                class="table-lg table w-full"
                id="tablePixels"
            >
                <thead>
                    <tr>
                        <th>Serviço</th>
                        <th>Nome</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->pixels as $index => $pixel)
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
                                    'id' => 'dropdownMoreTablePixel' . $loop->iteration,
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
                                                data-drawer-target="drawerAddPixel"
                                                data-drawer-show="drawerAddPixel"
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
        </div>
    </div>
@endcomponent

@push('floating')
    <!-- FORM -->
    @component('components.drawer', [
        'id' => 'drawerAddPixel',
        'title' => 'Adicionar pixel',
        'custom' => 'max-w-2xl',
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
                        <option value="0">Selecione um serviço</option>

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
                            class="hidden"
                            id="select-icon"
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

                    <h4 class="productPixelName [&>img]:h-8">Facebook</h4>
                    <hr class="my-4">

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">
                            <label for="product[pixels][pixel_id]">ID do pixel</label>
                            <input
                                type="text"
                                id="product[pixels][pixel_id]"
                                name="product[pixels][pixel_id]"
                                placeholder="Digite o PIXEL ID"
                            />
                        </div>

                        <div class="col-span-12">
                            <label for="product[pixels][attributes][access_token]">Access Token</label>
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
                                <option value="">Selecione</option>
                                @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                    <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">

                            <div class="space-y-2">

                                @include('components.toggle', [
                                    'id' => 'addFacebookBoleto',
                                    'label' => 'Boleto',
                                    'contentEmpty' => true,
                                ])

                                @include('components.toggle', [
                                    'id' => 'addFacebookPix',
                                    'label' => 'Pix',
                                    'contentEmpty' => true,
                                ])

                                @include('components.toggle', [
                                    'id' => 'addFacebookBackendPurchase',
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

                    <h4 class="productPixelName [&>img]:h-8">Google</h4>
                    <hr class="my-4">

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">
                            <label for="product[pixels][pixel_id]">ID do pixel</label>
                            <input
                                type="text"
                                id="product[pixels][pixel_id]"
                                name="product[pixels][pixel_id]"
                                placeholder="Digite o PIXEL ID"
                            />
                        </div>

                        <div class="col-span-12">
                            <label for="product[pixels][attributes][conversionLabel]">Rótulo de conversão</label>
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
                                <option value="">Selecione</option>
                                @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                    <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">

                            <div class="space-y-2">

                                @include('components.toggle', [
                                    'id' => 'addGoogleAdsBoleto',
                                    'label' => 'Boleto',
                                    'contentEmpty' => true,
                                ])

                                @include('components.toggle', [
                                    'id' => 'addGoogleAdsPix',
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

                    <h4 class="productPixelName [&>img]:h-8">Taboola</h4>
                    <hr class="my-4">

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">
                            <label for="product[pixels][pixel_id]">ID do pixel</label>
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
                                <option value="">Selecione</option>
                                @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                    <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">

                            <div class="space-y-2">

                                @include('components.toggle', [
                                    'id' => 'addTaboolaBoleto',
                                    'label' => 'Boleto',
                                    'contentEmpty' => true,
                                ])

                                @include('components.toggle', [
                                    'id' => 'addTaboolaPix',
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

                    <h4 class="productPixelName [&>img]:h-8">Outbrain</h4>
                    <hr class="my-4">

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">
                            <label for="">ID do pixel</label>
                            <input type="text" />
                        </div>

                        <div class="col-span-12">
                            <label for="">Valor enviado para o pixel</label>
                            <select>
                                <option value="">Selecione</option>
                                <option value="">Valor total (com juros)</option>
                                <option value="">Valor dos produtos</option>
                            </select>
                        </div>

                        <div class="col-span-12">

                            <div class="space-y-2">

                                @include('components.toggle', [
                                    'id' => 'addOutbrainBoleto',
                                    'label' => 'Boleto',
                                    'contentEmpty' => true,
                                ])

                                @include('components.toggle', [
                                    'id' => 'addOutbrainPix',
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

                    <h4 class="productPixelName [&>img]:h-8">Pinterest</h4>
                    <hr class="my-4">

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">
                            <label for="product[pixels][pixel_id]">ID do pixel</label>
                            <input
                                type="text"
                                id="product[pixels][pixel_id]"
                                name="product[pixels][pixel_id]"
                                placeholder="Digite o PIXEL ID"
                            />
                        </div>

                        <div class="col-span-12">
                            <label for="product[pixels][attributes][tag_id]">Tag ID</label>
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
                                <option value="">Selecione</option>
                                @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                    <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">

                            <div class="space-y-2">

                                @include('components.toggle', [
                                    'id' => 'addPinterestBoleto',
                                    'label' => 'Boleto',
                                    'contentEmpty' => true,
                                ])

                                @include('components.toggle', [
                                    'id' => 'addPinterestPix',
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

                    <h4 class="productPixelName [&>img]:h-8">Tiktok</h4>
                    <hr class="my-4">

                    <div class="grid grid-cols-12 gap-6">

                        <div class="col-span-12">
                            <label for="product[pixels][pixel_id]">ID do pixel</label>
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
                                <option value="">Selecione</option>
                                @foreach (\App\Enums\TypeAmountPixelEnum::getDescriptions() as $item)
                                    <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">

                            <div class="space-y-2">

                                @include('components.toggle', [
                                    'id' => 'addTiktokBoleto',
                                    'label' => 'Boleto',
                                    'contentEmpty' => true,
                                ])

                                @include('components.toggle', [
                                    'id' => 'addTiktokPix',
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
                                    <h5 class="font-medium">Pixel ID</h5>
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
                                    <h5 class="font-medium">Valor enviado para o pixel</h5>
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

@push('custom-script')
    <script>
        $(document).on("change", ".pixelServiceSelect", function() {
            let selectElement = this;
            let selectedOption = this.options[this.selectedIndex];
            let pixelServiceName = selectedOption.getAttribute('data-pixelServiceName');

            let contents = document.querySelectorAll('[id$="FormContent"]');
            contents.forEach(function(content) {
                // Esconde todos os conteúdos
                content.style.display = 'none';

                // Limpa os campos do formulário correspondente
                let inputs = content.querySelectorAll('input, textarea, select');
                inputs.forEach(function(input) {
                    input.value = ''; // Reseta o valor do campo
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = false; // Reseta checkboxes e radios
                    }
                });
            });

            if (selectedOption.value !== "") {
                let contentId = pixelServiceName + 'FormContent';
                let contentElement = document.getElementById(contentId);
                if (contentElement) {
                    contentElement.style.display = 'block';
                }
            }

            let svgIcon = selectedOption.getAttribute('data-icon');
            let iconElement = document.getElementById('select-icon');

            // Atualiza o ícone
            iconElement.src = svgIcon;

            // Adiciona ou remove o padding-left (pl-16)
            if (selectedOption.value === "") {
                iconElement.style.display = 'none';
                selectElement.classList.remove('pl-16');
            } else {
                iconElement.style.display = 'block';
                selectElement.classList.add('pl-16');
            }

            let contentId = pixelServiceName + 'FormContent';

            $(`#${contentId} .productPixelName`).html(`<img src="${svgIcon}" alt="Product Pixel"/>`);
        });
    </script>

    <script>
        const keyInputPixel = "pixels";

        // Função para limpar os dados do drawer
        function clearDrawerData() {
            let drawer = $("#drawerAddPixel");

            // Limpa inputs de texto, número e textarea
            drawer.find("input[type='text'], input[type='number'], textarea").val('');
            // Desmarca checkboxes e radio buttons
            drawer.find("input[type='checkbox'], input[type='radio']").prop('checked', false);
            // Reseta o valor de selects
            drawer.find("select").val('0');

            // Esconde todos os conteúdos que terminam com 'FormContent'
            let contents = document.querySelectorAll('[id$="FormContent"]');
            contents.forEach(function(content) {
                content.style.display = 'none';
            });

            // Esconde o ícone de seleção
            $('#select-icon').hide();

            // Remove a classe 'pl-16' de todos os elementos com a classe 'pixelServiceSelect'
            $('.pixelServiceSelect').each(function() {
                this.classList.remove('pl-16');
            });

            // Atualiza os textos do botão e do título do drawer
            drawer.find(".addPixel").text("Adicionar pixel");
            drawer.find(".titleDrawer").text("Adicionar Pixel");
        }

        $(document).on("click", ".viewPixel", function() {
            const drawer = $("#drawerViewPixel");
            const data = $(this).data("pixel");
            console.log(data);

            // Função para definir o texto de um campo no drawer
            const setTextField = (field, value) => {
                drawer.find(`#productView\\[${keyInputPixel}\\]\\[${field}\\]`).text(value);
            };

            const valueSentToThePixel = {
                'AMOUNT_TOTAL_WITH_FEE': 'Volor total (com juros)',
                'AMOUNT_TOTAL_PRODUCTS': 'Valor dos produtos',
            };

            setTextField('service', data.pixel_service.name);
            setTextField('name', data.name);
            setTextField('pixel_id', data.pixel_id);
            setTextField('amountToSend', valueSentToThePixel[data.attributes.amountToSend]);

            const paymentLabels = {
                "mark_billet": "Boleto Bancário",
                "mark_pix": "Pix",
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
                        .join('')}
                </ul>
            `;

            document.querySelector('#payment-methods').innerHTML = renderPaymentMethods({
                "mark_billet": data.mark_billet,
                "mark_pix": data.mark_pix,
            });

            drawer.find(`#productView\\[pixels\\]\\[image_url\\]`).attr('src', data.pixel_service.image_url);
        });

        $(document).on("click", ".addPixel", function() {
            const $table = $("#tablePixels");
            const $inputs = $(".inputsPixels input, .inputsPixels select");
            let idPixel = $(`#product\\[${keyInputPixel}\\]\\[id\\]`).val();
            let isEdit = !!idPixel;

            let isValid = $inputs.toArray().every(input => !$(input).prop("required") || $(input).val());

            if (!isValid) {
                notyf.info("Preencha todos os campos obrigatórios");
                return false;
            }

            if (isEdit) {
                let $tr = $table.find(`tbody tr[data-id="${idPixel}"]`);

                $tr.find("td").eq(0).text(`${$(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\] option:selected`).text()}`);
                $tr.find("td").eq(1).text(`${$(`#product\\[${keyInputPixel}\\]\\[name\\]`).val()}`);

                for (let input of $inputs) {
                    if (input.name && input.value) {
                        if (input.type === "radio" || input.type === "checkbox") {
                            if (!input.checked) {
                                continue;
                            }
                        }

                        $tr.append(
                            `<input type="hidden" name="${input.name.replace(`product[${keyInputPixel}]`, `product[${keyInputPixel}][${idPixel}]`)}" value="${input.value}" />`
                        );
                    }
                }

                $inputs.filter("input[type='text']").val("");

                $("#drawerAddPixel .closeButton").trigger("click");

                return;
            }

            $table.find("tbody").append(`
                <tr>
                    <td>${$(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\] option:selected`).text()}</td>
                    <td>${$(`#product\\[${keyInputPixel}\\]\\[name\\]`).val()}</td>
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
                        `<input type="hidden" name="${input.name.replace(`product[${keyInputPixel}]`, `product[${keyInputPixel}][${index}]`)}" value="${input.value}" />`
                    );
                }
            }

            clearDrawerData();
            $("#drawerAddPixel .closeButton").trigger("click");
        });

        $(document).on("click", ".editPixel", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataPixel = $(this).data("pixel");
            let slugService = dataPixel.pixel_service.name.replace(/\s/g, '').toLowerCase();
            let divDrawerAttributes = drawer.find(`#${slugService}FormContent`);
            const $inputs = $(".inputsPixels input, .inputsPixels select");

            drawer.find(".titleDrawer").text("Editar dados pixel");

            drawer.find(`#product\\[${keyInputPixel}\\]\\[id\\]`).val(dataPixel.id);
            drawer.find(`#product\\[${keyInputPixel}\\]\\[name\\]`).val(dataPixel.name);

            drawer.find(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\] option`).prop('selected', false);
            drawer.find(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\] option[value="${dataPixel.pixel_service_id}"]`).prop('selected', true);
            drawer.find(`#product\\[${keyInputPixel}\\]\\[pixel_service_id\\]`).trigger("change");

            drawer.find(`#product\\[${keyInputPixel}\\]\\[mark_billet\\]`).val(dataPixel.mark_billet);
            drawer.find(`#product\\[${keyInputPixel}\\]\\[mark_pix\\]`).val(dataPixel.mark_pix);
            drawer.find(`#product\\[${keyInputPixel}\\]\\[attributes\\]\\[backend_purchase\\]`).val(dataPixel.attributes.backend_purchase || 0);

            if (slugService == 'facebook') {
                divDrawerAttributes.find(`#addFacebookBoleto`).prop('checked', Boolean(dataPixel.mark_billet));
                divDrawerAttributes.find(`#addFacebookPix`).prop('checked', Boolean(dataPixel.mark_pix));
                divDrawerAttributes.find(`#addFacebookBackendPurchase`).prop('checked', Boolean(parseInt(dataPixel.attributes.backend_purchase)));
            } if (slugService == 'google') {
                divDrawerAttributes.find(`#addGoogleAdsBoleto`).prop('checked', Boolean(dataPixel.mark_billet));
                divDrawerAttributes.find(`#addGoogleAdsPix`).prop('checked', Boolean(dataPixel.mark_pix));
            } if (slugService == 'taboola') {
                divDrawerAttributes.find(`#addTaboolaBoleto`).prop('checked', Boolean(dataPixel.mark_billet));
                divDrawerAttributes.find(`#addTaboolaPix`).prop('checked', Boolean(dataPixel.mark_pix));
            } if (slugService == 'outbrain') {
                divDrawerAttributes.find(`#addOutbrainBoleto`).prop('checked', Boolean(dataPixel.mark_billet));
                divDrawerAttributes.find(`#addOutbrainPix`).prop('checked', Boolean(dataPixel.mark_pix));
            } if (slugService == 'pinterest') {
                divDrawerAttributes.find(`#addPinterestBoleto`).prop('checked', Boolean(dataPixel.mark_billet));
                divDrawerAttributes.find(`#addPinterestPix`).prop('checked', Boolean(dataPixel.mark_pix));
            } if (slugService == 'tiktok') {
                divDrawerAttributes.find(`#addTiktokBoleto`).prop('checked', Boolean(dataPixel.mark_billet));
                divDrawerAttributes.find(`#addTiktokPix`).prop('checked', Boolean(dataPixel.mark_pix));
            }

            divDrawerAttributes.find(`#product\\[${keyInputPixel}\\]\\[pixel_id\\]`).val(dataPixel.attributes.pixel_id);

            if (dataPixel.attributes) {
                for (let key in dataPixel.attributes) {
                    let value = dataPixel.attributes[key];
                    let input = divDrawerAttributes.find(`#product\\[${keyInputPixel}\\]\\[attributes\\]\\[${key}\\]`);

                    if (input.is("select")) {
                        input.find(`option[value="${value}"]`).prop('selected', true);
                    } else {
                        input.val(value);
                    }
                }
            }

            drawer.find(".addPixel").text("Atualizar");
        });

        $(document).on("click", ".closeButton, [drawer-backdrop]", function() {
            clearDrawerData();
        });
    </script>

    <script>
        $(document).on("change", "#addFacebookBoleto, #addFacebookPix, #addFacebookBackendPurchase, #addGoogleAdsBoleto, #addGoogleAdsPix, #addTaboolaBoleto, #addTaboolaPix, #addOutbrainBoleto, #addOutbrainPix, #addPinterestBoleto, #addPinterestPix, #addTiktokBoleto, #addTiktokPix", function() {
            let $this     = $(this);
            let textId    = $this.attr("id");
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
