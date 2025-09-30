<!--Header Notifictaion -->
<div class="header-element notifications-dropdown header-notification hs-dropdown ti-dropdown !hidden px-2 py-[1rem] [--placement:bottom-left] md:!block md:px-[0.65rem]">

    <button
        id="dropdown-notification"
        type="button"
        class="hs-dropdown-toggle ti-dropdown-toggle relative flex-shrink-0 !rounded-full !border-0 !p-0 align-middle text-xs !shadow-none"
    >

        <i class="bx bx-bell header-link-icon text-[1.125rem]"></i>
        <span class="absolute -top-[0.25rem] end-0 -me-[0.6rem] flex h-5 w-5">
            <span class="animate-slow-ping bg-secondary/40 absolute -start-[2px] -top-[2px] inline-flex h-full w-full rounded-full opacity-75"></span>
            <span
                class="bg-secondary relative inline-flex h-[14.7px] w-[14px] items-center justify-center rounded-full text-[0.625rem] text-white"
                id="notification-icon-badge"
            >
                5
            </span>
        </span>

    </button>

    <div
        class="main-header-dropdown hs-dropdown-menu ti-dropdown-menu border-defaultborder !m-0 !-mt-3 hidden !w-[22rem] border-0 bg-white !p-0"
        aria-labelledby="dropdown-notification"
    >

        <div class="ti-dropdown-header !m-0 flex items-center justify-between !bg-transparent !p-4">
            <p class="text-defaulttextcolor mb-0 text-[1.0625rem] font-semibold dark:text-[#8c9097] dark:text-white/50">Notifications</p>
            <span
                class="bg-secondary/10 text-secondary rounded-sm px-[0.45rem] py-[0.25rem/2] text-[0.75em] font-[600]"
                id="notifiation-data"
            >
                5 Unread
            </span>
        </div>

        <div class="dropdown-divider"></div>

        <ul
            class="end-0 !m-0 list-none !p-0"
            id="header-notification-scroll"
        >

            <li class="ti-dropdown-item dropdown-item !block">
                <div class="flex items-start">
                    <div class="pe-2">
                        <span class="inline-flex !h-[2.5rem] !w-[2.5rem] items-center justify-center !rounded-[50%] !bg-primary/10 !text-[0.8rem] !leading-[2.5rem] text-primary"><i class="ti ti-gift text-[1.125rem]"></i></span>
                    </div>
                    <div class="flex grow items-center justify-between">
                        <div>
                            <p class="text-defaulttextcolor mb-0 text-[0.8125rem] font-semibold dark:text-white"><a href="notifications.html">Your Order Has Been Shipped</a></p>
                            <span class="header-notification-text text-[0.75rem] font-normal text-[#8c9097] dark:text-white/50">Order No: 123456 Has Shipped To Your Delivery Address</span>
                        </div>
                        <div>
                            <a
                                aria-label="anchor"
                                href="javascript:void(0);"
                                class="dropdown-item-close1 me-1 min-w-fit text-[#8c9097] dark:text-white/50"
                            >
                                <i class="ti ti-x text-[1rem]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="ti-dropdown-item dropdown-item !block">
                <div class="flex items-start">
                    <div class="pe-2">
                        <span class="text-secondary bg-secondary/10 inline-flex !h-[2.5rem] !w-[2.5rem] items-center justify-center rounded-[50%] !text-[0.8rem] !leading-[2.5rem]"><i class="ti ti-discount-2 text-[1.125rem]"></i></span>
                    </div>
                    <div class="flex grow items-center justify-between">
                        <div>
                            <p class="text-defaulttextcolor mb-0 text-[0.8125rem] font-semibold dark:text-white"><a href="notifications.html">Discount Available</a></p>
                            <span class="header-notification-text text-[0.75rem] font-normal text-[#8c9097] dark:text-white/50">Discount Available On Selected Products</span>
                        </div>
                        <div>
                            <a
                                aria-label="anchor"
                                href="javascript:void(0);"
                                class="dropdown-item-close1 me-1 min-w-fit text-[#8c9097] dark:text-white/50"
                            >
                                <i class="ti ti-x text-[1rem]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="ti-dropdown-item dropdown-item !block">
                <div class="flex items-start">
                    <div class="pe-2">
                        <span class="text-pinkmain bg-pinkmain/10 inline-flex !h-[2.5rem] !w-[2.5rem] items-center justify-center rounded-[50%] !text-[0.8rem] !leading-[2.5rem]"><i class="ti ti-user-check text-[1.125rem]"></i></span>
                    </div>
                    <div class="flex grow items-center justify-between">
                        <div>
                            <p class="text-defaulttextcolor mb-0 text-[0.8125rem] font-semibold dark:text-white"><a href="notifications.html">Account Has Been Verified</a></p>
                            <span class="header-notification-text text-[0.75rem] font-normal text-[#8c9097] dark:text-white/50">Your Account Has Been Verified Sucessfully</span>
                        </div>
                        <div>
                            <a
                                aria-label="anchor"
                                href="javascript:void(0);"
                                class="dropdown-item-close1 me-1 min-w-fit text-[#8c9097] dark:text-white/50"
                            >
                                <i class="ti ti-x text-[1rem]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="ti-dropdown-item dropdown-item !block">
                <div class="flex items-start">
                    <div class="pe-2">
                        <span class="text-warning bg-warning/10 inline-flex !h-[2.5rem] !w-[2.5rem] items-center justify-center rounded-[50%] !text-[0.8rem] !leading-[2.5rem]"><i class="ti ti-circle-check text-[1.125rem]"></i></span>
                    </div>
                    <div class="flex grow items-center justify-between">
                        <div>
                            <p class="text-defaulttextcolor mb-0 text-[0.8125rem] font-semibold dark:text-white"><a href="notifications.html">Order Placed <span class="text-warning">ID: #1116773</span></a></p>
                            <span class="header-notification-text text-[0.75rem] font-normal text-[#8c9097] dark:text-white/50">Order Placed Successfully</span>
                        </div>
                        <div>
                            <a
                                aria-label="anchor"
                                href="javascript:void(0);"
                                class="dropdown-item-close1 me-1 min-w-fit text-[#8c9097] dark:text-white/50"
                            >
                                <i class="ti ti-x text-[1rem]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="ti-dropdown-item dropdown-item !block">
                <div class="flex items-start">
                    <div class="pe-2">
                        <span class="text-success bg-success/10 inline-flex !h-[2.5rem] !w-[2.5rem] items-center justify-center rounded-[50%] !text-[0.8rem] !leading-[2.5rem]"><i class="ti ti-clock text-[1.125rem]"></i></span>
                    </div>
                    <div class="flex grow items-center justify-between">
                        <div>
                            <p class="text-defaulttextcolor mb-0 text-[0.8125rem] font-semibold dark:text-white"><a href="notifications.html">Order Delayed <span class="text-success">ID: 7731116</span></a></p>
                            <span class="header-notification-text text-[0.75rem] font-normal text-[#8c9097] dark:text-white/50">Order Delayed Unfortunately</span>
                        </div>
                        <div>
                            <a
                                aria-label="anchor"
                                href="javascript:void(0);"
                                class="dropdown-item-close1 me-1 min-w-fit text-[#8c9097] dark:text-white/50"
                            >
                                <i class="ti ti-x text-[1rem]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

        </ul>

        <div class="empty-header-item1 mt-2 border-t p-4">
            <div class="grid">
                <a
                    href="notifications.html"
                    class="ti-btn ti-btn-primary-full !m-0 w-full p-2"
                >
                    View All
                </a>
            </div>
        </div>

        <div class="empty-item1 hidden p-[3rem]">
            <div class="text-center">
                <span class="avatar !bg-secondary/10 !text-secondary !h-[4rem] !w-[4rem] !rounded-full !leading-[4rem]">
                    <i class="ri-notification-off-line text-[2rem]"></i>
                </span>
                <h6 class="text-defaulttextcolor mt-3 text-[1rem] font-semibold dark:text-white">No New Notifications</h6>
            </div>
        </div>

    </div>

</div>
<!--End Header Notifictaion -->
