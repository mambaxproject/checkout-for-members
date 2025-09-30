<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\{AbandonedCart, Checkout, Order, Product};
use App\Policies\{AbandonedCartPolicy, CheckoutPolicy, OrderPolicy, ProductPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AbandonedCart::class => AbandonedCartPolicy::class,
        Order::class         => OrderPolicy::class,
        Product::class       => ProductPolicy::class,
        Checkout::class      => CheckoutPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
