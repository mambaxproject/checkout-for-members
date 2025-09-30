<div class="rounded-xl bg-neutral-700 p-4">

    <h4 class="mb-3 font-semibold text-white">Detalhes do pedido</h4>

    <div class="flex items-center gap-2">
        <p class="text-white">Nº do pedido:</p>
        <p class="text-white">#{{ $order->client_orders_uuid }}</p>
    </div>

    <div class="flex items-center gap-2">
        <p class="text-white">Data do pedido:</p>
        <p class="text-white">{{ $order->created_at->format('j \d\e M\. \d\e Y') }}</p>
    </div>

    <div class="flex items-start gap-2">
        <p class="text-white">Total do pedido:</p>

        @php
            $product = $order->item->product;
        @endphp

        @if ($product->isRecurring)
            <p class="text-white">
                    <span > {{ $order->brazilianFirstPrice }}</span>
                    <span >primeiro pagamento</span></br>

                <span ><span class="font-bold">Próxima cobrança:</span> {{ $order->brazilianPrice }} em {{ $product->nextCharge()->format('d/m/Y') }}</span><br/>

                <span >{{ $product->cyclePaymentTranslated }}</span>
            </p>
        @else
            <p class="text-white">
                {{ $order->brazilianPrice }} ({{ $order->items_count }} {{ Str::plural('item', $order->items_count) }})
                @if ($payment->isCreditCard)
                    <span class="text-sm text-neutral-300">em {{ $payment->installments }}x</span>
                @else
                    <span class="text-sm text-neutral-300">à vista</span>
                @endif
            </p>
        @endif
    </div>

    <hr class="mt-6 border-neutral-500">

</div>

{{-- <div class="space-y-6">
    <div class="flex items-center gap-4 md:gap-6">
        <div class="flex-1 border-4 rounded-xl p-3">
            <p>
                <span class="text-xl font-bold md:text-2xl">Data do pedido: </span>
                <span class="text-xl text-gray-500 font-bold md:text-2xl">{{ $order->created_at->format('j \d\e M\. \d\e Y') }}</span>
            </p>
            <p>
                <span class="text-xl font-bold md:text-2xl">Nº do pedido:</span>
                <span class="text-xl text-gray-500 md:text-2xl"> {{$order->id}}</span>
            </p>
            <p>
                <span class="text-xl font-bold md:text-2xl">Total do pedido: </span>
                <span class="text-xl text-gray-500 font-bold md:text-2xl">{{$order->brazilianPrice}} ({{$order->items_count}} {{ Str::plural('item', $order->items_count) }})</span>
                @if ($payment->isCreditCard)
                    <span class="text-sm text-neutral-600">em {{$payment->installments }}x</span>
                @else
                    <span class="text-sm text-neutral-600">à vista</span>
                @endif
            </p>

        </div>

    </div>

</div> --}}
