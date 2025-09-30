
<form
        action=""
        method="POST"
>
    @csrf
    @method('PUT')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleWooCommerceActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                'name' => 'status',
                'value' => $appsShopUser['woocommerce']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => $appsShopUser['woocommerce']['status'] === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleWooCommerceCart',
                'label' => 'Pular carrinho de compras',
                'contentEmpty' => false,
                'name' => 'skip_cart',
                'value' => 1,
                'isChecked' => $appsShopUser['woocommerce']['dataShopUser']['skip_cart'] ?? false,
            ])
            @endcomponent
        </div>
        <div class="col-span-12">
            <label>URL da loja <span class="required">*</span></label>
            <input
                    type="url"
                    name="store_url"
                    value="{{ $appsShopUser['woocommerce']['dataShopUser']['store_url'] ?? '' }}"
                    required
                    placeholder="Digite seu domínio"
            >
        </div>
        <div class="col-span-12">
            <label>Consumer_key <span class="required">*</span></label>
            <input
                    type="text"
                    name="consumer_key"
                    value="{{ $appsShopUser['woocommerce']['dataShopUser']['consumer_key'] ?? '' }}"
                    required
                    placeholder="Digite seu chave"
            >
        </div>
        <div class="col-span-12">
            <label>Consumer_secret <span class="required">*</span></label>
            <input
                    type="text"
                    name="consumer_secret"
                    value="{{ $appsShopUser['woocommerce']['dataShopUser']['consumer_secret'] ?? '' }}"
                    required
                    placeholder="Digite seu segredo"
            >
        </div>
    </div>

    <button
            class="button button-light mt-8 h-12 w-full gap-1 rounded-full"
            type="submit"
    >
        @include('components.icon', [
            'icon' => 'download',
            'custom' => 'text-xl',
        ])
        Baixar plugin
    </button>

    <button
            class="button button-primary mt-3 h-12 w-full rounded-full"
            type="submit"
    >
        Salvar
    </button>
</form>