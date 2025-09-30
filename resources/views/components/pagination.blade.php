<div class="mt-6 flex flex-col items-center justify-between gap-4 px-4 md:flex-row md:gap-0">

    <div class="text-sm text-neutral-400">Página {{ $currentPage }} de {{ $totalPages }} com {{ $totalItems }} registros.</div>

    <div class="flex items-center gap-2">

        <ul class="order-2 flex items-center">

            @for ($i = 1; $i <= $totalPages; $i++)
                <li>
                    @if($i == $currentPage)
                        <a
                                class="animate flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-bold text-white hover:bg-primary/80 hover:text-white"
                                href="?page={{$i}}"
                        >
                            {{$i}}
                        </a>
                    @else
                        <a
                                class="animate flex h-8 w-8 items-center justify-center rounded-lg text-sm text-neutral-400 hover:bg-neutral-200"
                                href="?page={{$i}}"
                        >
                            {{ $i }}
                        </a>
                    @endif
                </li>
            @endfor

        </ul>

        <a
            class="animate order-1 flex h-8 w-8 items-center justify-center rounded-lg text-sm text-neutral-400 hover:bg-neutral-200"
            href="#"
        >
            @include('components.icon', [
                'icon' => 'chevron_left',
                'custom' => 'text-xl',
            ])
        </a>

        <a
            class="animate order-3 flex h-8 w-8 items-center justify-center rounded-lg text-sm text-neutral-400 hover:bg-neutral-200"
            href="#"
        >
            @include('components.icon', [
                'icon' => 'chevron_right',
                'custom' => 'text-xl',
            ])
        </a>

    </div>

</div>
