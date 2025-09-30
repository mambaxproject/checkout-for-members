<div
    class="fixed left-0 top-0 z-50 hidden h-full w-full overflow-y-auto"
    aria-modal="false"
    aria-hidden="true"
    id={{ $id }}
>
    <div class="max-h-full w-full max-w-2xl px-4 md:px-0">
        <div class="md:py-20">
            <div class="w-full space-y-6 rounded-3xl bg-white px-6 pb-6 pt-4 shadow-xl">

                <div class="flex items-center gap-6">

                    <h3 class="{{ $title_style ?? '' }} mr-auto">{{ $title }}</h3>

                    <button
                        type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-lg hover:bg-neutral-100"
                        data-modal-hide={{ $id }}
                    >
                        @include('components.icon', [
                            'icon' => 'close',
                            'custom' => 'text-xl',
                        ])
                    </button>

                </div>

                <div class="">{{ $slot }}</div>

            </div>
        </div>
    </div>
</div>
