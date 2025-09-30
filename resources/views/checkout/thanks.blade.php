@extends('layouts.checkout')

@section('content')
    @if($payment->isCreditCard)
        @include('partials.checkout.payment-creditcard')
    @elseif($payment->isBillet)
        @include('partials.checkout.payment-finish-billet')
    @else
        @include('partials.checkout.payment-finish-pix')
    @endif

    <script>
        function redirectToTelegramInviteLink() {
            $.get('{{route('api.public.telegram.getInviteLink', ['payment' => $payment->external_identification])}}', function(data) {
                if (data.invite_link) {
                    window.location = data.invite_link;
                }
            })
        }

        window.order = {
            id_transaction: '{{$payment->external_identification}}',
            total: {{$order->amount}},
            payment_method: '{{$payment->payment_method}}',
            products: @json($order->items),
            thanks_page: '{{$order->thanksPage()}}',
            has_custom_page: '{{$order->hasCustomThanksPage}}',
            has_telegram_group: {{$order->item->product->parentProduct->telegramGroup()->exists() ? 1 : 0}},
            redirectToTelegramInviteLink: {{$order->item->product->parentProduct->getValueSchemalessAttributes('redirectToTelegramLink') ?? 0}},
        }
    </script>
@endsection