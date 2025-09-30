<div class="mb-6 space-y-1 rounded-xl bg-neutral-100 p-4 md:p-6">
    <h4 class="mb-2">Instruções</h4>
    <ol class="ml-6 list-decimal">
        <li class="text-sm">Acesse sua conta no Reportana</li>
        <li class="text-sm">Clique em configurações, localizado no canto inferior esquerdo.</li>
        <li class="text-sm">Clique em Integrações e procure por chaves de API</li>
        <li class="text-sm">Copie e cole seu Client ID e Client Secret nos campos abaixo.</li>
        <li class="text-sm">Clique em salvar e os pedidos serão enviados para Reportana.</li>
    </ol>
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
                'id' => 'toggleReportanaActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                'name' => 'status',
                'value' => $appsShopUser['reportana']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => $appsShopUser['reportana']['status'] === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>
        <div class="col-span-12">
            <label for="client_id">Client ID <span class="required">*</span></label>
            <input
                type="text"
                id="client_id"
                name="client_id"
                value="{{ $appsShopUser['reportana']['dataShopUser']['client_id'] ?? '' }}"
                placeholder="Digite seu id"
                required
            />
        </div>
        <div class="col-span-12">
            <label for="client_secret">Client Secret <span class="required">*</span></label>
            <input
                type="text"
                id="client_secret"
                name="client_secret"
                value="{{ $appsShopUser['reportana']['dataShopUser']['client_secret'] ?? '' }}"
                placeholder="Digite sua chave"
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