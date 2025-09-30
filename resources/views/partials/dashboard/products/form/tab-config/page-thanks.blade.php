@component('components.toggle', [
    'id' => 'pageThanks',
    'label' => 'Página de Obrigado',
    'isChecked' => $product->hasCustomThanksPage(),
])
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <label
                for="product[attributes][linkThanksForOrderInPIX]"
                class="label-input"
            >
                Compra aprovada PIX
            </label>
            <div class="append">
                <input
                    type="url"
                    class="pl-12"
                    name="product[attributes][linkThanksForOrderInPIX]"
                    id="product[attributes][linkThanksForOrderInPIX]"
                    value="{{ old('product.attributes.linkThanksForOrderInPIX', $product->attributes->linkThanksForOrderInPIX ?? '') }}"
                    placeholder="Link"
                >
                <span class="append-item-left w-12">
                    @include('components.icon', [
                        'icon' => 'link',
                        'custom' => 'text-xl',
                    ])
                </span>
            </div>
        </div>
        <div class="col-span-12">
            <label
                for="product[attributes][linkThanksForOrderInCREDIT_CARD]"
                class="label-input"
            >
                Compra aprovada Cartão de crédito
            </label>
            <div class="append">
                <input
                    type="url"
                    class="pl-12"
                    name="product[attributes][linkThanksForOrderInCREDIT_CARD]"
                    id="product[attributes][linkThanksForOrderInCREDIT_CARD]"
                    value="{{ old('product.attributes.linkThanksForOrderInCREDIT_CARD', $product->attributes->linkThanksForOrderInCREDIT_CARD ?? '') }}"
                    placeholder="Link"
                >
                <span class="append-item-left w-12">
                    @include('components.icon', [
                        'icon' => 'link',
                        'custom' => 'text-xl',
                    ])
                </span>
            </div>
        </div>
        <div class="col-span-12">
            <label
                for="product[attributes][linkThanksForOrderInBILLET]"
                class="label-input"
            >
                Compra aprovada Boleto
            </label>
            <div class="append">
                <input
                    type="url"
                    class="pl-12"
                    name="product[attributes][linkThanksForOrderInBILLET]"
                    id="product[attributes][linkThanksForOrderInBILLET]"
                    value="{{ old('product.attributes.linkThanksForOrderInBILLET', $product->attributes->linkThanksForOrderInBILLET ?? '') }}"
                    placeholder="Link"
                >
                <span class="append-item-left w-12">
                    @include('components.icon', [
                        'icon' => 'link',
                        'custom' => 'text-xl',
                    ])
                </span>
            </div>
        </div>
    </div>
@endcomponent

@push('floating')
@endpush
