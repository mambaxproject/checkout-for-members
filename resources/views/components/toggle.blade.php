<div
    class="flex flex-col"
    id="toggle-component-{{ $id }}"
>
    <label
        class="mb-0 flex w-fit cursor-pointer items-center gap-4"
        for="{{ $id }}"
    >

        <input
            type="{{ $type ?? 'checkbox' }}"
            class="{{ $customInput ?? '' }} peer hidden"
            id="{{ $id }}"
            name="{{ $name ?? '' }}"
            value="{{ $value ?? '' }}"
            @checked($isChecked ?? false)
            {{ $action ?? '' }}
        >

        <div class="animate relative h-6 w-[44px] {{ $customToggle ?? '' }} rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full"></div>

        @isset($label)
            <div class="flex-1">{{ $label }}</div>
        @endisset

    </label>

    
    @if (!isset($contentEmpty))
        <div class="toggleContent mt-4 hidden">{{ $slot ?? '' }}</div>
    @endif

</div>
