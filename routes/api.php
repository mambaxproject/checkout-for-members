<?php

use App\Http\Controllers\Dashboard\TelegramGroupController;
use App\Http\Controllers\Api\V1\Auth\{AuthApiController, RegisterApiController, RegisterUserShopApiController};
use App\Http\Controllers\Api\V1\Data\{AdminDashboardController,
    CategoriesProductApiController,
    OrdersApiController,
    ProductsApiController,
    ShopsApiController,
    UsersApiController,
    Webhookn8nApiController};
use App\Http\Controllers\Api\V1\Public\{AbandonedCartApiController,
    CheckoutApiController,
    CouponDiscountApiController,
    OrderBumpsApiController,
    PixelsApiController};
use App\Http\Controllers\Api\V1\Suitpay\WebhookApiController;
use Illuminate\Http\Request;
// region endpoints API V1
Route::group(['prefix' => 'v1/public', 'as' => 'api.public.', 'namespace' => 'Api\V1\Public'], function () {

    Route::group(['prefix' => 'telegram', 'as' => 'telegram.', 'namespace' => 'Telegram'], function () {
        Route::post('webhook', [TelegramGroupController::class, 'webhook'])->name('webhook');
        Route::get('get-invite-link/{payment:external_identification}', [TelegramGroupController::class, 'getInviteLink'])->name('getInviteLink');
        Route::get('is-group-active/{telegram}', [TelegramGroupController::class, 'isGroupActive'])->name('isGroupActive');;
    });

    Route::post('upload-imagem-richEditor', function (Request $request) {
        $file = $request->file('file');

        $path = Storage::disk('s3')->put('uploads', $file);

        return response()->json(['url' => Storage::disk('s3')->url($path)]);
    })->name('upload-imagem-richEditor');

    Route::post('upload-imagem', function (Request $request) {
        $image      = $request->base64;
        $extension  = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        $replace    = substr($image, 0, strpos($image, ',') + 1);
        $image      = str_replace($replace, '', $image);
        $image      = str_replace(' ', '+', $image);
        $image_name = 'uploads/' . time() . '.' . $extension;
        $res        = Storage::disk('s3')->put($image_name, base64_decode($image));

        if ($res) {
            return response()->json(['url' => Storage::disk('s3')->url($image_name)]);
        }

        return response()->json(['error' => 'Image upload failed'], 500);
    })->name('upload-imagem');

    Route::post('webhooks/suitpay/updateOrderByTransation', [WebhookApiController::class, 'updateOrderByTransation'])
        ->name('webhooks.suitpay.updateOrderByTransation');

    Route::controller(CouponDiscountApiController::class)->group(function () {
        Route::post('discounts/validateCoupon', 'validateCoupon')
            ->name('discounts.validateCoupon');

        Route::post('discounts/automaticCoupon', 'automaticCoupon')
            ->name('discounts.automaticCoupon');

        Route::get('couponsDiscounts/{product:id}', 'index')
            ->name('couponsDiscounts.index');
    });

    Route::post('abandoned-carts', [AbandonedCartApiController::class, 'store'])
        ->name('abandoned-carts.store');

    Route::get('pixels/{product:id}', [PixelsApiController::class, 'index'])
        ->name('pixels.index');

    Route::get('orderBumps/{product:id}', [OrderBumpsApiController::class, 'index'])
        ->name('orderBumps.index');

    Route::controller(CheckoutApiController::class)->group(function () {
        Route::post('checkout/pay', 'pay')->name('checkout.pay');
    });

    Route::get('checkout/check-payment/{order_hash}', [CheckoutApiController::class, 'checkPayment'])
        ->name('checkout.checkPayment');

    Route::get('checkout/card-installments', [CheckoutApiController::class, 'cardInstallments'])
        ->name('checkout.cardInstallments');
});

Route::group(['prefix' => 'v1/auth', 'as' => 'api.auth.', 'namespace' => 'Api\V1\Auth'], function () {

    Route::controller(AuthApiController::class)->group(function () {
        Route::post('/login', 'login')
            ->middleware(['guest'])
            ->name('login');

        Route::post('/logout', 'logout')
            ->middleware(['auth:sanctum'])
            ->name('logout');

        Route::get('recovery-password', 'recoveryPassword');
    });

    Route::post('/password/reset', 'ResetPasswordController')
        ->middleware(['guest'])
        ->name('password.reset');

    Route::controller(RegisterApiController::class)->group(function () {
        Route::post('/register', 'store')
            ->middleware(['guest'])
            ->name('register');
    });

    Route::post('register-user-shop', RegisterUserShopApiController::class)->name('auth.registerUserShop');
});

Route::group(['prefix' => 'v1/data', 'as' => 'api.data.', 'namespace' => 'Api\V1\Data', 'middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard/products/data', [AdminDashboardController::class, 'getDashboardData'])->name('dashboard.products.data');
    });

    Route::controller(UsersApiController::class)->group(function () {
        Route::get('/users/me', 'me');
        Route::put('/users/update', 'update');
        Route::put('/users/updatePassword', 'updatePassword');
        Route::post('/users/external-logout', 'logout');
    });

    Route::controller(ShopsApiController::class)->group(function () {
        Route::put('/shops/update', 'update');
        Route::post('/shops/regenerate-token', 'regenerateToken')
            ->withoutMiddleware('auth:sanctum');
    });

    Route::controller(OrdersApiController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::get('/orders/{order}/show', 'show');
    });

    Route::controller(\App\Http\Controllers\Api\V1\Data\AbandonedCartsApiController::class)->group(function () {
        Route::get('/abandoned-carts', 'index');
        Route::get('/abandoned-carts/{abandonedCart}/show', 'show');
    });

    Route::post('/webhookn8n', [Webhookn8nApiController::class, 'webhookn8n']);

    Route::controller(\App\Http\Controllers\Api\V1\Data\CheckoutApiController::class)->group(function () {
        Route::post('/checkout/pay', 'pay');
        Route::post('/checkout/card-installments', 'cardInstallments');
    });

    Route::controller(ProductsApiController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/{product}/show', 'show');
    });

    Route::controller(CategoriesProductApiController::class)->group(function () {
        Route::get('/categories-product', 'index');
    });
});
// endregion API V1

// region endpoints API V2

Route::group(['prefix' => 'v2/auth', 'as' => 'api.v2.auth.', 'namespace' => 'Api\V2\Auth'], function () {

    Route::post('register-user-shop', \App\Http\Controllers\Api\V2\Auth\RegisterUserShopApiController::class)
        ->name('registerUserShop');

});

Route::group(['prefix' => 'v2/data', 'as' => 'api.v2.data.', 'namespace' => 'Api\V2\Data', 'middleware' => 'auth:sanctum'], function () {

    Route::controller(\App\Http\Controllers\Api\V2\Data\ShopsApiController::class)->group(function () {
        Route::put('/shops/update', 'update')->name('update');
    });

});

// endregion API V2
