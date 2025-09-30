<label for="{{ $id }}">{{ $label }}</label>
<div class="append">

    <input
        class="noScrollInput appearance-none text-center"
        name="{{ $name }}"
        id="{{ $id }}"
        min="{{ $min }}"
        max="{{ $max }}"
        value="{{ $min }}"
        type="number"
    >

    <button
        class="append-item-left w-12 hover:text-primary"
        onclick="decreaseValue()"
        type="button"
    >
        @include('components.icon', ['icon' => 'remove'])
    </button>
    <button
        class="append-item-right w-12 hover:text-primary"
        onclick="increaseValue()"
        type="button"
    >
        @include('components.icon', ['icon' => 'add'])
    </button>

</div>

@push('custom-script')
    <script>
        function increaseValue(min = {{ $min ?? '0' }}, max = {{ $max }}) {
            let quantityInput = document.getElementById('{{ $id }}');
            let currentValue = parseInt(quantityInput.value);

            if (currentValue < max) {
                quantityInput.value = currentValue + 1;
            }
        }

        function decreaseValue(min = {{ $min ?? '0' }}, max = {{ $max }}) {
            let quantityInput = document.getElementById('{{ $id }}');
            let currentValue = parseInt(quantityInput.value);

            if (currentValue > min) {
                quantityInput.value = currentValue - 1;
            }
        }
    </script>
@endpush
