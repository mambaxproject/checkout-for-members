@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Links de afiliado do produto <b>{{ $product->name }}</b></h1>

        <div class="space-y-4 md:space-y-10">

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
                                        <th>URL</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($activeOffers as $offer)
                                        @php($linkAffiliate = url($offer->url).'?afflt='.$affiliate->code)
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
                                                        data-clipboard-text="{{ $linkAffiliate }}"
                                                    >
                                                        {{ $linkAffiliate }}

                                                        <span class="absolute -right-16 hidden rounded-md bg-gray-200 px-2 py-1 text-xs font-semibold group-hover:block">Copiar</span>
                                                    </span>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                                    @include('components.icon', [
                                                        'icon' => 'circle',
                                                        'type' => 'fill',
                                                        'custom' => 'text-xs ' . \App\Enums\StatusEnum::getClassText($offer->status),
                                                    ])
                                                    {{ $offer->statusFormatted }}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Nenhuma oferta ativa</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            @endcomponent

        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>
@endsection