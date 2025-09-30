@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h1>Telegram > {{$telegram->name}}</h1>
        </div>


        <div>

            @component('components.card', ['custom' => 'overflow-hidden'])
                <div class="overflow-x-scroll md:overflow-visible">
                    <table class="table w-full">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telegram Username</th>
                            <th>link</th>
                            <th>pagamento</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($members as $member)
                            <tr>
                                <td>{{$member->order->user->name}}</td>
                                <td>{{$member->telegram_username}}</td>
                                <td>{{$member->invite_link}}</td>
                                <td>{{$member->order->paymentStatus}}</td>
                                <td>
                                    <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' => 'text-xs ' . \App\Enums\SituationTelegramGroupMemberEnum::getClass($member->status),
                                        ])
                                        {{ $member->situationFormatted }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div
                                            class="col-span-12 rounded-lg bg-gray-50 p-4 text-center text-sm text-gray-800 dark:bg-gray-800 dark:text-gray-300"
                                            role="alert"
                                    >
                                        Sem registros.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            @endcomponent

        </div>

    </div>
@endsection

@push('floating')
@endpush

@push('custom-script')
@endpush
