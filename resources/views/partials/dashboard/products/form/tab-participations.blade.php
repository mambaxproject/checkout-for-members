<div
    class="tab-content hidden"
    id="tab-participations"
    data-tab="tab-participations"
>

    <div class="space-y-4 md:space-y-10">

        @component('components.card', ['custom' => 'p-6 md:p-8'])
            <div class="space-y-8">

                <div class="space-y-4 md:space-y-6">

                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                        <h3>Coprodutor</h3>

                        @unless ($product->coproducers->isNotEmpty())
                            <button
                                class="button button-primary h-12 gap-1 rounded-full"
                                data-drawer-target="drawerAddParticipations"
                                data-drawer-show="drawerAddParticipations"
                                data-drawer-placement="right"
                                type="button"
                            >
                                @include('components.icon', [
                                    'icon' => 'add',
                                    'custom' => 'text-xl',
                                ])
                                Convidar produtor
                            </button>
                        @endunless

                    </div>

                    <form
                        action=""
                        method=""
                    >
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12">
                                <div class="append">
                                    <!-- filter rows in id="tableCoproducers" -->
                                    <input
                                        placeholder="Pesquisar"
                                        onkeyup="filterTable('tableCoproducers', 'append-item', this.value)"
                                        type="text"
                                    />
                                    <button
                                        class="append-item-right w-12"
                                        type="button"
                                    >
                                        @include('components.icon', ['icon' => 'search'])
                                    </button>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                    <div class="overflow-x-scroll md:overflow-visible">
                        <table
                            class="table-lg table w-full"
                            id="tableCoproducers"
                        >
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Validade</th>
                                <th>Comissão total</th>
                                <th>Situação</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($product->coproducers as $coproducer)
                                <tr>
                                    <td>{{ $coproducer->name }}</td>
                                    <td>
                                        {{ $coproducer?->valid_until_at?->format('d/m/Y') ?? 'Vitalício' }}
                                    </td>
                                    <td>
                                        {{ $coproducer->percentage_commission ? Number::percentage($coproducer->percentage_commission) : '-' }}
                                    </td>
                                    <td>
                                        <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                            @include('components.icon', [
                                                'icon' => 'circle',
                                                'type' => 'fill',
                                                'custom' => 'text-xs ' . \App\Enums\SituationCoproducerEnum::getClass($coproducer->situation),
                                            ])
                                            {{ $coproducer->situationFormatted }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreTableParticipations' . $loop->iteration,
                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>
                                                <li>
                                                    <button
                                                        class="updateCoproducer flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                        data-coproducer="{{ $coproducer }}"
                                                        data-drawer-target="drawerAddParticipations"
                                                        data-drawer-show="drawerAddParticipations"
                                                        data-drawer-placement="right"
                                                        title="Editar"
                                                        type="button"
                                                    >
                                                        Editar
                                                    </button>
                                                </li>

                                                <li>
                                                    <form
                                                        action="{{ route('dashboard.coproducers.destroy', $coproducer) }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            class="flex w-full items-center rounded-lg px-3 py-2 text-sm hover:bg-neutral-100"
                                                            type="submit"
                                                            onclick="return confirm('Tem certeza que deseja excluir o co-produtor {{ $coproducer->name }}?');"
                                                        >
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
                                    <td
                                        class="text-center"
                                        colspan="5"
                                    >
                                        Sem registros
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        @endcomponent

            <button
                    class="button button-primary mx-auto h-12 w-full max-w-xs rounded-full"
                    type="button"
                    onclick="document.location.href = document.location.pathname + '{{ '#tab=tab-area-members' }}'; window.location.reload();"
            >
                Salvar
            </button>

    </div>

</div>

@push('floating')
    @component('components.drawer', [
        'id' => 'drawerAddParticipations',
        'title' => 'Adicionar co-produtor',
        'custom' => 'max-w-xl',
    ])
        <form
            action="{{ route('dashboard.coproducers.store', $product) }}"
            method="POST"
            id="formAddParticipations"
        >
            @csrf

            <div class="grid grid-cols-12 gap-6">

                <div class="col-span-12">
                    <label for="name">Nome do co-produtor</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Nome do co-produtor"
                        required
                    />
                </div>

                <div class="col-span-12">
                    <label for="email">E-mail do co-produtor</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="E-mail do co-produtor"
                        required
                    />
                </div>

                <div class="col-span-12">

                    <div class="space-y-6">

                        <input
                            id="hiddenParticipationsReceivedProducer"
                            type="hidden"
                            name="allow_producer_sales"
                            value="1"
                        >
                        @component('components.toggle', [
                            'id' => 'toggleParticipationsReceivedProducer',
                            'label' => 'Receber comissão de vendas do produtor',
                            'isChecked' => true,
                            'contentEmpty' => true,
                        ])
                        @endcomponent

                        <input
                            id="hiddenParticipationsReceivedAffiliates"
                            type="hidden"
                            name="allow_affiliate_sales"
                            value="0"
                        >
                        @component('components.toggle', [
                            'id' => 'toggleParticipationsReceivedAffiliates',
                            'label' => 'Receber comissão de vendas de afiliados',
                            'contentEmpty' => true,
                        ])
                        @endcomponent

                    </div>

                </div>

                <div class="col-span-12">

                    <label for="percentage_commission">Valor da comissão</label>
                    <input
                        type="text"
                        id="percentage_commission"
                        name="percentage_commission"
                        placeholder="Valor da comissão"
                        onkeyup="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\d{1,})(\d{2})$/, '$1.$2');"
                        maxlength="5"
                    />
                    <p class="mt-px text-xs italic text-neutral-400">O valor da comissão é definada em porcentagem</p>

                </div>

                <div class="comission col-span-12">
                    <label for="valid_until_at">Duração do Contrato</label>
                    <select
                        name="valid_until_at"
                        id="valid_until_at"
                    >
                        <option value="7">7 dias</option>
                        <option value="15">15 dias</option>
                        <option value="30">30 dias</option>
                        <option value="lifetime">Vitalício</option>
                    </select>
                </div>

            </div>

            <button
                class="button button-primary mt-8 h-12 w-full gap-1 rounded-full"
                id="submitButton"
                type="submit"
            >
                @include('components.icon', [
                    'icon' => 'add',
                    'custom' => 'text-xl',
                ])
                Adicionar
            </button>
        </form>
    @endcomponent
@endpush

@push('custom-script')
    <script>
        function filterTable(tableCoproducers, appendItem, value) {
            let rows = document.getElementById(tableCoproducers).getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                let name = rows[i].getElementsByTagName('td')[0];

                if (name) {
                    let textValue = name.textContent || name.innerText;

                    if (textValue.toUpperCase().indexOf(value.toUpperCase()) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }
    </script>

    <script>
        // Adiciona valor para input hidden checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const toggleParticipationsReceivedProducer = document.getElementById('toggleParticipationsReceivedProducer');
            const toggleParticipationsReceivedAffiliates = document.getElementById('toggleParticipationsReceivedAffiliates');
            const hiddenParticipationsReceivedProducer = document.getElementById('hiddenParticipationsReceivedProducer');
            const hiddenParticipationsReceivedAffiliates = document.getElementById('hiddenParticipationsReceivedAffiliates');

            if (toggleParticipationsReceivedProducer) {
                toggleParticipationsReceivedProducer.addEventListener('change', function() {
                    hiddenParticipationsReceivedProducer.value = toggleParticipationsReceivedProducer.checked ? '1' : '0';
                });
            }

            if (toggleParticipationsReceivedAffiliates) {
                toggleParticipationsReceivedAffiliates.addEventListener('change', function() {
                    hiddenParticipationsReceivedAffiliates.value = toggleParticipationsReceivedAffiliates.checked ? '1' : '0';
                });
            }
        });
    </script>

    <script>
        // Carrega data para drawer
        $(document).on('click', '.updateCoproducer', function() {
            const drawerTarget = $(this).data('drawer-target');
            const drawer = $(`#${drawerTarget}`);
            const data = $(this).data('coproducer');

            if (data) {
                drawer.find('form').attr('action', `/dashboard/coproducers/${data.id}/update`);

                if (drawer.find('input[name="_method"][value="PUT"]').length === 0) {
                    drawer.find('form').append('<input type="hidden" name="_method" value="PUT">');
                }

                drawer.find('#name').val(data.name).prop('readonly', false);
                drawer.find('#email').val(data.email).prop('readonly', true);
                drawer.find('#toggleParticipationsReceivedProducer').prop('checked', data.allow_producer_sales === 1);
                drawer.find('#toggleParticipationsReceivedAffiliates').prop('checked', data.allow_affiliate_sales === 1);
                drawer.find('#hiddenParticipationsReceivedProducer').val(data.allow_producer_sales);
                drawer.find('#hiddenParticipationsReceivedAffiliates').val(data.allow_affiliate_sales);
                drawer.find('#percentage_commission').val(data.percentage_commission);
                drawer.find('#valid_until_at').val(data.valid_until_at ? data.duration : 'lifetime');
            }
        });
    </script>

    <script>
        // Envia form
        $(document).on('submit', '#formAddParticipations', function(event) {
            event.preventDefault();

            const toggleParticipationsReceivedProducer = document.getElementById('toggleParticipationsReceivedProducer');
            const toggleParticipationsReceivedAffiliates = document.getElementById('toggleParticipationsReceivedAffiliates');
            const isProducerChecked = toggleParticipationsReceivedProducer?.checked;
            const isAffiliateChecked = toggleParticipationsReceivedAffiliates?.checked;

            if (!isProducerChecked && !isAffiliateChecked) {
                notyf.info('Selecione pelo menos uma opção de comissão ou preencha o valor da comissão do produtor ou dos afiliados.');
                return false;
            }

            this.submit();
        });
    </script>
@endpush