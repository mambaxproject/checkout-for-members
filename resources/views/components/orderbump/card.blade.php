@component('components.card', ['custom' => 'p-6 space-y-4'])

    <div class="mx-auto w-fit rounded-full bg-success-100 px-6 py-2">
        <span class="font-medium text-success-800">🎉 Você tem 2 ofertas</span>
    </div>

    @component('components.card', ['custom' => 'p-4 space-y-3 border border-dashed border-neutral-200 shadow-md shadow-neutral-100 md:p-6'])
        <div class="flex items-center gap-4 md:gap-6">

            <img
                class="rounded-md"
                src="https://placehold.co/80"
                alt="Imagem"
                loading="lazy"
            />

            <div class="">

                <h4 class="font-semibold">Nome do produto</h4>
                <p class="text-sm text-neutral-600">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores, ipsam?</p>

                <div class="mt-1 flex items-center gap-3">
                    <del class="text-sm text-neutral-400">R$ 179,00</del>
                    <b class="textPrimaryColor">R$ 120,00</b>
                </div>

            </div>

        </div>

        <label
            class="button setButtonColor setButtonTextColor h-10 cursor-pointer gap-3 rounded-full"
            for="addOrderbump1"
        >
            <input
                class="border-0"
                id="addOrderbump1"
                type="checkbox"
            />
            Adicionar oferta
        </label>
    @endcomponent

    @component('components.card', ['custom' => 'p-4 space-y-3 border border-dashed border-neutral-200 shadow-md shadow-neutral-100 md:p-6'])
        <div class="flex items-center gap-4 md:gap-6">

            <img
                class="rounded-md"
                src="https://placehold.co/80"
                alt="Imagem"
                loading="lazy"
            />

            <div class="">

                <h4 class="font-semibold">Nome do produto</h4>
                <p class="text-sm italic text-neutral-600">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores, ipsam?</p>

                <div class="mt-1 flex items-center gap-3">
                    <del class="text-sm text-neutral-400">R$ 179,00</del>
                    <b class="textPrimaryColor">R$ 120,00</b>
                </div>

            </div>

        </div>

        <label
            class="button setButtonColor setButtonTextColor h-10 cursor-pointer gap-3 rounded-full"
            for="addOrderbump2"
        >
            <input
                class="border-0"
                id="addOrderbump2"
                type="checkbox"
            />
            Adicionar oferta
        </label>
    @endcomponent

@endcomponent
