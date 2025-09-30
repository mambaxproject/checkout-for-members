<form
    action=""
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            @component('components.toggle', [
                'id' => 'toggleGoogleAnalyticsActive',
                'label' => 'Ativar',
                'contentEmpty' => false,
                'name' => 'status',
                'value' => $appsShopUser['google-analytics']['dataShopUser']['status'] ?? \App\Enums\StatusEnum::ACTIVE->name,
                'isChecked' => ($appsShopUser['google-analytics']['dataShopUser']['status'] ?? '') === \App\Enums\StatusEnum::ACTIVE->name
            ])
            @endcomponent
        </div>

        <div class="col-span-12">
            <label for="tracking_id">
                Google Analytics Tracking ID:
                <span class="required">*</span>
            </label>
            <input
                type="text"
                id="tracking_id"
                name="tracking_id"
                value="{{ $appsShopUser['google-analytics']['dataShopUser']['tracking_id'] ?? '' }}"
                placeholder="digite o google analytics tracking ID"
                required
            />

            <p class="text-xs text-gray-400 mt-1">
                Exemplo: G-XXXXXXXXXX
            </p>

            <p class="mt-4">
                <img src="{{ asset('images/dashboard/apps/tracking-code-google-analytics.png') }}"
                     alt="código trackemaneto google analytics"
                     loading="lazy"
                />
            </p>
        </div>
    </div>

    <button
        class="button button-primary mt-8 h-12 w-full rounded-full"
        type="submit"
    >
        Salvar
    </button>
</form>