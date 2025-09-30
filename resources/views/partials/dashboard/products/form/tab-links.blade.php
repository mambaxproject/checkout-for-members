<div class="hidden tab-content" id="tab-links" data-tab="tab-links">

    <div class="space-y-4 md:space-y-10">

        @component('components.card', ['custom' => 'p-6 md:p-8'])
            <div class="space-y-8">

                <div class="space-y-4 md:space-y-6">

                    <h3>Domínio Próprio</h3>

                    @component('components.toggle', [
                        'id' => 'toggleDomainLink',
                        'label' => 'Vincular',
                        'isChecked' => $product->domains->isNotEmpty(),
                    ])
                        @if ($product->domains->isEmpty())
                            <button class="button button-light mb-6 ml-auto h-12 gap-1 rounded-full"
                                data-drawer-target="drawerDomainLink" data-drawer-show="drawerDomainLink"
                                data-drawer-placement="right" type="button">
                                @include('components.icon', [
                                    'icon' => 'add',
                                    'custom' => 'text-xl',
                                ])
                                Vincular domínio
                            </button>
                        @endif

                        <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                            <div class="overflow-x-scroll md:overflow-visible">
                                <table class="table-lg table w-full tableDomain">
                                    <thead>
                                        <tr>
                                            <th>Domínio</th>
                                            <th>URL</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($product->domains as $domain)
                                            <tr>
                                                <td>{{ $domain->domain }}</td>
                                                <td>{{ $domain->url }}</td>
                                                <td>
                                                    <button type="button"
                                                        class="validateDnsDomain flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1"
                                                        title="Domínio {{ $domain->verifiedFormatted }}"
                                                        data-domain="{{ $domain }}"
                                                        data-actionCheckDnsDomain="{{ route('dashboard.domains.checkDns', $domain) }}"
                                                        data-drawer-target="drawerInstructionsDnsDomain"
                                                        data-drawer-show="drawerInstructionsDnsDomain"
                                                        data-drawer-placement="right">
                                                        @include('components.icon', [
                                                            'icon' => 'circle',
                                                            'type' => 'fill',
                                                            'custom' => 'text-xs ' . $domain->verifiedClassCss,
                                                        ])
                                                        {{ $domain->verifiedFormatted }}
                                                    </button>
                                                </td>
                                                <td class="text-end">
                                                    @component('components.dropdown-button', [
                                                        'id' => 'dropdownMoreTableDominios0',
                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                        'custom' => 'text-xl',
                                                    ])
                                                        <ul>
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('dashboard.domains.destroy', $domain) }}">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit"
                                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                        onclick="return confirm('Tem certeza?')">
                                                                        Excluir
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    @endcomponent
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    Nenhum domínio vinculado
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endcomponent

                </div>

            </div>
        @endcomponent

        @component('components.card', ['custom' => 'p-6 md:p-8'])
            <div class="space-y-8">

                <div class="space-y-4 md:space-y-6">

                    <h3>Links e ofertas</h3>

                    <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                        <div class="overflow-x-scroll md:overflow-visible">
                            <table class="table-lg table w-full">
                                <thead>
                                    <tr>
                                        <th>Nome da oferta</th>
                                        <th>Valor</th>
                                        <th class="w-[40%]">URL</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activeOffers as $offer)
                                        <tr>
                                            <td>{{ $offer->name }}</td>
                                            <td>{{ $offer->brazilianPrice }}</td>
                                            <td>
                                                <div class="flex items-center gap-2">

                                                    @include('components.icon', [
                                                        'icon' => 'content_copy',
                                                        'custom' => 'text-xl text-gray-400',
                                                    ])

                                                    <span
                                                        class="copyClipboard group relative flex w-fit cursor-pointer items-center gap-2"
                                                        data-clipboard-text="{{ $offer->url }}">
                                                        {{ $offer->url }}

                                                        <span
                                                            class="absolute -right-16 hidden rounded-md bg-gray-200 px-2 py-1 text-xs font-semibold group-hover:block">Copiar</span>
                                                    </span>

                                                </div>
                                            </td>
                                            <td>
                                                <form id="statusForm-{{ $offer->id }}"
                                                    action="{{ route('dashboard.products.toogleStatus', $offer->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')

                                                    @include('components.toggle', [
                                                        'id' => 'offerStatus-' . $offer->id,
                                                        'contentEmpty' => true,
                                                        'isChecked' => $offer->status === 'ACTIVE',
                                                    ])
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                Nenhuma oferta ativa
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        @endcomponent

        @component('components.card', ['custom' => 'p-6 md:p-8'])
            <div class="space-y-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <h3>Links UTM Gerados ({{ $utmLinks->count() }})</h3>
                    <div class="flex items-center gap-2">
                        <button class="button addUTMLink button-primary h-12 rounded-full"
                            data-drawer-target="drawerUtmLink" data-drawer-show="drawerUtmLink"
                            data-drawer-placement="right" type="button">
                            @include('components.icon', [
                                'icon' => 'add',
                                'custom' => 'text-xl',
                            ])
                            Adicionar UTM link
                        </button>
                    </div>
                </div>

                @if ($utmLinks->count())
                    <div class="space-y-4">


                        <div class=" rounded-lg border border-neutral-100 2xl:overflow-visible">
                            <div class="overflow-x-scroll 2xl:overflow-visible">
                                <table class="table-lg table w-full">
                                    <thead>
                                        <tr>
                                            <th>Nome da oferta</th>
                                            <th>Origem</th>
                                            <th>Meio</th>
                                            <th>Campanha</th>
                                            <th>Conteúdo</th>
                                            <th>Termo</th>
                                            <th class="w-[20%]">URL</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($utmLinks as $link)
                                            <tr>
                                                <td title="{{ $link->product->name }}" class="">
                                                    {{ Str::limit($link->product->name, 15) }}</td>
                                                <td title="{{ $link->utm_source }}">
                                                    {{ Str::limit($link->utm_source, 25) }}</td>
                                                <td title="{{ $link->utm_medium }}">
                                                    {{ Str::limit($link->utm_medium, 25) }}</td>
                                                <td title="{{ $link->utm_campaign }}">
                                                    {{ Str::limit($link->utm_campaign, 15) }}</td>
                                                <td title="{{ $link->utm_content }}">
                                                    {{ Str::limit($link->utm_content, 15) }}</td>
                                                <td title="{{ $link->utm_term }}">{{ Str::limit($link->utm_term, 15) }}
                                                </td>
                                                <td
                                                    style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; min-width: 20px;">
                                                    <div class="flex w-fit items-center gap-2">
                                                        @include('components.icon', [
                                                            'icon' => 'content_copy',
                                                            'custom' => 'text-xl text-gray-400',
                                                        ])
                                                        <span
                                                            class="copyClipboard group relative flex w-fit cursor-pointer items-center gap-2  whitespace-nowrap"
                                                            data-clipboard-text="{{ $link->url }}">
                                                            {{ Str::limit($link->url, 20) }}
                                                            <span
                                                                class="absolute -right-16 hidden rounded-md bg-gray-200 px-2 py-1 text-xs font-semibold group-hover:block">
                                                                Copiar
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @component('components.dropdown-button', [
                                                        'id' => 'dropdownMoreTableUTMLink' . $link->id,
                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                        'custom' => 'text-xl',
                                                    ])
                                                        <ul>
                                                            <li>
                                                                <button data-drawer-target="drawerUtmLink"
                                                                    data-drawer-show="drawerUtmLink"
                                                                    data-drawer-placement="right"
                                                                    data-data="{{ $link->toJson() }}"
                                                                    data-url="{{ route('dashboard.utmLink.update', $link->id) }}"
                                                                    class="flex editUtmLink items-center rounded-lg px-3 py-2 hover:bg-neutral-100">
                                                                    Editar
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('dashboard.utmLink.destroy', $link->id) }}">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit"
                                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                        title="Solicitar estorno">
                                                                        Remover
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    @endcomponent
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Nenhuma oferta ativa</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                @endif
            </div>
        @endcomponent
    </div>
</div>

@push('floating')
    @component('components.drawer', [
        'id' => 'drawerUtmLink',
        'title' => 'Gerar link rastreável',
        'custom' => 'max-w-xl',
    ])
        <form method="POST" action="{{ route('dashboard.utmLink.store') }}">
            @csrf
            @method('post')
            <div class="mb-4">
                <label for="offer">Oferta de destino *</label>
                <select id="offer" name="product_id" required class="form-select">
                    @foreach ($activeOffers as $offer)
                        <option value="{{ $offer->id }}">{{ $offer->name }} - {{ $offer->brazilianPrice }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="utm_source">Origem (utm_source) *</label>
                <input type="text" id="utm_source" name="utm_source" required class="form-input"
                    placeholder="Ex: facebook, email, etc." />
            </div>

            <div class="mb-4">
                <label for="utm_medium">Meio (utm_medium) *</label>
                <input type="text" id="utm_medium" name="utm_medium" required class="form-input"
                    placeholder="Ex: cpc, feed, etc." />
            </div>

            <div class="mb-4">
                <label for="utm_campaign">Campanha (utm_campaign) </label>
                <input type="text" id="utm_campaign" name="utm_campaign" class="form-input"
                    placeholder="Ex: lancamento-maio" />
            </div>

            <div class="mb-4">
                <label for="utm_content">Conteúdo (utm_content)</label>
                <input type="text" id="utm_content" name="utm_content" class="form-input"
                    placeholder="Ex: banner-topo" />
            </div>

            <div class="mb-4">
                <label for="utm_term">Termo (utm_term)</label>
                <input type="text" id="utm_term" name="utm_term" class="form-input"
                    placeholder="Ex: serviços, marketing" />
            </div>

            <button type="submit" class="button button-primary mt-6 w-full h-12 rounded-full">
                Gerar link rastreável
            </button>
        </form>
    @endcomponent

    @if ($product->domains->isEmpty())
        @component('components.drawer', [
            'id' => 'drawerDomainLink',
            'title' => 'Vincular domínio próprio',
            'custom' => 'max-w-xl',
        ])
            <form action="{{ route('dashboard.domains.store', $product) }}" method="post">
                @csrf

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12">
                        <label for="domain">URL do domínio</label>
                        <input type="url" id="domain" name="domain" placeholder="Digite seu domínio"
                            pattern="https?://.+" title="A URL deve começar com http:// ou https://" required />

                        <p class="text-sm text-gray-500 italic">Exemplo: <strong>https://meudominio.com.br</strong></p>
                    </div>

                    <div class="col-span-12">
                        <div class="alert alert-light mt-2">
                            @include('components.icon', [
                                'icon' => 'help',
                                'custom' => 'text-xl',
                            ])
                            <p>Vincule um domínio que já percente a você.</p>
                        </div>
                    </div>

                </div>

                <button type="submit" class="button button-primary mt-8 h-12 w-full gap-1 rounded-full">
                    Salvar
                </button>
            </form>
        @endcomponent
    @endif

    @component('components.drawer', [
        'id' => 'drawerInstructionsDnsDomain',
        'title' => 'Validação domínio próprio',
        'custom' => 'max-w-xl',
    ])
        <form action="" method="post" id="formCheckDnsDomain">
            @csrf

            <div class="rounded-xl bg-neutral-100 p-6">
                <p>Para utilizar seu checkout, adicione o registro DNS abaixo no serviço em que você registrou seu domínio
                    (Registro.br, GoDaddy, Cloudflare, Locaweb, ou outro):</p>
                <ul class="infosDnsDomain mt-4">
                    <li>
                        - URL:
                        <strong class="font-medium" id="url"></strong>
                    </li>
                    <li>
                        - TIPO:
                        <strong class="font-medium" id="type"></strong>
                    </li>
                    <li>
                        - HOST:
                        <strong class="font-medium" id="host"></strong>
                    </li>
                    <li>
                        - VALOR:
                        <strong class="font-medium" id="value"></strong>
                    </li>
                </ul>
            </div>

            <button class="button button-primary mt-8 h-12 w-full gap-1 rounded-full" type="submit">
                Validar DNS
            </button>
        </form>
    @endcomponent
@endpush

@push('custom-script')
    <script>
        $(document).on('click', '.editUtmLink', function() {
            const data = $(this).data('data');
            const url = $(this).data('url');
            const drawer = document.getElementById('drawerUtmLink');

            drawer.querySelector('form').setAttribute('action', url);
            drawer.querySelector('input[name=_method]').value = "put";

            drawer.querySelector('.button').innerHTML = "Salvar";
            drawer.querySelector('.titleDrawer').innerHTML = 'Editar UTM link';
            drawer.querySelector('select[name=product_id]').value = data.product_id;
            drawer.querySelector('input[name=utm_source]').value = data.utm_source;
            drawer.querySelector('input[name=utm_medium]').value = data.utm_medium;
            drawer.querySelector('input[name=utm_campaign]').value = data.utm_campaign ?? '';
            drawer.querySelector('input[name=utm_content]').value = data.utm_content ?? '';
            drawer.querySelector('input[name=utm_term]').value = data.utm_term ?? '';
        });

        $(document).on('click', '.addUTMLink', function() {
            const drawer = document.getElementById('drawerUtmLink');

            drawer.querySelector('.titleDrawer').innerHTML = 'Gerar link rastreável';
            drawer.querySelector('.button').innerHTML = "Gerar link rastreável";
        })
    </script>
    <script>
        window.addEventListener("load", (event) => {
            let domainCreated = Boolean({{ session('domainCreated') }});
            const validateDnsDomainButton = document.querySelector(
                '.tableDomain tbody tr:first-child .validateDnsDomain');

            if (domainCreated && validateDnsDomainButton) {
                validateDnsDomainButton.click();
            }
        });
    </script>

    <script>
        $(document).on('click', '.validateDnsDomain', function() {
            const domain = $(this).data('domain');

            $(".infosDnsDomain #url").text(domain.dns.url);
            $(".infosDnsDomain #type").text(domain.dns.type);
            $(".infosDnsDomain #host").text(domain.dns.host);
            $(".infosDnsDomain #value").text(domain.dns.value);

            $("#formCheckDnsDomain").attr('action', $(this).data('actioncheckdnsdomain'));
        });
    </script>

    <script>
        document.querySelectorAll('[id^="offerStatus"]').forEach((element) => {
            element.addEventListener('change', function() {
                if (confirm('Tem certeza?')) {
                    let form = this.closest('form');
                    form.submit();
                } else {
                    this.checked = !this.checked;
                }
            });
        });
    </script>
@endpush
