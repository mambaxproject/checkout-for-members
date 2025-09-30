 <div class="mb-6 space-y-1 rounded-xl bg-neutral-100 p-4 md:p-6">
    <h4 class="mb-2">Atenção</h4>
    <p class="text-sm">As tags são enviadas dinamicamente com o nome dos produtos e o contexto do funil. Exemplo: carrinho_abandonado - Produto X.</p>
    <p class="text-sm">Os nome dos campos que serão enviados para o Active Compaign são: LINKCHECKOUT e LINK PAGAMENTO.</p>
    <p class="text-sm">Certifique-se de que esses campos existem na sua conta do Active Compaign.</p>
</div>

<form
        action=""
        method="POST"
>
    @csrf
    @method('PUT')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleActiveCampaignActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                'name' => 'status',
                'value' => $appsShopUser['active-campaign']['dataShopUser']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => ($appsShopUser['active-campaign']['dataShopUser']['status'] ?? '') === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>
        <div class="col-span-12">
            <label>Nome <span class="required">*</span></label>
            <input
                    name="name"
                    value="{{ $appsShopUser['active-campaign']['dataShopUser']['name'] ?? '' }}"
                    type="text"
                    required
            >
        </div>
        <div class="col-span-12">
            <label>API URL <span class="required">*</span></label>
            <input
                    name="api_url"
                    value="{{ $appsShopUser['active-campaign']['dataShopUser']['api_url'] ?? '' }}"
                    type="url"
                    required
            >
        </div>
        <div class="col-span-12">
            <label>API Key <span class="required">*</span></label>
            <input
                    name="api_key"
                    value="{{ $appsShopUser['active-campaign']['dataShopUser']['api_key'] ?? '' }}"
                    type="text"
                    required
            >
        </div>
    </div>

    <button
            class="button button-primary mt-8 h-12 w-full rounded-full"
            type="submit"
    >
        Salvar
    </button>
</form>