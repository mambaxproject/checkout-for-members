
<form
        action=""
        method="POST"
>
    @csrf
    @method('PUT')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleUtmifyActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                'name' => 'status',
                'value' => $appsShopUser['utmify']['dataShopUser']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => ($appsShopUser['utmify']['dataShopUser']['status'] ?? \App\Enums\StatusEnum::INACTIVE->name) === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>
        <div class="col-span-12">
            <label>Credencial de API <span class="required">*</span></label>
            <input
                    type="text"
                    name="api_token"
                    value="{{ $appsShopUser['utmify']['dataShopUser']['api_token'] ?? '' }}"
                    required
                    placeholder="Digite sua credencial"
            >
        </div>
    </div>

    <button
            class="button button-primary mt-3 h-12 w-full rounded-full"
            type="submit"
    >
        Salvar
    </button>
</form>