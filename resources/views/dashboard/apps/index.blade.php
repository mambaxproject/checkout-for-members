@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Aplicações</h1>

        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12">
                @component('components.card', ['custom' => 'p-4 md:p-6 lg:p-8'])
                    <div class="space-y-4 md:space-y-6">

                        <h3>API</h3>

                        @component('components.toggle', [
                            'id' => 'toggleAppApiActive',
                            'label' => 'Habilitar',
                            'isChecked' => $tokensUserShop->count(),
                        ])
                            <button
                                class="button button-light mb-4 h-10 w-full rounded-full"
                                data-drawer-target="drawerAddAppApi"
                                data-drawer-show="drawerAddAppApi"
                                data-drawer-placement="right"
                                type="button"
                            >
                                @include('components.icon', [
                                    'icon' => 'add',
                                    'custom' => 'text-xl  ',
                                ])
                                Adicionar
                            </button>

                            <div class="overflow-x-scroll md:overflow-visible">
                                <table class="table w-full">
                                    <thead>
                                        <tr>
                                            <th class="w-20">ID</th>
                                            <th class="w-1/2">Nome</th>
                                            <th>Usado última vez</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tokensUserShop as $token)
                                            <tr>
                                                <td>{{ $token->id }}</td>
                                                <td>{{ $token->name }}</td>
                                                <td>{{ $token->last_used_at ?? 'N/S' }}</td>
                                                <td class="text-end">

                                                    @component('components.dropdown-button', [
                                                        'id' => 'dropdownMoreTableAppApi' . $token->id,
                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                        'custom' => 'text-xl',
                                                    ])
                                                        <ul>
                                                            <li>
                                                                <form
                                                                    action="{{ route('dashboard.api.destroy', $token->id) }}"
                                                                    method="POST"
                                                                >
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button
                                                                        class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                        type="submit"
                                                                        onclick="return confirm('Tem certeza?')"
                                                                    >
                                                                        Remover
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    @endcomponent

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent

                    </div>
                @endcomponent
            </div>

            <div class="col-span-12">
                @component('components.card', ['custom' => 'p-4 md:p-6 lg:p-8'])
                    <div class="space-y-4 md:space-y-6">

                        <h3>Webhook</h3>

                        @component('components.toggle', [
                            'id' => 'toggleAppWebhookActive',
                            'label' => 'Habilitar',
                            'isChecked' => $webHooksShopUser->count(),
                        ])
                            <button
                                class="button button-light mb-4 h-10 w-full rounded-full storeWebHook"
                                data-drawer-target="drawerAddAppWebhook"
                                data-drawer-show="drawerAddAppWebhook"
                                data-drawer-placement="right"
                                type="button"
                            >
                                @include('components.icon', [
                                    'icon' => 'add',
                                    'custom' => 'text-xl  ',
                                ])
                                Adicionar
                            </button>

                            <div class="overflow-x-scroll md:overflow-visible">
                                <table class="table w-full">
                                    <thead>
                                        <tr>
                                            <th class="w-1/3">Nome</th>
                                            <th class="w-1/3">URL</th>
                                            <th class="w-1/5">Produto(s)</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($webHooksShopUser as $webhook)
                                            <tr>
                                                <td>{{ $webhook->name }}</td>
                                                <td>{{ $webhook->url }}</td>
                                                <td>{{ $webhook->products->implode('name') }}</td>
                                                <td>
                                                    <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                                        @include('components.icon', [
                                                            'icon' => 'circle',
                                                            'type' => 'fill',
                                                            'custom' => 'text-xs ' . \App\Enums\StatusEnum::getClassText($webhook->status),
                                                        ])
                                                        {{ $webhook->statusFormatted }}
                                                    </div>
                                                </td>
                                                <td class="text-end">

                                                    @component('components.dropdown-button', [
                                                        'id' => 'dropdownMoreTableAppWebhook' . $webhook->id,
                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                        'custom' => 'text-xl',
                                                    ])
                                                        <ul>
                                                            <li>
                                                                <button
                                                                    class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100 editWebHook"
                                                                    data-webhook="{{ $webhook }}"
                                                                    data-actionFormEdit="{{ route('dashboard.webhooks.update', $webhook) }}"
                                                                    data-actionFormStore="{{ route('dashboard.webhooks.store') }}"
                                                                    data-drawer-target="drawerAddAppWebhook"
                                                                    data-drawer-show="drawerAddAppWebhook"
                                                                    data-drawer-placement="right"
                                                                    type="button"
                                                                >
                                                                    Editar
                                                                </button>
                                                            </li>

                                                            <li>
                                                                <form
                                                                    action="{{ route('dashboard.webhooks.destroy', $webhook->id) }}"
                                                                    method="post"
                                                                >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button
                                                                        class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                        type="submit"
                                                                        onclick="return confirm('Tem certeza?')"
                                                                    >
                                                                        Remover
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    @endcomponent

                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endcomponent

                    </div>
                @endcomponent
            </div>

            @foreach ($appsShopUser as $index => $app)
                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    @component('components.card')
                        <button
                            class="animate flex h-full min-h-[160px] w-full items-center justify-center rounded-xl p-4 hover:ring-4 hover:ring-neutral-200 lg:p-8 editAppShopUser"
                            data-drawer-target="drawerFormApp"
                            data-drawer-show="drawerFormApp"
                            data-contentFormApp="{{ view('dashboard.apps.forms.' . $app->slug, compact('appsShopUser', 'productsShop'))->render() }}"
                            data-appShopUser="{{ $app }}"
                            data-actionFormEdit="{{ route('dashboard.apps.update', $app) }}"
                            data-drawer-placement="right"
                            type="button"
                        >
                            <img
                                class="lg:max-w-auto max-h-14 max-w-60"
                                src="{{ asset($app->icon_url) }}"
                                alt="{{ $app->name }}"
                                loading="lazy"
                            />
                        </button>
                    @endcomponent
                </div>
            @endforeach

        </div>

    </div>

    @if (session('token'))
        @component('components.modal', [
            'id' => 'showToken',
            'title' => 'Api Token',
        ])
            <div class="space-y-4">

                <p>
                    Esta é a única vez que será mostrada, então não a perca!
                    Agora você pode usar esse token para fazer solicitações de API.
                </p>

                <code class="flex items-center justify-between rounded-md bg-black px-4 py-2 text-white">

                    <p>{{ session('token') }}</p>

                    <input
                        id="tokenInput"
                        value="{{ session('token') }}"
                        type="hidden"
                    />

                    <div class="flex items-center gap-2">
                        <span
                            id="copyMessage"
                            class="hidden text-xs text-primary"
                        >
                            Copiado!
                        </span>

                        <button
                            class="animate hover:text-primary"
                            onclick="copyToClipboard()"
                            type="button"
                        >
                            @include('components.icon', [
                                'icon' => 'content_copy',
                                'custom' => 'text-xl',
                            ])
                        </button>
                    </div>

                </code>

            </div>
        @endcomponent

        <script>
            window.onload = function() {
                const targetEl = document.getElementById('showToken');
                const modal = new Modal(targetEl);
                modal.toggle();

                $('[data-modal-hide="showToken"]').on('click', function() {
                    modal.toggle()
                });
            };

            function copyToClipboard() {
                const input = document.getElementById('tokenInput');
                const tempInput = document.createElement('input');

                tempInput.value = input.value;
                document.body.appendChild(tempInput);

                tempInput.select();
                document.execCommand('copy');

                document.body.removeChild(tempInput);

                const copyMessage = document.getElementById('copyMessage');
                copyMessage.classList.remove('hidden');

                setTimeout(() => {
                    copyMessage.classList.add('hidden');
                }, 2000);
            }
        </script>
    @endif
@endsection

@push('floating')
    @include('dashboard.apps.forms.api.api')

    @include('dashboard.apps.forms.webhooks.webhooks')

    @include('dashboard.apps.forms.drawerFormApp')
@endpush

@section('script')
    <script>
        $(document).on('click', '.storeWebHook', function() {
            const form         = $('#formSaveWebhook');
            let actionFormEdit = $(this).data('actionformestore');

            form.attr('action', actionFormEdit);
            form.find('input[name="name"]').val('');
            form.find('input[name="url"]').val('');

            form.find('input[name="event_id[]"]').each(function() {
                $(this).prop('checked', 0);
            });
        });

        $(document).on('click', '.editWebHook', function() {
            const dataWebhook  = $(this).data('webhook');
            const form         = $('#formSaveWebhook');
            let actionFormEdit = $(this).data('actionformedit');

            form.append('<input type="hidden" name="_method" value="PUT">');

            form.attr('action', actionFormEdit);
            form.find('input[name="name"]').val(dataWebhook.name);
            form.find('input[name="url"]').val(dataWebhook.url);

            let events = dataWebhook.events.map(event => event.id);

            form.find('input[name="event_id[]"]').each(function() {
                $(this).prop('checked', events.includes(parseInt($(this).val())));
            });

            let products = dataWebhook.products.map(product => product.id);

            form.find('select[name="product_id[]"] option').each(function() {
                $(this).prop('selected', products.includes(parseInt($(this).val())));
            });
        });
    </script>

    <script>
        $(document).on('click', '.editAppShopUser', function() {
            const drawerFormApp   = $('#drawerFormApp');
            const dataAppShopUser = $(this).data('appshopuser');
            let contentFormApp    = $(this).data('contentformapp');
            let actionFormEdit    = $(this).data('actionformedit');

            drawerFormApp.find('.titleDrawer').text(dataAppShopUser.name);
            drawerFormApp.find("#contentFormApp").empty().html(contentFormApp);

            drawerFormApp.find('form').attr('action', actionFormEdit);
            drawerFormApp.append('<input type="hidden" name="_method" value="PUT">');
        });
    </script>
@endsection
