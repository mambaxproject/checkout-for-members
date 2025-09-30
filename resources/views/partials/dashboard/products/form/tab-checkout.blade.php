<div
    class="tab-content hidden"
    id="tab-checkout"
    data-tab="tab-checkout"
>

    <div class="space-y-4 md:space-y-10">

        @component('components.card', ['custom' => 'p-6 md:p-8'])
            <div class="space-y-8">

                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                    <h3>Checkout</h3>

                    <div class="flex items-center gap-2">

                        <button
                            class="button button-primary h-12 rounded-full"
                            data-modal-target="modalAddCheckout"
                            data-modal-toggle="modalAddCheckout"
                            type="button"
                        >
                            @include('components.icon', [
                                'icon' => 'add',
                                'custom' => 'text-xl',
                            ])
                            Adicionar novo
                        </button>

                    </div>

                </div>

                <div class="">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Modelos</th>
                                <th>Selecionar checkout</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($checkoutsShop as $checkout)
                                <tr>
                                    <td>{{ $checkout->name }}</td>
                                    <td>
                                        <form
                                            id="toggleForm_{{ $checkout->id }}"
                                            method="POST"
                                            action="{{ route('dashboard.products.updateCheckout', $product) }}"
                                        >
                                            @csrf
                                            @method('PUT')

                                            @component('components.toggle', [
                                                'id' => 'selectThemeCheckout_' . $checkout->id,
                                                'name' => 'checkout_id',
                                                'value' => $checkout->id,
                                                'contentEmpty' => true,
                                                'isChecked' => $product->checkout->id === $checkout->id,
                                            ])
                                            @endcomponent

                                        </form>
                                    </td>
                                    <td class="text-end">

                                        @component('components.dropdown-button', [
                                            'id' => 'dropdownMoreTableParticipations_' . $checkout->id,
                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                            'custom' => 'text-xl',
                                        ])
                                            <ul>
                                                <li>
                                                    <a
                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                        href="{{ route('dashboard.checkouts.edit', $checkout->id) }}"
                                                        title="Personalizar checkout"
                                                        target="_blank"
                                                    >
                                                        Personalizar
                                                    </a>
                                                </li>
                                                @unless($checkout->default)
                                                    <li>
                                                        <form
                                                            method="POST"
                                                            action="{{ route('dashboard.checkouts.destroy', $checkout->id) }}"
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
                                                @endunless
                                            </ul>
                                        @endcomponent

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        @endcomponent

            <button
                    class="button button-primary mx-auto h-12 w-full max-w-xs rounded-full"
                    type="button"
                    onclick="document.location.href = document.location.pathname + '#tab=tab-participations';  window.location.reload();"
            >
                Salvar
            </button>

    </div>

</div>

@push('floating')
    @component('components.modal', [
        'id' => 'modalAddCheckout',
        'title' => 'Adicionar novo tema de checkout',
    ])
        <form
            action="{{ route('dashboard.products.storeCheckout', $product) }}"
            method="POST"
        >
            @csrf

            <p class="mb-4">
                Personalize sua página de checkout para garantir a melhor experiência para sua clientela
            </p>

            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-12">
                    <label for="name">Nome do Checkout</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        class="{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        minlength="3"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-xs italic text-danger-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12">
                    <div
                        class="flex flex-col"
                        id="toggle-component-defaultCheckout"
                    >

                        <label
                            class="mb-0 flex w-fit cursor-pointer items-center gap-4"
                            for="defaultCheckout"
                        >

                            <input
                                type="checkbox"
                                class="peer hidden"
                                id="defaultCheckout"
                                name="default"
                            />

                            <div class="animate relative h-6 w-[44px] rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full"></div>

                            <div class="flex-1">
                                Definir como checkout padrão
                            </div>

                        </label>

                    </div>

                </div>

            </div>

            <button
                class="button button-primary mt-6 h-12 w-full rounded-full"
                type="submit"
            >
                Salvar
            </button>

        </form>

    @endcomponent
@endpush

@push('custom-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('[id^="selectThemeCheckout_"]');
            const loadingDiv = document.getElementById('loading');

            toggles.forEach((toggle) => {
                toggle.addEventListener('change', function() {

                    // Exibir o loader
                    if (loadingDiv) {
                        loadingDiv.style.display = 'flex';
                    }

                    const formId = toggle.id.replace('selectThemeCheckout_', 'toggleForm_');
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
