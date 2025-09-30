@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">

            <h1>Checkouts</h1>

            <a
                class="button button-primary h-12 gap-1 rounded-full"
                title="Adicionar checkout"
                href="{{ route('dashboard.checkouts.create') }}"
            >
                @include('components.icon', [
                    'icon' => 'add',
                    'custom' => 'text-xl',
                ])
                Adicionar checkout
            </a>

        </div>

        <div class="">

            @component('components.card', ['custom' => 'overflow-hidden'])
                <div class="overflow-x-scroll md:overflow-visible">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Checkout</th>
                                <th>Padrão</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkouts as $checkout)
                                <tr>
                                    <td>{{ $checkout->name }}</td>
                                    <td>{{ $checkout->defaultFormatted }}</td>
                                    <td>
                                        <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                            @include('components.icon', [
                                                'icon' => 'circle',
                                                'type' => 'fill',
                                                'custom' => 'text-xs text-primary',
                                            ])
                                            {{ $checkout->statusFormatted }}
                                        </div>
                                    </td>

                                    <td class="text-end">

                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreTableParticipations' . $checkout->id,
                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>
                                                <li>
                                                    <a
                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                        href="{{ route('dashboard.checkouts.edit', $checkout) }}"
                                                        title="Editar checkout"
                                                    >
                                                        Editar
                                                    </a>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('dashboard.checkouts.destroy', $checkout) }}"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                            type="submit"
                                                            title="Remover checkout"
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
                            @empty
                                <tr>
                                    <td
                                        colspan="4"
                                        class="py-8 text-center"
                                    >Nenhum checkout cadastrado</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            @endcomponent

        </div>

    </div>
@endsection
