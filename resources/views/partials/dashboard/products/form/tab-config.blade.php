<div
    class="tab-content hidden"
    id="tab-config"
    data-tab="tab-config"
>

    <form
        action="{{ route('dashboard.products.update', $product) }}"
        method="POST"
        class="formDataProduct"
    >

        <input
            type="hidden"
            name="tab"
            value="config"
        />

        <div class="space-y-4 md:space-y-10">

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-8">

                    <h3>Métodos de Pagamentos</h3>

                    <div class="divPaymentMethods space-y-6">
                        @if (user()->shop()->hasCreditCardPaymentEnabled)
                            @component(
                                'components.toggle',
                                array_merge(
                                    [
                                        'id' => 'toggleShowCreditCard',
                                        'label' => 'Cartão de crédito',
                                        'name' => 'product[attributes][paymentMethods][]',
                                        'value' => \App\Enums\PaymentMethodEnum::CREDIT_CARD->name,
                                        'isChecked' => $product->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name),
                                    ],
                                    $product['paymentType'] !== 'UNIQUE' ? ['contentEmpty' => true] : []))
                                <div
                                    class="grid grid-cols-12 gap-6"
                                    id="checkPaymentType_RECURRING"
                                >
                                    <div class="col-span-12">
                                        <label for="product[attributes][maxInstallments]">Número máximo de parcelas</label>
                                        <select
                                            name="product[attributes][maxInstallments]"
                                            id="product[attributes][maxInstallments]"
                                        >
                                            @foreach (range(1, 12) as $numberInstallment)
                                                <option
                                                    value="{{ $numberInstallment }}"
                                                    @selected(old('product.attributes.maxInstallments', $product->attributes->maxInstallments ?? 12) == $numberInstallment)
                                                >
                                                    {{ $numberInstallment }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endcomponent
                        @endif

                        @if ($product['paymentType'] === 'UNIQUE')
                            @component('components.toggle', [
                                'id' => 'toggleShowBoleto',
                                'label' => 'Boleto',
                                'name' => 'product[attributes][paymentMethods][]',
                                'value' => \App\Enums\PaymentMethodEnum::BILLET->name,
                                'isChecked' => $product->hasPaymentMethod(\App\Enums\PaymentMethodEnum::BILLET->name),
                            ])
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12">
                                        <label>Validade do boleto (em dias úteis)</label>
                                        <input
                                            class="noScrollInput"
                                            type="number"
                                            name="product[attributes][daysDueDateBillet]"
                                            id="product[attributes][daysDueDateBillet]"
                                            placeholder="Recomendado: 3 dias"
                                            min="0"
                                            max="9"
                                            value="{{ old('product.attributes.daysDueDateBillet', $product->attributes->daysDueDateBillet ?? 3) }}"
                                            onkeyup="limitCharger(this)"
                                        >
                                    </div>
                                </div>
                            @endcomponent

                            @component('components.toggle', [
                                'id' => 'toggleShowPix',
                                'label' => 'Pix',
                                'name' => 'product[attributes][paymentMethods][]',
                                'value' => \App\Enums\PaymentMethodEnum::PIX->name,
                                'isChecked' => $product->hasPaymentMethod(\App\Enums\PaymentMethodEnum::PIX->name),
                                'contentEmpty' => true,
                            ])
                            @endcomponent
                        @endif

                    </div>

                </div>
            @endcomponent

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-8">

                    <h3>Configurações</h3>

                    <div class="space-y-6">

                        @include('partials.dashboard.products.form.tab-config.coupons-discount')
                        @include('partials.dashboard.products.form.tab-config.order-bump')
                        @include('partials.dashboard.products.form.tab-config.page-thanks')
                        @include('partials.dashboard.products.form.tab-config.up-sell')
                        @include('partials.dashboard.products.form.tab-config.pixel')
                        @component('components.toggle', [
                            'id' => 'telegram-invite-link',
                            'label' => 'Redirecionar para link do telegram',
                            'name' => 'product[attributes][redirectToTelegramLink]',
                            'value' => 1,
                            'isChecked' => $product->getValueSchemalessAttributes('redirectToTelegramLink') ?? false,
                        ])
                        @endcomponent
                    </div>

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

@push('custom-script')
    <script src="{{ asset('js/dashboard/validation/currency.js') }}"></script>
    <script src="{{ asset('js/dashboard/validation/pattern.js') }}"></script>

    <script>
        function formatDateTimeToPTBR(dateString) {
            const date = new Date(dateString);

            const formattedDate = date.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
            });
            const formattedTime = date.toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            return `${formattedDate} ${formattedTime}`;
        }

        function generateList(items, itemTemplate, listClass = '') {
            const ulClass = listClass ? ` class="${listClass}"` : '';

            return `
                <ul${ulClass}>
                    ${items.map(itemTemplate).join('')}
                </ul>
            `;
        }

        const paymentMethodLabels = {
            "CREDIT_CARD": "Cartão de Crédito",
            "BILLET": "Boleto",
            "PIX": "Pix"
        };

        const paymentMethodTemplate = (method) => {
            const label = paymentMethodLabels[method] || method;

            return `
                <li class="flex items-center gap-3">
                    <div class="flex h-5 w-5 items-center justify-center rounded-sm bg-primary text-white">
                        @include('components.icon', ['icon' => 'check', 'custom' => 'text-xl'])
                    </div>
                    ${label}
                </li>
            `;
        };
    </script>
@endpush
