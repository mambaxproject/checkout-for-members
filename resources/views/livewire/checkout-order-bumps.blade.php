@php
    $offersCount = $orderBumps->count();
@endphp

<div>
    @if ($offersCount)

        @component('components.card', [
            'custom' => 'p-4 md:p-6 space-y-4 order-bump mb-5',
        ])
            <div class="mx-auto w-fit rounded-full bg-success-100 px-6 py-2">
                <span class="font-medium text-success-800">🎉 Você tem {{ $offersCount }} {{ $offersCount > 1 ? 'ofertas' : 'oferta' }}</span>
            </div>

            @foreach ($orderBumps as $index => $orderBump)
                @component('components.card', [
                    'custom' => 'p-4 space-y-3 border border-dashed border-neutral-200 shadow-md shadow-neutral-100 md:p-6',
                ])
                    <div class="flex gap-4 md:items-center md:gap-6">

                        <figure class="relative h-20 w-20">
                            <img
                                class="absolute h-full w-full rounded-md object-cover"
                                src="{{ $orderBump->product->featuredImageUrl }}"
                                alt="Imagem"
                                loading="lazy"
                            />
                        </figure>

                        <div class="flex-1">

                            <h4 class="font-semibold">{{ $orderBump->name }}</h4>
                            <p class="text-sm italic text-neutral-600">{{ $orderBump->description }}</p>

                            <div class="mt-1 flex items-center gap-3">
                                <del class="text-sm text-neutral-400">{{ $orderBump->product_offer->brazilianPrice }}</del>
                                <b class="textPrimaryColor">{{ $orderBump->brazilianPrice }}</b>
                            </div>

                        </div>

                    </div>

                    <label
                        class="button setButtonColor setButtonTextColor h-10 cursor-pointer gap-3 rounded-full"
                        for="addOrderbump{{ $orderBump->id }}"
                    >
                        <input
                            class="order-bump border-0"
                            id="addOrderbump{{ $orderBump->id }}"
                            type="checkbox"
                            value="{{ $orderBump->id }}"
                            name="orderBumps[{{ $index }}]"
                            data-productData="{{ json_encode($orderBump) }}"
                            onchange="this.checked ? checkout.addProduct(JSON.parse(this.dataset.productdata), true) : checkout.removeProduct(JSON.parse(this.dataset.productdata), true)"
                        />
                        Adicionar oferta
                    </label>
                @endcomponent
            @endforeach
        @endcomponent

    @endif
</div>
