<form
    action="{{ route('dashboard.apps.update', 4) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleMemberkitActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                'name' => 'status',
                'value' => $appsShopUser['member-kit']['dataShopUser']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => ($appsShopUser['member-kit']['dataShopUser']['status'] ?? '') === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>

        <div class="col-span-12">
            <label>Nome <span class="required">*</span></label>
            <input
                type="text"
                name="name"
                value="{{ $appsShopUser['member-kit']['dataShopUser']['name'] ?? '' }}"
                placeholder="Digite um nome"
                required
            >
        </div>

        <div class="col-span-12">
            <label>Chave secreta <span class="required">*</span></label>
            <input
                type="text"
                name="secret_key"
                value="{{ $appsShopUser['member-kit']['dataShopUser']['secret_key'] ?? '' }}"
                placeholder="Digite a chave secreta da conta"
                required
            >
        </div>

        <div class="col-span-12">
            <label>ID da turma <span class="required">*</span></label>
            <input
                type="text"
                name="class_id"
                value="{{ $appsShopUser['member-kit']['dataShopUser']['class_id'] ?? '' }}"
                required
                placeholder="Digite o ID da turma"
            />

            <div class="mt-2 space-y-1">
                <p class="text-xs text-neutral-400">Preencha este campo com o ID da turma que deseja que o aluno seja matriculado.</p>
                <p class="text-xs text-neutral-400">Caso não preencha, o aluno será matriculado como assinatura com acesso a todas as turmas.</p>
            </div>
        </div>

        <div class="col-span-12">
            <label>Produto</label>
            <select name="product_id[]" id="product_id" required>
                <option value="">Selecione um produto</option>
                @foreach ($productsShop as $product)
                    <option value="{{ $product->id }}"
                        @selected($appsShopUser['member-kit']?->appShop?->products->contains($product->id))
                    >
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <button
        class="button button-primary mt-8 h-12 w-full rounded-full"
        type="submit"
    >
        Salvar
    </button>
</form>