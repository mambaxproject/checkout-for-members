@component('components.drawer', [
    'id' => 'drawerAddAppApi',
    'title' => 'Criar token API',
    'custom' => 'max-w-xl',
])
    <form
            action="{{ route('dashboard.api.store') }}"
            method="POST"
    >
        @csrf
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <label>Nome</label>
                <input
                        name="name"
                        type="text"
                        placeholder="Digite seu domínio"
                        required
                >
            </div>
        </div>

        <button
                class="button button-primary mt-8 h-12 w-full rounded-full"
                type="submit"
        >
            Adicionar
        </button>
    </form>
@endcomponent