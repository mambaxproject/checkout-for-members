<div class="slider-container">
    <input
        class="slider w-full"
        id="{{ $id ?? '' }}"
        min="{{ $min ?? 0 }}"
        max="{{ $max ?? 100 }}"
        step="{{ $step ?? 1 }}"
        value="{{ $value ?? 0 }}"
        name="{{ $name ?? '' }}"
        type="range"
    />
    <span class="value">{{ $value ?? 0 }}</span>
</div>

@push('custom-script')
    <script>
        document.querySelectorAll('.slider-container').forEach((container) => {
            const sliderEl = container.querySelector('.slider');
            const sliderValue = container.querySelector('.value');

            sliderEl.addEventListener('input', (event) => {
                const tempSliderValue = event.target.value;

                sliderValue.textContent = tempSliderValue;

                const progress = (tempSliderValue / sliderEl.max) * 100;

                sliderEl.style.background = `linear-gradient(to right, #f50 ${progress}%, #ccc ${progress}%)`;
            });
        });
    </script>
@endpush
