@component('components.drawer', [
    'id' => 'drawerAddAppWebhook',
    'title' => 'Salvar Webhook',
    'custom' => 'max-w-xl',
])
    <form
        action="{{ route('dashboard.webhooks.store') }}"
        id="formSaveWebhook"
        method="POST"
    >
        @csrf
        <div class="grid grid-cols-12 gap-6">

            <h4 class="col-span-12">Informações do webhook</h4>

            <div class="col-span-12">
                <label for="name">Nome</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Digite o nome"
                    required
                />
            </div>
            <div class="col-span-12">
                <label for="url">URL</label>
                <input
                    type="url"
                    id="url"
                    name="url"
                    placeholder="Digite a URL"
                    required
                />
            </div>

            <h4 class="col-span-12">
                <hr class="mb-4" />
                Eventos
            </h4>

            <div class="col-span-12">

                <div class="space-y-4">

                    @foreach (\App\Models\WebhookEvent::toBase()->get(['id', 'name']) as $index => $event)
                        @include('components.toggle', [
                            'id' => 'toggleAppEventsId' . $index,
                            'label' => $event->name,
                            'contentEmpty' => true,
                            'value' => $event->id,
                            'name' => 'event_id[]',
                        ])
                    @endforeach

                </div>

            </div>

            <div class="col-span-12">
                <label>Produto</label>
                <select name="product_id[]" id="product_id">
                    <option value="">Todos</option>
                    @foreach ($productsShop as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-12">
                <div class="alert alert-light p-6">
                    <p class="text-sm">
                        Ao ativar o Webhook, sempre que houver alterações de pagamento em alguma venda, será feito um POST para a URL que você informar abaixo, contendo o evento e os dados do pagamento envolvido.
                    </p>
                </div>
            </div>

        </div>

        <button
            class="button button-primary mt-8 h-12 w-full rounded-full"
            type="submit"
        >
            Salvar
        </button>
    </form>
@endcomponent