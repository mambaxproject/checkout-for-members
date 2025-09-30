<?php

use App\Http\Controllers\Api\V1\Public\CheckoutApiController;
use App\Http\Controllers\Auth\{ExternalLoginApiController};
use App\Http\Middleware\{HandleLinkOfferAffiliate, HandleLinkOfferCoproducer, VerifyCsrfToken};
use App\Models\{Affiliate, Product};
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::redirect('/', '/login');

Route::get('health', [HealthCheckResultsController::class, '__invoke']);

require __DIR__ . '/socialLogin.php';
require __DIR__ . '/members.php';

Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');

Auth::routes();

Route::post('auth/external-login', ExternalLoginApiController::class)
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('auth.externalLogin');

Route::post('auth/v2/external-login', \App\Http\Controllers\Auth\V2\ExternalLoginApiController::class)
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('auth.v2.externalLogin');

require __DIR__ . '/admin.php';

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
        Route::post('profile/two-factor', 'ChangePasswordController@toggleTwoFactor')->name('password.toggleTwoFactor');
    }
});

Route::group(['namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Two Factor Authentication
    if (file_exists(app_path('Http/Controllers/Auth/TwoFactorController.php'))) {
        Route::get('two-factor', 'TwoFactorController@show')->name('twoFactor.show');
        Route::post('two-factor', 'TwoFactorController@check')->name('twoFactor.check');
        Route::get('two-factor/resend', 'TwoFactorController@resend')->name('twoFactor.resend');
    }
});

Route::group(['namespace' => 'Dashboard', 'as' => 'dashboard.', 'prefix' => 'dashboard', 'middleware' => ['auth', '2fa', 'accessDashboard']], function () {
    Route::get('/', 'HomeController@index')->name('home.index');

    Route::controller(\App\Http\Controllers\Dashboard\UserController::class)->group(function () {
        Route::get('users', 'index')->name('users.index');
        Route::get('users/profile', 'profile')->name('users.profile');
        Route::put('users/update', 'update')->name('users.update');
    });

    Route::put('products/{product}/toogleStatus', 'ProductController@toogleStatus')->name('products.toogleStatus');
    Route::put('products/{product}/updateSituation', 'ProductController@updateSituation')->name('products.updateSituation');
    Route::put('products/{product}/disable', 'ProductController@disable')->name('products.disable');
    Route::put('products/{product}/enable', 'ProductController@enable')->name('products.enable');
    Route::post('products/{product}/storeCheckout', 'ProductController@storeCheckout')->name('products.storeCheckout');
    Route::put('products/{product}/updateCheckout', 'ProductController@updateCheckout')->name('products.updateCheckout');
    Route::post('products/checkUniqueProductName', 'ProductController@checkUniqueProductName')->name('products.checkUniqueProductName');
    Route::resource('utmLink', \App\Http\Controllers\Dashboard\ProductUtmLinkController::class)->except(['index', 'edit', 'show']);
    Route::resource('products', \App\Http\Controllers\Dashboard\ProductController::class)->except(['edit']);
    Route::get('products/{productUuid}/edit', [\App\Http\Controllers\Dashboard\ProductController::class, 'edit'])->name('products.edit');

    Route::resource('coupon-discounts', \App\Http\Controllers\Dashboard\CouponDiscountController::class);

    Route::controller(\App\Http\Controllers\Dashboard\FileUploadChunckingController::class)->group(function () {
        Route::post('file-upload-chunking', 'uploadChunk')->name('fileUploadChunking');
    });

    Route::controller(\App\Http\Controllers\Dashboard\DomainController::class)->group(function () {
        Route::post('domains/{product}/store', 'store')->name('domains.store');
        Route::delete('domains/{domain}/destroy', 'destroy')->name('domains.destroy');
        Route::post('domains/{domain}/checkDns', 'checkDns')->name('domains.checkDns');
    });

    Route::controller(\App\Http\Controllers\Dashboard\OrderController::class)->group(function () {
        Route::post('orders/{order}/refund', 'refund')->name('orders.refund');
        Route::get('orders', 'index')->name('orders.index');
        Route::get('orders/{orderUuid}/show', 'show')->name('orders.show');
    });

    Route::resource('suitpay-crm-integration', \App\Http\Controllers\Dashboard\SuitpayCRMIntegrationController::class);
    Route::controller(\App\Http\Controllers\Dashboard\SuitpayCRMIntegrationController::class)->group(function () {
        Route::post('suitpay-crm-integration/active-crm', 'activeCRM')->name('suitpay-crm-integration.activeCRM');
        Route::post('suitpay-crm-integration/{suitpayCrmIntegration}/disable', 'updateStatus')->name('suitpay-crm-integration.updateStatus');
    });

    Route::resource('utm-reports', \App\Http\Controllers\Dashboard\UTMReportsController::class);

    Route::controller(\App\Http\Controllers\Dashboard\ReportCommissioningController::class)->group(function () {
        Route::get('reports/commissioning', 'index')->name('reports.commissioning.index');
    });

    Route::put('affiliates/{affiliate}/approve', 'AffiliateController@approve')->name('affiliates.approve');
    Route::put('affiliates/{affiliate}/cancel', 'AffiliateController@cancel')->name('affiliates.cancel');
    Route::put('affiliates/{affiliate}/reactive', 'AffiliateController@reactive')->name('affiliates.reactive');
    Route::get('affiliates/{affiliate}/links/{product}', 'AffiliateController@linksProductToAffiliate')->name('affiliates.linksProductToAffiliate');
    Route::get('affiliates/products', 'AffiliateController@productsAffiliate')->name('affiliates.productsAffiliate');
    Route::put('affiliates/products/{product}/pixel', 'AffiliateController@updateProductPixelAffiliate')->name('affiliates.productsPixelAffiliate');
    Route::resource('affiliates', \App\Http\Controllers\Dashboard\AffiliateController::class);

    Route::controller(\App\Http\Controllers\Dashboard\SubscriptionController::class)->group(function () {
        Route::get('subscriptions', 'index')->name('subscriptions.index');
        Route::get('subscriptions/{orderUuid}', 'show')->name('subscriptions.show');
        Route::post('subscriptions/{order}/send-link-update-credit-card-customer', 'sendLinkUpdateCreditCardCustomer')->name('subscriptions.sendLinkUpdateCreditCardCustomer');
        Route::post('subscriptions/{order}/send-link-update-offer-customer', 'sendLinkUpdateOfferCustomer')->name('subscriptions.sendLinkUpdateOfferCustomer');
        Route::post('subscriptions/{order}/charge-retry', 'chargeRetry')->name('subscriptions.chargeRetry');
    });

    Route::resource('abandoned-carts', \App\Http\Controllers\Dashboard\AbandonedCartController::class)
        ->only(['index', 'show']);

    Route::resource('webhooks', \App\Http\Controllers\Dashboard\WebhookController::class)
        ->only(['store', 'destroy', 'update']);

    Route::resource('api', \App\Http\Controllers\Dashboard\ApiController::class)
        ->only(['store', 'destroy']);

    Route::resource('apps', \App\Http\Controllers\Dashboard\AppController::class)
        ->only(['index', 'show', 'update']);

    // Route::resource('members', \App\Http\Controllers\Dashboard\MemberController::class)
    //     ->only(['index', 'show']);



    Route::resource('telegram', \App\Http\Controllers\Dashboard\TelegramGroupController::class);

    Route::controller(\App\Http\Controllers\Dashboard\NotificationController::class)->group(function () {
        Route::get('notification/{services}', 'index')->name('notification.index');
        Route::get('notification/connect/whatsapp', 'connectWhatsapp')->name('notification.connection');
        Route::get('notification/products/available', 'getProductsAvailable')->name('notification.productsAvailable');
        Route::get('notification/store/action', 'store')->name('notification.store');
        Route::post('notification/create', 'create')->name('notification.create');
        Route::get('notification/edit/{actionId}', 'edit')->name('notification.edit');
        Route::post('notification/update', 'update')->name('notification.update');
        Route::put('notification/changeStatus/{actionId}', 'changeStatus')->name('notification.changeStatus');
        Route::post('notification/duplicate', 'duplicate')->name('notification.duplicate');
        Route::delete('notification/disconnect/whatsapp', 'disconnectWhatsapp')->name('notification.disconnectWhatsapp');
    });

    Route::controller(\App\Http\Controllers\Dashboard\MarketplaceController::class)->group(function () {
        Route::get('marketplace', 'index')->name('marketplace.index');
        Route::post('joinAffiliate/{product}', 'joinAffiliate')->name('marketplace.joinAffiliate');
    });

    Route::controller(\App\Http\Controllers\Dashboard\ReferenceController::class)->group(function () {
        Route::get('reference', 'index')->name('reference.index');
    });

    Route::controller(\App\Http\Controllers\Dashboard\ReportController::class)->group(function () {
        Route::get('reports', 'index')->name('reports.index');
        Route::get('reports/metrics-subscriptions', 'metricsSubscriptions')->name('reports.metricsSubscriptions');
    });

    Route::resource('checkouts', \App\Http\Controllers\Dashboard\CheckoutController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);

    Route::delete('checkouts/{checkout}/destroy', 'CheckoutController@destroy')
        ->missing(function () {
            return back()
                ->withFragment('tab=tab-checkout')
                ->with('error', 'Não é possível excluir o checkout.');
        })
        ->name('checkouts.destroy');

    Route::controller(\App\Http\Controllers\Dashboard\CoproducerController::class)->group(function () {
        Route::post('coproducers/{product}/store', 'store')->name('coproducers.store');
        Route::put('coproducers/{coproducer}/update', 'update')->name('coproducers.update');
        Route::delete('coproducers/{coproducer}/destroy', 'destroy')->name('coproducers.destroy');
        Route::get('coproducers/{coproducer}/links/{product}', 'CoproducerController@linksProductToCoproducer')->name('coproducers.linksProductToCoproducer');
        Route::put('coproducers/{coproducer}/update-situation', 'CoproducerController@updateSituation')->name('coproducers.updateSituation');
        Route::get('coproducers/products', 'CoproducerController@productsCoproducer')->name('coproducers.productsCoproducer');
    });
});

Route::group(['namespace' => 'Checkout', 'as' => 'checkout.'], function () {
    Route::controller(\App\Http\Controllers\Checkout\ProductController::class)->group(function () {
        Route::get('checkout/{product?}', function (Product $product) {
            return view('checkout.products.checkoutBuilder', compact('product'));
        })->name('checkout.index');

        Route::get('{product:code}', 'product')->name('checkout.product')
            ->middleware([HandleLinkOfferAffiliate::class, HandleLinkOfferCoproducer::class]);

        Route::get('checkout/thanks/{order_hash}', 'thanks')->name('checkout.thanks');

        Route::get('checkout/pay', 'pay');
    });
});

Route::group(['namespace' => 'Affiliate', 'as' => 'affiliate.'], function () {
    Route::controller(\App\Http\Controllers\Affiliate\JoinController::class)->group(function () {
        Route::get('affiliate/{product}/join', 'join')->name('join');
        Route::post('affiliate/{product}/register', 'register')->name('register');
    });

    Route::get('r/{code}', function () {
        // Cria um cookie no cliente com o código do afiliado, então redireciona para a URL externa

        $affiliate = Affiliate::whereCode(request('code'))->first();
        $product   = $affiliate->product;

        setcookie('afflt_code_' . $product->code, $affiliate->code, time() + 3600, '/');

        return redirect()->away($affiliate->product->getValueSchemalessAttributes('externalSalesLink'));
    })->name('redirectExternalSalesLink');
});

Route::group(['namespace' => 'Coproducer', 'as' => 'coproducer.'], function () {
    Route::controller(\App\Http\Controllers\Coproducer\JoinController::class)->group(function () {
        Route::get('coproducer/{coproducer}/join', 'join')->name('join');
        Route::post('coproducer/{coproducer}/register', 'register')->name('register');
    });
});

Route::group(['namespace' => 'Public', 'as' => 'public.'], function () {

    Route::post('checkout/{order}/payUpSell/{upSell}', [CheckoutApiController::class, 'payUpSell'])
        ->name('checkout.payUpSell');

    Route::controller(\App\Http\Controllers\Public\Subscription\UpdateController::class)->group(function () {
        Route::get('subscription/{order_hash}', 'show')->name('subscription.show');
        Route::post('subscription/{order}/update-card', 'updateCard')->name('subscription.updateCard');
        Route::get('subscription/{order_hash}/edit-offer/{product}', 'editOffer')->name('subscription.editOffer');
        Route::post('subscription/{order}/update-offer', 'updateOffer')->name('subscription.updateOffer');
    });
});
