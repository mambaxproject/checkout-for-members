<button
    class="ti-btn ti-btn-wave {{ $btnCustom ?? 'ti-btn-primary' }} mb-0"
    data-hs-overlay="#{{ $id }}"
    type="button"
>

    <i class="bx bx-{{ $btnIcon ?? 'filter-alt' }}"></i>
    {{ $btnTitle }}

</button>

<div
    class="hs-overlay ti-offcanvas ti-offcanvas-right !z[105] hidden"
    id="{{ $id }}"
>

    <div class="ti-offcanvas-header">

        <h6 class="ti-offcanvas-title">{{ $drawerTitle }}</h6>

        <button
            class="ti-btn ti-btn-small ti-btn-wave hover:bg-slate-100 !px-2"
            data-hs-overlay="#{{ $id }}"
            type="button"
        >
            <i class="bx bx-x text-slate-400 text-xl"></i>
        </button>

    </div>

    <div class="ti-offcanvas-body">{{ $slot }}</div>

</div>
