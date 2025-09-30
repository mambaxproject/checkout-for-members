<form
    action=""
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleBotConversaActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                    'name' => 'status',
                'value' => $appsShopUser['botconversa']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => $appsShopUser['botconversa']['status'] === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>

        <div class="col-span-12">
            <label for="apikey">Api key<span class="required">*</span></label>
            <input
                    type="text"
                    id="apikey"
                    name="apikey"
                    placeholder="digite a api key"
                    value="{{ $appsShopUser['botconversa']['dataShopUser']['apikey'] ?? '' }}"
                    required
            />
        </div>
    </div>

    <button
        class="button button-primary mt-8 h-12 w-full rounded-full"
        type="submit"
    >
        Salvar
    </button>
</form>