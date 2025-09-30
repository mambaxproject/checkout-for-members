@extends('layouts.checkout')

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="rounded-3xl bg-neutral-100 p-4 md:p-8">

            <div class="space-y-4 md:space-y-6">

                <div class="flex items-center gap-4 md:gap-6">

                    <figure class="h-20 w-20 overflow-hidden rounded-xl md:h-28 md:w-28">
                        <img
                            class="h-full w-full object-cover"
                            src="{{ $product->featuredImageUrl }}"
                            alt="{{ $product->name }}"
                            loading="lazy"
                        />
                    </figure>

                    <div class="flex-1">

                        <h3>{{ $product->name }}</h3>

                        <p class="text-sm text-neutral-600">
                            {{ $product->getValueSchemalessAttributes('affiliate.descriptionProduct') }}
                        </p>

                    </div>

                </div>

                @component('components.card', ['custom' => 'p-6 md:p-8 flex-1'])
                    <div class="space-y-6">
                        <p>
                            E-mail de suporte para afiliados:
                            <b>{{ $product->getValueSchemalessAttributes('affiliate.emailSupport') }}</b>
                        </p>
                        <hr>

                        <h3>Ofertas</h3>

                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Nome
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Preço
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Tipo pagamento
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($activeOffers as $offer)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $offer->name }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ $offer->brazilianPrice }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $product->paymentTypeTranslated }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endcomponent

                @auth
                    @if ($product?->shop?->owner_id === auth()->id() || $product->coproducers->where('situation', 'ACTIVE')->contains('user_id', auth()->id()))
                        <button
                            class="button button-light bg-gray-200 h-10 w-full rounded-full"
                            title="Você é o dono deste produto"
                            disabled
                        >
                            Você é o produtor
                        </button>
                    @elseif ($product->affiliates->doesntContain('user_id', auth()->id()))
                        <form method="POST" action="{{ route('affiliate.register', $product) }}">
                            @csrf

                            <div class="space-y-6">
                                <button
                                    type="submit"
                                    class="button h-10 w-full rounded-full md:h-12 focus:outline-none text-white bg-green-700 hover:bg-green-800"
                                    onclick="return confirm('Tem certeza?');"
                                >
                                    Solicitar afiliação
                                </button>
                            </div>
                        </form>
                    @else
                        <button
                            class="button button-light bg-gray-200 h-10 w-full rounded-full"
                            title="Você já é afiliado deste produto"
                            disabled
                        >
                            Você já é afiliado
                        </button>
                    @endif
                @endauth

                @guest
                    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 text-center" role="alert">
                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        Você precisa estar logado para solicitar afiliação.
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                        <a href="https://web.suitpay.app/login"
                           title="Fazer login"
                           target="_blank"
                           class="button h-10 w-full rounded-full md:h-12 focus:outline-none text-white bg-green-700 hover:bg-green-800"
                        >
                            Fazer login
                        </a>

                        <a href="https://web.suitpay.app/register"
                           title="Fazer cadastro"
                           target="_blank"
                           class="button h-10 w-full rounded-full md:h-12 focus:outline-none text-white bg-green-700 hover:bg-green-800"
                        >
                            Fazer cadastro
                        </a>
                    </div>
                @endguest

            </div>

        </div>
    </div>
@endsection
