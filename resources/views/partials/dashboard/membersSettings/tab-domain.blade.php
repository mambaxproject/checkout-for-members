<div class="tab-content hidden" id="tab-domain" data-tab="tab-domain">
    @component('components.card', ['custom' => 'p-6 md:p-8'])
        <div class="space-y-8">

            <div class="space-y-4 md:space-y-6">
                <div>
                    <h3>Domínio Próprio</h3>
                    @if (empty($domains))
                        <button class="button button-light ml-auto h-12 gap-1 rounded-full"
                            data-drawer-target="drawerDomainLink" data-drawer-show="drawerDomainLink"
                            data-drawer-placement="right" type="button">
                            @include('components.icon', [
                                'icon' => 'add',
                                'custom' => 'text-xl',
                            ])
                            Vincular domínio
                        </button>
                    @endif
                </div>
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
                                @if (!empty($domains))
                                    <tr>
                                        <td>{{ 'https://www.' . preg_replace('/^members\./', '', $domains['url']) }}</td>
                                        <td>{{ 'https://' . $domains['url'] }}</td>
                                        <td>
                                            <button type="button"
                                                class="validateDnsDomain flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1"
                                                title="Domínio a" data-domain="a" data-actionCheckDnsDomain=""
                                                data-drawer-target="drawerInstructionsDnsDomain"
                                                data-drawer-show="drawerInstructionsDnsDomain"
                                                data-drawer-placement="right">
                                                @include('components.icon', [
                                                    'icon' => 'circle',
                                                    'type' => 'fill',
                                                    'custom' =>
                                                        'text-xs ' .
                                                        (!empty($domains['status'])
                                                            ? 'text-primary'
                                                            : 'text-gray-500'),
                                                ])
                                                @if ($domains['status'])
                                                    Verificado
                                                @else
                                                    Não verificado
                                                @endif
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
                                                            action="{{ route('dashboard.members.deleteDomain', ['courseId' => $course['id']]) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                onclick="return confirm('Tem certeza?')">
                                                                Excluir
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="validateDnsDomain flex w-fit items-center gap-2 px-3 py-1"
                                                            title="Domínio {{ $domains['url'] ?? '' }}"
                                                            data-drawer-target="drawerInstructionsDnsDomain"
                                                            data-drawer-show="drawerInstructionsDnsDomain"
                                                            data-drawer-placement="right">
                                                            Detalhes
                                                        </button>
                                                    </li>
                                                </ul>
                                            @endcomponent
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            Nenhum domínio vinculado
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    @endcomponent


    @push('floating')
        @component('components.drawer', [
            'id' => 'drawerDomainLink',
            'title' => 'Vincular domínio próprio',
            'custom' => 'max-w-xl',
        ])
            @if (empty($domains))
                <form action="{{ route('dashboard.members.addDomain', ['courseId' => $course['id']]) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <label for="domain">URL do domínio</label>
                            <input type="url" id="domain" name="url" placeholder="Digite seu domínio"
                                pattern="https?://.+" title="A URL deve começar com http:// ou https://" required />

                            <p class="text-sm mt-4 text-gray-500 italic">Exemplo: <strong>https://meudominio.com.br</strong>
                            </p>
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
            @endif
        @endcomponent

        @component('components.drawer', [
            'id' => 'drawerInstructionsDnsDomain',
            'title' => 'Informações domínio próprio',
            'custom' => 'max-w-xl',
        ])
            <div class="rounded-xl bg-neutral-100 p-6">
                <p>Para utilizar seu checkout, adicione o registro DNS abaixo no serviço em que você registrou seu domínio
                    (Registro.br, GoDaddy, Cloudflare, Locaweb, ou outro):</p>
                <ul class="infosDnsDomain mt-4">
                    <li>
                        - URL:
                        <strong class="font-medium" id="url">{{ $domains['url'] ?? '' }}</strong>
                    </li>
                    <li>
                        - TIPO:
                        <strong class="font-medium" id="type">{{ $domains['type'] ?? '' }}</strong>
                    </li>
                    <li>
                        - HOST:
                        <strong class="font-medium" id="host">{{ $domains['host'] ?? '' }}</strong>
                    </li>
                    <li>
                        - VALOR:
                        <strong class="font-medium" id="value">{{ $domains['value'] ?? '' }}</strong>
                    </li>
                </ul>
            </div>
        @endcomponent
        @if (!empty($domains))
            @include('partials.dashboard.membersSettings.custom-login')
        @endif
    @endpush
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabDomain = document.getElementById("tab-domain");
            const customLogin = document.getElementById("customLogin");

            if (!customLogin) {
                console.warn("Elementos não encontrados:", {
                    tabDomain,
                    customLogin
                });
                return;
            }

            const observer = new MutationObserver(() => {
                if (tabDomain.classList.contains("hidden")) {
                    customLogin.classList.add("hidden");
                } else {
                    customLogin.classList.remove("hidden");
                }
            });

            observer.observe(tabDomain, {
                attributes: true,
                attributeFilter: ["class"]
            });
        });
    </script>
</div>
