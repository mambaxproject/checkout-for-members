<!-- Page Header -->
<div class="page-header block justify-between md:flex">

    <h3 class="!text-defaulttextcolor flex items-center gap-3 text-[1.125rem] font-semibold px-4">

        @isset($icon)
            <i class="bi bi-{{ $icon }} {{ $customIcon ?? 'text-gray-600' }}"></i>
        @endisset

        {{ $page }}

    </h3>

</div>
<!-- Page Header Close -->
