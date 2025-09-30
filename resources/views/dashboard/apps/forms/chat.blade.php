<form
    action=""
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleChatActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                'name' => 'status',
                'value' => $appsShopUser['chat']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => $appsShopUser['chat']['status'] === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>

        <div class="col-span-12">
            <label for="script_html_chat">Script HTML do chat <span class="required">*</span></label>
            <textarea
                    id="script_html_chat"
                    name="script_html_chat"
                    required
                    rows="8"
            >{{ $appsShopUser['chat']['dataShopUser']['script_html_chat'] ?? '' }}</textarea>
        </div>
    </div>

    <button
        class="button button-primary mt-8 h-12 w-full rounded-full"
        type="submit"
    >
        Salvar
    </button>
</form>