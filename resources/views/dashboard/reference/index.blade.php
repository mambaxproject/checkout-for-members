@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Referência</h1>

        <div class="grid grid-cols-1 gap-3 xl:grid-cols-3 xl:gap-6">

            <div class="col-span-1 xl:col-span-3">
                @component('components.card', ['custom' => 'p-8'])
                    <div class="space-y-6">

                        <h3>Link de referência</h3>

                        <form
                                action=""
                                method=""
                        >
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12">
                                    <label for="">Ganhe 1.5% de comissão das pessoas que você referenciou por até 12 meses</label>
                                    <div class="append">
                                        <input
                                                value="https://demo.website/AUmmtTm4"
                                                type="text"
                                                disabled
                                        />
                                        <button class="append-item-right animate h-12 w-12 bg-white/30 hover:text-primary">
                                            @include('components.icon', [
                                                'icon' => 'content_copy',
                                                'custom' => 'text-xl',
                                            ])
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                @endcomponent
            </div>

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">

                        <h3>Saldo disponível</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                R$
                                <span class="text-2xl font-semibold">987.256,00</span>
                            </p>

                            <a
                                    class="button button-light h-10 rounded-full"
                                    href="#"
                            >
                                Realizar saque
                            </a>

                        </div>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">

                        <h3>Indicações Ativas</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                R$
                                <span class="text-2xl font-semibold">987.256,00</span>
                            </p>

                            <div class="alert alert-success mt-4">
                                <div class="flex items-center gap-px text-sm font-semibold text-success-600">
                                    @include('components.icon', [
                                        'icon' => 'arrow_upward_alt',
                                        'custom' => 'text-xl',
                                    ])
                                    50%
                                </div>
                                <p class="text-sm">Comparado a última semana</p>
                            </div>

                        </div>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">

                        <h3>Ganhos totais</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                R$
                                <span class="text-2xl font-semibold">987.256,00</span>
                            </p>

                            <div class="alert alert-success mt-4">
                                <div class="flex items-center gap-px text-sm font-semibold text-success-600">
                                    @include('components.icon', [
                                        'icon' => 'arrow_upward_alt',
                                        'custom' => 'text-xl',
                                    ])
                                    50%
                                </div>
                                <p class="text-sm">Comparado a última semana</p>
                            </div>

                        </div>

                    </div>
                @endcomponent

            </div>

        </div>

        <div class="">

            <div class="overflow-x-scroll md:overflow-visible">
                <table class="table w-full">
                    <thead>
                    <tr>
                        <th class="w-14">
                            <input type="checkbox">
                        </th>
                        <th>Nome do cliente</th>
                        <th>Nome do produto</th>
                        <th>Comissão</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @for ($i = 1; $i <= 8; $i++)
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>Nome do cliente</td>
                            <td>Nome do produto</td>
                            <td>R$ 54,20</td>
                            <td>
                                <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                    @include('components.icon', [
                                        'icon' => 'circle',
                                        'type' => 'fill',
                                        'custom' => 'text-xs text-primary',
                                    ])
                                    Aprodvado
                                </div>
                            </td>
                            <td>25/03/2024</td>
                            <td class="text-end">

                                @component('components.dropdown-button', [
                                    'id' => 'dropdownMoreTableParticipations' . $i,
                                    'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                    'custom' => 'text-xl',
                                ])
                                    <ul>
                                        <li>
                                            <a
                                                    class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                    href="#"
                                            >
                                                Detalhes
                                            </a>
                                        </li>
                                    </ul>
                                @endcomponent

                            </td>
                        </tr>
                    @endfor
                    </tbody>
                </table>
            </div>

            @include('components.pagination', [
                'currentPage' => '1',
                'totalPages' => '10',
                'totalItems' => '300',
            ])

        </div>

        @component('components.card', ['custom' => 'p-8'])
            <div class="space-y-6">

                <div class="">
                    <h3>Rastreamento</h3>
                    <p class="text-sm text-neutral-400">Adicione o seu Facebook Pixel ou HTML personalizado para rastrear suas conversões</p>
                </div>

                <form
                        action=""
                        method=""
                >

                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <label>Como você planeja promover o Suit Pay?</label>
                            <textarea
                                    type="text"
                                    rows="4"
                                    placeholder="Explique como você vai promover a Suit Pay (Ads, Indicação de amigos, etc.)"
                            >
                            </textarea>
                        </div>
                        <div class="col-span-12">
                            <label>Código do Facebook Pixel</label>
                            <input
                                    type="text"
                                    placeholder="Exemplo:  897346"
                            >
                        </div>
                        <div class="col-span-12">
                            <label>HTML personalizado:</label>
                            <input
                                    type="text"
                                    placeholder="<script> <!--pixel-->"
                            >
                        </div>
                    </div>

                    <button
                            class="button button-primary mt-6 h-12 rounded-full px-12"
                            type="submit"
                    >
                        Salvar
                    </button>

                </form>

            </div>
        @endcomponent

    </div>
@endsection
