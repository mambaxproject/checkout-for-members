<!-- Header Profile -->
<div class="header-element hs-dropdown ti-dropdown !items-center px-2 [--placement:bottom-left] md:!px-[0.65rem]">

    <button
        id="dropdown-profile"
        type="button"
        class="hs-dropdown-toggle ti-dropdown-toggle me-0 flex-shrink-0 !gap-2 !rounded-full !border-0 !p-0 align-middle text-xs !shadow-none !shadow-transparent sm:me-2"
    >

        <img
            class="inline-block rounded-full"
            src="{{ user()->avatar_url }}"
            width="32"
            height="32"
            alt="{{ user()->name }}"
        >

        <div class="dropdown-profile hidden md:block">
            <p class="mb-0 text-[0.813rem] font-semibold leading-none text-[#536485]">
                {{ user()->name }}
            </p>
        </div>

        <i class="ri-arrow-down-s-line text-xl"></i>

    </button>

    <div
        class="hs-dropdown-menu ti-dropdown-menu border-defaultborder main-header-dropdown header-profile-dropdown dropdown-menu-end !-mt-3 hidden w-[11rem] overflow-hidden border-0 !p-0 pt-0"
        aria-labelledby="dropdown-profile"
    >

        <ul class="text-defaulttextcolor font-medium dark:text-[#8c9097] dark:text-white/50">

            <!--
            <li>
                <a
                    class="ti-dropdown-item !inline-flex w-full !gap-x-0 !p-[0.65rem] !text-[0.8125rem]"
                    href="#"
                >
                    <i class="ti ti-user-circle me-2 text-[1.125rem] opacity-[0.7]"></i>
                    Profile
                </a>
            </li>
            -->

            <li>
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="ti-dropdown-item !inline-flex w-full !gap-x-0 !p-[0.65rem] !text-[0.8125rem]"
                    >
                        <i class="ti ti-logout me-2 text-[1.125rem] opacity-[0.7]"></i>
                        Sair
                    </button>
                </form>
            </li>

        </ul>

    </div>

</div>
<!-- End Header Profile -->
