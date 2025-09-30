<div class="{{ $customCard ?? '' }} box mb-0">

    @isset($cardTitle)
        <div class="box-header justify-between">
            <div class="box-title">{{ $cardTitle }}</div>
        </div>
    @endisset

    <div class="{{ $customCardBody ?? '' }} p-4 md:p-6">{{ $slot }}</div>

</div>
