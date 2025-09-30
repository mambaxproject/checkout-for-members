<input type="hidden"
       name="checkout[settings][allowCouponsDiscounts]"
       value="{{ (($product->checkout->settings['allowCouponsDiscounts'] ?? false) && $product->couponsDiscount->isNotEmpty()) }}"
/>

@component('components.toggle', [
    'id' => 'couponsDiscount',
    'label' => 'Cupom de desconto',
    'isChecked' => (($product->checkout->settings['allowCouponsDiscounts'] ?? false) && $product->couponsDiscount->isNotEmpty()),
])
    <button
        class="addCouponDiscount button button-light mb-6 h-12 w-full rounded-full"
        data-url="{{ route('dashboard.coupon-discounts.store') }}"
        data-drawer-target="drawerAddCoupons"
        data-drawer-show="drawerAddCoupons"
        data-drawer-placement="right"
        type="button"
    >
        @include('components.icon', [
            'icon' => 'add',
            'custom' => 'text-xl',
        ])
        Adicionar cupom
    </button>

    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
        <div class="overflow-x-scroll md:overflow-visible">
            <table
                class="table-lg table w-full"
                id="tableCouponDiscount"
            >
                <thead>
                    <tr>
                        <th>Código do cupom</th>
                        <th>Tipo do desconto</th>
                        <th>Valor do cupom</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->couponsDiscount as $coupon)
                        <tr data-id="{{ $coupon->id }}">

                            <input
                                type="hidden"
                                name="product[couponsDiscount][{{ $coupon->id }}][id]"
                                value="{{ $coupon->id }}"
                            />

                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->typeDiscountTranslated }}</td>
                            <td>{{ $coupon->amountFormatted }}</td>
                            <td class="text-end">
                                @component('components.dropdown-button', [
                                    'id' => 'dropdownMoreTableCoupons' . $loop->iteration,
                                    'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                    'custom' => 'text-xl',
                                ])
                                    <ul>
                                        <li>
                                            <a
                                                class="viewCouponDiscount flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-coupon="{{ $coupon }}"
                                                href="javascript:void(0)"
                                                title="Visualizar"
                                                data-drawer-target="drawerViewCoupons"
                                                data-drawer-show="drawerViewCoupons"
                                                data-drawer-placement="right"
                                            >
                                                Visualizar
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="editCouponDiscount flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-coupon="{{ $coupon }}"
                                                href="javascript:void(0)"
                                                data-drawer-target="drawerAddCoupons"
                                                data-drawer-show="drawerAddCoupons"
                                                data-drawer-placement="right"
                                            >
                                                Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="duplicateCouponDiscount flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                data-coupon="{{ $coupon }}"
                                                href="javascript:void(0)"
                                                data-drawer-target="drawerAddCoupons"
                                                data-drawer-show="drawerAddCoupons"
                                                data-drawer-placement="right"
                                            >
                                                Duplicar
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                title="Remover cupom"
                                                href="javascript:void(0)"
                                                class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100 btnFormDeleteCouponDiscount"
                                                data-url="{{ route('dashboard.coupon-discounts.destroy', $coupon) }}"
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
        'id' => 'drawerAddCoupons',
        'title' => 'Adicionar cupom',
        'custom' => 'max-w-2xl',
    ])
        <form method="POST" action="{{ route('dashboard.coupon-discounts.store') }}" id="formCouponDiscount">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}" />

            <div class="inputsCouponDiscount grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <div class="grid grid-cols-12 gap-4">

                        <div class="col-span-9">

                            <label for="code">Código do cupom</label>
                            <div class="append">

                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    min="3"
                                    maxlength="30"
                                    placeholder="EXEMPLO10"
                                    required
                                />

                                <div class="append-item-right w-fit">

                                    <div id="msgCopyToClipboard"></div>

                                    <button
                                        class="animate w-12 hover:text-primary"
                                        onclick="copyToClipboard()"
                                        type="button"
                                    >
                                        @include('components.icon', [
                                            'icon' => 'content_copy',
                                            'custom' => 'text-xl',
                                        ])
                                    </button>

                                </div>

                            </div>

                        </div>

                        <div class="col-span-3 self-end">
                            <button
                                class="button button-primary h-12 w-full rounded-lg"
                                onclick="generateCoupon()"
                                type="button"
                            >
                                Gerar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-span-12">
                    <label for="name">Nome</label>
                    <div class="append">
                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Nome para o cupom"
                            minlength="3"
                            maxlength="30"
                            required
                        />
                    </div>
                </div>

                <div class="col-span-12">
                    <label for="description">Descrição</label>
                    <textarea
                        rows="4"
                        id="description"
                        name="description"
                        placeholder="Digite uma descrição"
                    ></textarea>
                </div>

                <div class="col-span-12">
                    <label for="quantity">Total de cupons disponíveis</label>
                    <input
                        class="noScrollInput"
                        type="number"
                        id="quantity"
                        name="quantity"
                        placeholder="1"
                        min="1"
                        value=""
                        required
                    />
                </div>

                <div class="col-span-12">
                    <label for="minimum_price_order">Valor mínimo de compra</label>
                    <div class="append">
                        <input
                            type="text"
                            id="minimum_price_order"
                            name="minimum_price_order"
                            class="pl-12"
                            placeholder="0,00"
                            autocomplete="off"
                            value="0"
                            oninput="setCurrencyMask(this)"
                            required
                        />
                        <span class="append-item-left w-12">R$</span>
                    </div>
                </div>

                <div class="col-span-12">
                    <label class="label-input mb-6">Escolha uma opção</label>

                    <div class="grid grid-cols-12 gap-4">

                        <div class="col-span-6">
                            <label
                                class="mb-0 w-full cursor-pointer"
                                for="addPercentage"
                            >
                                <input
                                    type="radio"
                                    class="peer hidden"
                                    id="addPercentage"
                                    name="type"
                                    value="{{ \App\Enums\TypeDiscountEnum::PERCENTAGE->name }}"
                                    onchange="togglePercentValue()"
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

                        <div class="col-span-6">
                            <label
                                class="mb-0 w-full cursor-pointer"
                                for="addFixedValue"
                            >
                                <input
                                    type="radio"
                                    class="peer hidden"
                                    id="addFixedValue"
                                    name="type"
                                    value="{{ \App\Enums\TypeDiscountEnum::VALUE->name }}"
                                    onchange="toggleFixedValue()"
                                />
                                <div class="flex w-full items-center gap-2 rounded-lg border border-neutral-200 p-6 peer-checked:border-primary peer-checked:[&>span>i]:block peer-checked:[&>span]:border-primary peer-checked:[&>span]:bg-primary">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full border">
                                        @include('components.icon', [
                                            'icon' => 'check',
                                            'custom' => 'text-xl text-white hidden',
                                        ])
                                    </span>
                                    Valor fixo
                                </div>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="addPercentageContent col-span-12 hidden">
                    <label for="amountPERCENTAGE">Porcentagem</label>
                    <input
                        type="text"
                        id="amountPERCENTAGE"
                        name="amount"
                        placeholder="0"
                        autocomplete="off"
                        maxlength="5"
                        oninput="setPercentageMask(this)"
                    />
                </div>

                <div class="addFixedValueContent col-span-12 hidden">
                    <label for="amountVALUE">Valor</label>
                    <div class="append">
                        <input
                            type="text"
                            id="amountVALUE"
                            name="amount"
                            class="pl-12"
                            placeholder="0,00"
                            autocomplete="off"
                            oninput="setCurrencyMask(this)"
                        />
                        <span class="append-item-left w-12">R$</span>
                    </div>
                </div>

                <div class="col-span-6">
                    <label for="start_at">Válido a partir de</label>
                    <input
                        type="datetime-local"
                        id="start_at"
                        name="start_at"
                        value="{{ now()->format('Y-m-d\TH:i') }}"
                        required
                    />
                </div>

                <div class="col-span-6">
                    <label for="end_at">Válido até</label>
                    <input
                        type="datetime-local"
                        id="end_at"
                        name="end_at"
                        value="{{ now()->addMonth()->format('Y-m-d\TH:i') }}"
                        required
                    />
                </div>

                <div class="col-span-12">
                    <label for="offersId block">Válido somente para as ofertas</label>
                    <div>
                        <button id="dropdownCheckboxButtonOffers" data-dropdown-toggle="dropdownDefaultCheckbox" class="text-white focus:ring-4 w-full focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center button-primary dark:focus:ring-blue-800" type="button">
                            <span class="truncate flex-1 text-left">Todos</span>
                            <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownDefaultCheckbox" class="z-10 hidden w-[90%] bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownCheckboxButtonOffers">

                                @foreach($product->offers as $offer)
                                    <li>
                                        <div class="flex items-center">
                                            <input name="offers[]" id="checkbox-offer-{{$offer->id}}" type="checkbox" value="{{$offer->id}}" data-name="{{$offer->name}}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="checkbox-offer-{{$offer->id}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$offer->name}}</label>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-span-12">

                    <h4>Regras</h4>
                    <hr class="my-2">

                    <div class="mt-4 space-y-4">

                        <label
                            for="automatic_application"
                            class="flex cursor-pointer items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                id="automatic_application"
                                name="automatic_application"
                                value="1"
                            />
                            Aplicação automática no carrinho de compra
                        </label>

                        <label
                                for="allow_affiliate_links"
                                class="flex cursor-pointer items-center gap-2"
                        >
                            <input
                                    type="checkbox"
                                    id="allow_affiliate_links"
                                    name="allow_affiliate_links"
                                    value="1"
                            />
                            Permitir o uso em links de afiliados
                        </label>

                        <label
                            for="once_per_customer"
                            class="flex cursor-pointer items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                id="once_per_customer"
                                name="once_per_customer"
                                value="1"
                            />
                            Uso único, (1 vez) por cliente
                        </label>

                        <label
                            for="newsletter_abandoned_carts"
                            class="flex cursor-pointer items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                id="newsletter_abandoned_carts"
                                name="newsletter_abandoned_carts"
                                value="1"
                            />
                            Envio automático nos e-mails de carrinho abandonado
                        </label>

                        <label
                            for="only_first_order"
                            class="flex cursor-pointer items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                id="only_first_order"
                                name="only_first_order"
                                value="1"
                            />
                            Cupom de 1º compra
                        </label>

                    </div>

                </div>

                <div class="col-span-12">

                    <h4>Métodos de pagamento</h4>
                    <hr class="my-2">

                    <div class="mt-4 space-y-4">
                        @foreach (\App\Enums\PaymentMethodEnum::getDescriptions() as $paymentMethod)
                            <label
                                for="paymentMethodCoupon_{{ $paymentMethod['value'] }}"
                                class="flex cursor-pointer items-center gap-2"
                            >
                                <input
                                    type="checkbox"
                                    id="paymentMethodCoupon_{{ $paymentMethod['value'] }}"
                                    name="payment_methods[]"
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
                class="button button-primary mt-8 h-12 w-full gap-1 rounded-full"
                type="submit"
            >
                @include('components.icon', [
                    'icon' => 'add',
                    'custom' => 'text-xl',
                ])
                Salvar
            </button>
        </form>
    @endcomponent

    <!-- VIEWS -->
    @component('components.drawer', [
        'id' => 'drawerViewCoupons',
        'title' => 'Ver cupom',
        'custom' => 'max-w-2xl translate-x-0',
    ])
        <div class="grid grid-cols-12 gap-x-4 divide-y divide-neutral-100">
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Código do cupom</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[couponsDiscount][code]"
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
                            id="productView[couponsDiscount][name]"
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
                            id="productView[couponsDiscount][description]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Total de coupons disponíveis</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[couponsDiscount][quantity]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-medium">Valor mínimo de compra</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[couponsDiscount][minimum_price_order]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5
                        class="font-medium"
                        id="productView[couponsDiscount][textAmount]"
                    >
                    </h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[couponsDiscount][amount]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-6">
                <div class="space-y-1 py-4">
                    <h5 class="font-semibold">Válido a partir de</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[couponsDiscount][start_at]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-6">
                <div class="space-y-1 py-4">
                    <h5 class="font-semibold">Válido até</h5>
                    <div class="rounded-xl bg-neutral-100 p-4">
                        <p
                            class="!whitespace-normal"
                            id="productView[couponsDiscount][end_at]"
                        ></p>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-semibold">Regras</h5>
                    <div
                        class="rounded-xl bg-neutral-100 p-4"
                        id="coupon-rules"
                    >
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="space-y-1 py-4">
                    <h5 class="font-semibold">Métodos de pagamento</h5>
                    <div
                        class="rounded-xl bg-neutral-100 p-4"
                        id="productView[couponsDiscount][payment_methods]"
                    >
                    </div>
                </div>
            </div>
        </div>
    @endcomponent
@endpush

@push('custom-script')
    <script>
        // não permite espaços nos campos "code" e "name"
        $('input[name="code"]').keypress(function(e) {
            if (!/[0-9a-zA-Z-]/.test(String.fromCharCode(e.which)))
                return false;
        });

        function generateCoupon() {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            const inputElement = document.getElementById('code');

            let coupon = '';

            for (let i = 0; i < 8; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                coupon += characters[randomIndex];
            }

            if (inputElement) {
                inputElement.value = coupon;
            }
        }

        function copyToClipboard() {
            const inputElement = document.getElementById('code');
            const msgCopyToClipboard = document.getElementById('msgCopyToClipboard');

            if (inputElement) {
                console.log(inputElement);


                inputElement.select();
                document.execCommand('copy');

                msgCopyToClipboard.innerHTML = 'Copiado!';

                setTimeout(() => {
                    msgCopyToClipboard.innerHTML = '';
                }, 1500);

            }
        }

        function togglePercentValue() {
            $('.addPercentageContent').show();
            $('.addFixedValueContent').hide();

            $(`input[name="amount"]`).prop('required', false);
            $(`#amountPERCENTAGE`).prop('required', true);
            $(`#amountVALUE`).val('');
        }

        function toggleFixedValue() {
            $('.addFixedValueContent').show();
            $('.addPercentageContent').hide();

            $(`input[name="amount"]`).prop('required', false);
            $(`#amountVALUE`).prop('required', true);
            $(`#amountPERCENTAGE`).val('');
        }
    </script>

    <script>
        const keyInputCouponDiscount = "couponsDiscount";

        // Função para limpar os dados do drawer
        function clearDrawerData() {
            let drawer = $("#drawerAddCoupons");

            drawer.find("input[type='text'], input[type='number'], textarea").val('');
            drawer.find("input[type='checkbox'], input[type='radio']").prop('checked', false);
            drawer.find(`input[name="product[${keyInputCouponDiscount}][type]"][value="PERCENTAGE"]`).prop('checked', true);

            drawer.find(".addCouponDiscount").text("Adicionar cupom de desconto");
            drawer.find(".titleDrawer").text("Novo cupom de desconto");
        }

        $(document).on("click", ".addCouponDiscount", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer     = $(`#${nameDrawer}`);
            let url        = $(this).data('url');

            drawer.find("form").attr("action", url);
            drawer.find("form").find('input[name="_method"]').remove();
        });

        $(document).on("click", ".viewCouponDiscount", function() {
            const drawer = $("#drawerViewCoupons");
            const data = JSON.parse($(this).attr('data-coupon'));

            const setTextField = (field, value) => {
                drawer.find(`#productView\\[${keyInputCouponDiscount}\\]\\[${field}\\]`).text(value);
            };

            setTextField('code', data.code);
            setTextField('name', data.name);
            setTextField('description', data.description);
            setTextField('quantity', data.quantity);
            setTextField('amount', data.type === 'PERCENTAGE' ? data.amount + '%' : 'R$ ' + data.amount);
            setTextField('minimum_price_order', data.minimum_price_order);
            setTextField('start_at', formatDateTimeToPTBR(data.start_at));
            setTextField('end_at', formatDateTimeToPTBR(data.end_at));

            const paymentMethodsList = generateList(data.payment_methods, paymentMethodTemplate, 'space-y-1');
            drawer.find(`#productView\\[${keyInputCouponDiscount}\\]\\[payment_methods\\]`).html(paymentMethodsList);
            drawer.find(`#productView\\[${keyInputCouponDiscount}\\]\\[textAmount\\]`).html(data.type === 'PERCENTAGE' ? 'Porcentagem' : 'Valor fixo');

            const rulesCouponLabels = {
                "allow_affiliate_links": "Permitir o uso em links de afiliados",
                "automatic_application": "Aplicação automática no carrinho de compra",
                "only_first_order": "Uso único, (1 vez) por cliente",
                "newsletter_abandoned_carts": "Envio automático nos e-mails de carrinho abandonado",
                "once_per_customer": "Cupom de 1ª compra",
            };

            const rulesCouponTemplate = (label) => `
                <li class="flex items-center gap-3">
                    <div class="flex h-5 w-5 items-center justify-center rounded-sm bg-primary text-white">
                        @include('components.icon', ['icon' => 'check', 'custom' => 'text-xl'])
                    </div>
                    ${label}
                </li>
            `;

            const renderCouponRules = (rules) => {
                return `
                    <ul>
                        ${Object.entries(rules)
                            .filter(([_, isActive]) => isActive === 1) // Filtra as regras ativas
                            .map(([method]) => rulesCouponTemplate(rulesCouponLabels[method] || method)) // Mapeia e gera o template
                            .join('')}
                    </ul>
                `;
            };

            document.querySelector('#coupon-rules').innerHTML = renderCouponRules({
                "automatic_application": data.automatic_application,
                "allow_affiliate_links": data.allow_affiliate_links,
                "only_first_order": data.only_first_order,
                "newsletter_abandoned_carts": data.newsletter_abandoned_carts,
                "once_per_customer": data.once_per_customer
            });
        });

        $(document).on("click", ".editCouponDiscount", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataCouponDiscount = $(this).data("coupon");

            drawer.find("form").attr("action", `/dashboard/coupon-discounts/${dataCouponDiscount.id}`);
            drawer.find("form").append('<input type="hidden" name="_method" value="PUT">');

            drawer.find(".titleDrawer").text("Editar cupom de desconto");

            drawer.find(`#code`).val(dataCouponDiscount.code);
            drawer.find(`#name`).val(dataCouponDiscount.name);
            drawer.find(`#description`).val(dataCouponDiscount.description);
            drawer.find(`#quantity`).val(dataCouponDiscount.quantity);
            drawer.find(`input[name="type"][value="${dataCouponDiscount.type}"]`).prop('checked', true).trigger("change");
            const dataCouponDiscountMinimumPriceOrder = formatCurrencyBR(dataCouponDiscount.minimum_price_order)
            drawer.find(`#minimum_price_order`).val(dataCouponDiscountMinimumPriceOrder);
            drawer.find(`#start_at`).val(formatDateForInput(dataCouponDiscount.start_at));
            drawer.find(`#end_at`).val(formatDateForInput(dataCouponDiscount.end_at));
            drawer.find(`#automatic_application`).prop('checked', dataCouponDiscount.automatic_application);
            drawer.find(`#allow_affiliate_links`).prop('checked', dataCouponDiscount.allow_affiliate_links);
            drawer.find(`#once_per_customer`).prop('checked', dataCouponDiscount.once_per_customer);
            drawer.find(`#newsletter_abandoned_carts`).prop('checked', dataCouponDiscount.newsletter_abandoned_carts);
            drawer.find(`#only_first_order`).prop('checked', dataCouponDiscount.only_first_order);

            drawer.find(`input[name="offers[]"]`).prop('checked', false);
            drawer.find('#dropdownCheckboxButtonOffers > span').html('Todos')

            drawer.find(`input[name="offers[]"]`).on('change', function(e) {
                let elements = drawer.find('input[name="offers[]"]:checked');
                let checkedOfferNames = elements.map(function() {
                    return $(this).data('name');
                }).get();

                if (checkedOfferNames.length) {
                    drawer.find('#dropdownCheckboxButtonOffers > span').html(checkedOfferNames.join(', '))
                } else {
                    drawer.find('#dropdownCheckboxButtonOffers > span').html('Todos')
                }
            })

            let checkedOfferNames = []
            for (let offer of dataCouponDiscount.offers) {
                checkedOfferNames.push(offer.name)
                drawer.find(`#checkbox-offer-${offer.id}`).prop('checked', true);
            }

            if (checkedOfferNames.length) {
                drawer.find('#dropdownCheckboxButtonOffers > span').html(checkedOfferNames.join(', '))
            }

            drawer.find(`input[name="payment_methods[]"]`).prop('checked', false);
            for (let paymentMethod of dataCouponDiscount.payment_methods) {
                drawer.find(`#paymentMethodCoupon_${paymentMethod}`).prop('checked', true);
            }

            const selectedDiscountType = document.querySelector('input[name="type"]:checked').value;

            if (selectedDiscountType === 'PERCENTAGE') {
                drawer.find(`#amountPERCENTAGE`).val(dataCouponDiscount.amount);
            }

            if (selectedDiscountType === 'VALUE') {
                const dataCouponDiscountAmount = formatCurrencyBR(dataCouponDiscount.amount)
                drawer.find(`#amountVALUE`).val(dataCouponDiscountAmount);
            }

            drawer.find(".addCouponDiscount").text("Atualizar");
        });

        $(document).on("click", ".duplicateCouponDiscount", function() {
            let nameDrawer = $(this).data("drawerTarget");
            let drawer = $(`#${nameDrawer}`);
            let dataCouponDiscount = $(this).data("coupon");

            drawer.find(`#name`).val(dataCouponDiscount.name);
            drawer.find(`#description`).val(dataCouponDiscount.description);
            drawer.find(`#quantity`).val(dataCouponDiscount.quantity);
            drawer.find(`#type`).val(dataCouponDiscount.type);

            const dataCouponDiscountMinimumPriceOrder = formatCurrencyBR(dataCouponDiscount.minimum_price_order)
            drawer.find(`#minimum_price_order`).val(dataCouponDiscountMinimumPriceOrder);

            drawer.find(`input[name="type"][value="${dataCouponDiscount.type}"]`).prop('checked', true).trigger("change");

            const selectedDiscountType = document.querySelector('input[name="type"]:checked').value;

            if (selectedDiscountType === 'PERCENTAGE') {
                drawer.find(`#amountPERCENTAGE`).val(dataCouponDiscount.amount);
            }

            if (selectedDiscountType === 'VALUE') {
                const dataCouponDiscountAmount = formatCurrencyBR(dataCouponDiscount.amount)
                drawer.find(`#amountVALUE`).val(dataCouponDiscountAmount);
            }

            drawer.find(`#start_at`).val(formatDateForInput(dataCouponDiscount.start_at));
            drawer.find(`#end_at`).val(formatDateForInput(dataCouponDiscount.end_at));

            drawer.find(`#automatic_application`).prop('checked', dataCouponDiscount.automatic_application);
            drawer.find(`#allow_affiliate_links`).prop('checked', dataCouponDiscount.allow_affiliate_links);
            drawer.find(`#once_per_customer`).prop('checked', dataCouponDiscount.once_per_customer);
            drawer.find(`#newsletter_abandoned_carts`).prop('checked', dataCouponDiscount.newsletter_abandoned_carts);
            drawer.find(`#only_first_order`).prop('checked', dataCouponDiscount.only_first_order);

            drawer.find(`input[name="payment_methods[]"]`).prop('checked', false);
            for (let paymentMethod of dataCouponDiscount.payment_methods) {
                drawer.find(`#paymentMethodCoupon_${paymentMethod}`).prop('checked', true);
            }
        });

        $(document).on("click", ".closeButton, [drawer-backdrop]", function() {
            clearDrawerData();
        });
    </script>

    <script>
        let maxPriceOffers = {{ $product->maxPriceOffers }};

        $(document).on("blur", "#amountVALUE", function() {
            let value = parseFloat($(this).val().replace(/\./g, '').replace(',', '.'));

            if (value > maxPriceOffers) {
                notyf.info(`O valor do cupom não pode ser maior que o valor máximo de ofertas (${maxPriceOffers.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })})`);
                $(this).val('').focus();
            }
        });
    </script>

    <script>
        $(document).on("submit", "#formCouponDiscount", function(event) {
            event.preventDefault();

            let form = $(this);
            let url  = form.attr("action");
            let data = form.serialize();

            data = data.replace(/&amount=(?=&|$)/g, '');

            let dataPaymentMethods = $(".divPaymentMethods :input").filter(function() {
                return $.trim($(this).val()).length > 0;
            }).serialize();

            data += '&' + dataPaymentMethods;

            $.ajax({
                url: url,
                type: "POST",
                data: data,
                beforeSend: function() {
                    form.find('button[type="submit"]')
                        .addClass('cursor-wait')
                        .text('Aguarde...')
                        .attr('disabled', true);
                },
                success: function (response) {
                    notyf.success(response.message);

                    window.location.href = window.location.pathname + '#tab=tab-config';
                    window.location.reload();
                },
                error: function (response) {
                    let errors = response.responseJSON.errors;

                    for(let key in errors) {
                        let input = form.find('input[name="' + key + '"]');

                        input.addClass('!border-danger-500');

                        if (key === 'code') {
                            input.parent().parent().parent().parent().find('.invalid-feedback').remove();
                            input.parent().parent().parent().parent().append('<div class="error-msg">' + errors[key][0] + '</div>');
                        } else {
                            input.parent().find('.invalid-feedback').remove();
                            input.parent().append('<div class="error-msg">' + errors[key][0] + '</div>');
                        }
                    }
                },
                complete: function() {
                    form.find('button[type="submit"]')
                        .text('Salvar')
                        .attr('disabled', false);
                }
            })
        });
    </script>

    <script>
        $(document).on("click", ".btnFormDeleteCouponDiscount", function(event) {
            event.preventDefault();

            if(!confirm("Tem certeza?")) {
                return;
            }

            let url = $(this).data('url');

            $.ajax({
                url: url,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    _method: 'DELETE',
                },
                success: function (response) {
                    notyf.success(response.message);

                    window.location.href = window.location.pathname + '#tab=tab-config';

                    window.location.reload();
                },
                error: function (response) {
                    notyf.error(response.responseJSON.message);
                },
            })
        });
    </script>

    <script>
        $(document).on("change", "input#couponsDiscount", function() {
            $("input[name='checkout[settings][allowCouponsDiscounts]']")
                .val($(this).prop('checked') ? 1 : 0);
        });
    </script>
@endpush
