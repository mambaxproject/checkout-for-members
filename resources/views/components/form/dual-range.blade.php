<div class="slider-container">

    <div class="relative flex h-10 items-center">

        <div class="slider-track absolute z-10 h-[2px] w-full"></div>

        <input
            class="slider-1 pointer-events-none absolute"
            name={{ $name ?? '' }}
            min={{ $min ?? 0 }}
            max={{ $max ?? 100 }}
            value={{ $slide1Value ?? 0 }}
            oninput="slideOne()"
            type="range"
        >

        <input
            class="slider-2 pointer-events-none absolute"
            name={{ $name ?? '' }}
            min={{ $min ?? 0 }}
            max={{ $max ?? 100 }}
            value={{ $slide2Value ?? 0 }}
            oninput="slideTwo()"
            type="range"
        >

    </div>

    <div class="relative flex items-center justify-between">

        <div class="pointer-events-none absolute left-0">

            <span
                class="w-fit text-xs font-semibold"
                id="range1"
            >
                0
            </span>

        </div>

        <div class="pointer-events-none absolute right-0">

            <span
                class="text-xs font-semibold"
                id="range2"
            >
                0
            </span>

        </div>

    </div>

</div>

@push('custom-script')
    <script>
        window.onload = function() {
            document.querySelectorAll('.slider-container').forEach(container => {
                const sliderOne = container.querySelector(".slider-1");
                const sliderTwo = container.querySelector(".slider-2");
                const displayValOne = container.querySelector("#range1");
                const displayValTwo = container.querySelector("#range2");
                const sliderTrack = container.querySelector(".slider-track");
                const sliderMaxValue = sliderOne.max;

                let minGap = 0;

                function slideOne() {
                    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                        sliderOne.value = parseInt(sliderTwo.value) - minGap;
                    }
                    displayValOne.textContent = sliderOne.value;
                    fillColor();
                }

                function slideTwo() {
                    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                        sliderTwo.value = parseInt(sliderOne.value) + minGap;
                    }
                    displayValTwo.textContent = sliderTwo.value;
                    fillColor();
                }

                function fillColor() {
                    let percent1 = parseInt((sliderOne.value / sliderMaxValue) * 100);
                    let percent2 = parseInt((sliderTwo.value / sliderMaxValue) * 100);

                    sliderTrack.style.background = `linear-gradient(to right, transparent ${percent1}% , #33cc33 ${percent1}% , #33cc33 ${percent2}%, transparent ${percent2}%)`;
                }

                sliderOne.addEventListener('input', slideOne);
                sliderTwo.addEventListener('input', slideTwo);

                slideOne();
                slideTwo();
            });
        }
    </script>
@endpush
