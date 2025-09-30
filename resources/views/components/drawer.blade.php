<div
    id="{{ $id }}"
    class="{{ $custom ?? 'max-w-md' }} drawer fixed right-0 top-0 z-[60] !m-0 h-full min-h-screen w-full translate-x-full overflow-y-auto bg-white p-4 transition-transform"
>
    <div class="relative p-6">

        <button
            class="closeButton absolute right-0 top-0 hover:text-danger-600"
            data-drawer-hide="{{ $id }}"
            type="button"
        >
            @include('components.icon', ['icon' => 'close'])
        </button>

        <div class="">
            <h3 class="titleDrawer mb-6">{{ $title }}</h3>
            <div class="">
                {{ $slot }}
            </div>
        </div>

    </div>
</div>
