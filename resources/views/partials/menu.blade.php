<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-semibold">
            {{ trans('panel.site_title') }}
            <small class="font-weight-light" style="font-size: 60%;">
                Admin
            </small>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li>
                    <select class="searchable-field form-control">

                    </select>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>

                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/users*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/permissions*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/users*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/permissions*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('shop_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.shops.index") }}" class="nav-link {{ request()->is("admin/shops") || request()->is("admin/shops/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-bars">

                            </i>
                            <p>
                                {{ trans('cruds.shop.title') }}
                            </p>
                        </a>
                    </li>
                @endcan

                @can('produto_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/products*") ? "menu-open" : "" }} {{ request()->is("admin/category-products*") ? "menu-open" : "" }} {{ request()->is("admin/sector-products*") ? "menu-open" : "" }} {{ request()->is("admin/type-products*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/products*") ? "active" : "" }} {{ request()->is("admin/category-products*") ? "active" : "" }} {{ request()->is("admin/sector-products*") ? "active" : "" }} {{ request()->is("admin/type-products*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-shopping-cart">

                            </i>
                            <p>
                                {{ trans('cruds.produto.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('product_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.products.index") }}" class="nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-ticket-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.product.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('category_product_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.category-products.index") }}" class="nav-link {{ request()->is("admin/category-products") || request()->is("admin/category-products/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-bars">

                                        </i>
                                        <p>
                                            {{ trans('cruds.categoryProduct.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('pedido_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/orders*") ? "menu-open" : "" }} {{ request()->is("admin/item-orders*") ? "menu-open" : "" }} {{ request()->is("admin/order-payments*") ? "menu-open" : "" }} {{ request()->is("admin/discount-orders*") ? "menu-open" : "" }} {{ request()->is("admin/discount-coupons*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/orders*") ? "active" : "" }} {{ request()->is("admin/item-orders*") ? "active" : "" }} {{ request()->is("admin/order-payments*") ? "active" : "" }} {{ request()->is("admin/discount-orders*") ? "active" : "" }} {{ request()->is("admin/discount-coupons*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-dollar-sign">

                            </i>
                            <p>
                                {{ trans('cruds.pedido.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('order_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.orders.index") }}" class="nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-hand-holding-usd">

                                        </i>
                                        <p>
                                            {{ trans('cruds.order.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('item_order_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.item-orders.index") }}" class="nav-link {{ request()->is("admin/item-orders") || request()->is("admin/item-orders/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-bars">

                                        </i>
                                        <p>
                                            {{ trans('cruds.itemOrder.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('order_payment_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.order-payments.index") }}" class="nav-link {{ request()->is("admin/order-payments") || request()->is("admin/order-payments/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.orderPayment.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('discount_order_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.discount-orders.index") }}" class="nav-link {{ request()->is("admin/discount-orders") || request()->is("admin/discount-orders/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.discountOrder.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('discount_coupon_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.discount-coupons.index") }}" class="nav-link {{ request()->is("admin/discount-coupons") || request()->is("admin/discount-coupons/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-tags">

                                        </i>
                                        <p>
                                            {{ trans('cruds.discountCoupon.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('affiliate_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.affiliates.index") }}" class="nav-link {{ request()->is("admin/affiliates") || request()->is("admin/affiliates/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon far fa-handshake">

                            </i>
                            <p>
                                {{ trans('cruds.affiliate.title') }}
                            </p>
                        </a>
                    </li>
                @endcan

                @can('admin_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/audit-logs*") ? "menu-open" : "" }} {{ request()->is("admin/genders*") ? "menu-open" : "" }} {{ request()->is("admin/states*") ? "menu-open" : "" }} {{ request()->is("admin/cities*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/audit-logs*") ? "active" : "" }} {{ request()->is("admin/genders*") ? "active" : "" }} {{ request()->is("admin/marital-statuses*") ? "active" : "" }} {{ request()->is("admin/states*") ? "active" : "" }} {{ request()->is("admin/cities*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.admin.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.audit-logs.index") }}" class="nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.auditLog.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('gender_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.genders.index") }}" class="nav-link {{ request()->is("admin/genders") || request()->is("admin/genders/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-genderless">

                                        </i>
                                        <p>
                                            {{ trans('cruds.gender.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('state_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.states.index") }}" class="nav-link {{ request()->is("admin/states") || request()->is("admin/states/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-bars">

                                        </i>
                                        <p>
                                            {{ trans('cruds.state.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('city_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.cities.index") }}" class="nav-link {{ request()->is("admin/cities") || request()->is("admin/cities/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-map-pin">

                                        </i>
                                        <p>
                                            {{ trans('cruds.city.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>
