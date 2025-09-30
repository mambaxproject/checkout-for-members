<form
    action=""
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id'           => 'toggleGoogleTagManagerActive',
                'label'        => 'Ativar',
                'contentEmpty' => false,
                'name'         => 'status',
                'value' => $appsShopUser['google-tag-manager']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => $appsShopUser['google-tag-manager']['status'] === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>

        <div class="col-span-12">
            <label for="code_gtm">ID de rastreamento <span class="required">*</span></label>
            <input
                type="text"
                id="code_gtm"
                name="code_gtm"
                placeholder="digite o código do google tag manager"
                value="{{ $appsShopUser['google-tag-manager']['dataShopUser']['code_gtm'] ?? '' }}"
                required
            />
            <p class="mt-3 text-xs leading-6 text-gray-600 italic">
                Para obter o código de rastreamento, acesse o Google Tag Manager e copie o código de rastreamento.
                Ex: GTM-XXXXXXX
            </p>
        </div>
    </div>

    <button
        class="button button-primary mt-8 h-12 w-full rounded-full"
        type="submit"
    >
        Salvar
    </button>
</form>