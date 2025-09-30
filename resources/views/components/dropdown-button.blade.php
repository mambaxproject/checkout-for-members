<div class="{{ $customContainer ?? '' }} inline-block w-fit">

    <button
        class="{{ $customButton ?? '' }} flex items-center justify-center"
        id="{{ $id }}Button"
        data-dropdown-toggle="{{ $id }}"
        data-dropdown-placement="right"
        type="button"
    >
        @include('components.icon', ['icon' => $icon ?? 'more_vert'])
    </button>

    @component('components.dropdown', ['id' => $id])
        {{ $slot }}
    @endcomponent

</div>
