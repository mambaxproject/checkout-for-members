<div class="hs-dropdown ti-dropdown float-end [--strategy:fixed]">

    <a
        class="!text-defaulttextcolor bg-light border-light flex h-[1.75rem] w-[1.75rem] items-center justify-center rounded-sm !px-2 !py-1 !text-[0.8rem] !font-medium shadow-none"
        aria-expanded="false"
        data-hs-dropdown-position="bottom-end"
        href="javascript:void(0);"
    >
        <i class="bx bx-{{ $icon ?? 'dots-vertical-rounded' }}"></i>
    </a>

    <ul class="{{ $custom ?? '' }} hs-dropdown-menu ti-dropdown-menu hidden">
        {{ $slot }}
    </ul>

</div>
