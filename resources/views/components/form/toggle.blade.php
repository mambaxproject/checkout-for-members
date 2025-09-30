@props([
    'id',
    'name',
    'label' => '',
    'checked' => false,
    'type' => 'checkbox', // ou 'radio'
    'value' => '1',
])

<label
    class="flex w-fit cursor-pointer items-center gap-3"
    for="{{ $id }}"
>

    <div class="relative">
        <input
            class="peer sr-only"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            type="{{ $type }}"
            {{ $checked ? 'checked' : '' }}
        />

        <div class="h-6 w-11 rounded-full bg-gray-300 transition-colors peer-checked:bg-primary"></div>
        <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></div>

    </div>

    @if ($label)
        <span class="text-sm text-gray-700">{{ $label }}</span>
    @endif

</label>
