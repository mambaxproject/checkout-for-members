<div
    id="{{ $accordionItemId }}"
    @class(['hs-accordion accordion-item', 'active' => isset($open)])
>

    <button
        class="hs-accordion-toggle accordion-button hs-accordion-active:bg-slate-500 hs-accordion-active:text-white flex w-full items-center justify-between p-4"
        aria-controls="{{ $accordionItemCollapseId }}"
        type="button"
    >

        {{ $accordionItemTitle }}
        <i class="bx bx-chevron-down hs-accordion-active:rotate-180 text-2xl transition-all duration-300 ease-in-out"></i>

    </button>

    <div
        id="{{ $accordionItemCollapseId }}"
        @class([
            'hs-accordion-content accordion-collapse w-full transition-[height] duration-300',
            'hidden' => empty($open),
        ])
    >

        {{ $slot }}

    </div>

</div>
