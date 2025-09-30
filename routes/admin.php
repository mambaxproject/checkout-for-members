<?php

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', '2fa', \App\Http\Middleware\IsAdminMiddleware::class]], function () {
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

    Route::get('/', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::get('users/impersonate/{user}', 'UsersController@impersonate')->name('users.impersonate');
    Route::get('users/stop-impersonate', 'UsersController@stopImpersonate')->name('users.stopImpersonate');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Gender
    Route::delete('genders/destroy', 'GenderController@massDestroy')->name('genders.massDestroy');
    Route::resource('genders', 'GenderController');

    // Marital Status
    Route::delete('marital-statuses/destroy', 'MaritalStatusController@massDestroy')->name('marital-statuses.massDestroy');
    Route::resource('marital-statuses', 'MaritalStatusController');

    // State
    Route::delete('states/destroy', 'StateController@massDestroy')->name('states.massDestroy');
    Route::resource('states', 'StateController');

    // City
    Route::delete('cities/destroy', 'CityController@massDestroy')->name('cities.massDestroy');
    Route::post('cities/media', 'CityController@storeMedia')->name('cities.storeMedia');
    Route::post('cities/ckmedia', 'CityController@storeCKEditorImages')->name('cities.storeCKEditorImages');
    Route::resource('cities', 'CityController');

    // Category Product
    Route::delete('category-products/destroy', 'CategoryProductController@massDestroy')->name('category-products.massDestroy');
    Route::post('category-products/media', 'CategoryProductController@storeMedia')->name('category-products.storeMedia');
    Route::post('category-products/ckmedia', 'CategoryProductController@storeCKEditorImages')->name('category-products.storeCKEditorImages');
    Route::resource('category-products', 'CategoryProductController');

    Route::resource('shops', 'ShopController');
    Route::post('shops/media', 'ShopController@storeMedia')->name('shops.storeMedia');

    // Product
    Route::get('products/{product}/revisions', 'ProductController@revisions')->name('products.revisions');
    Route::put('products/{product}/revisions/{revisionsProduct}/update', 'ProductController@updateRevision')->name('products.updateRevision');
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');

    // PRODUTOS APPROVE
    Route::get('products/{product}/review', 'ProductController@review')->name('products.review');
    Route::put('products/{product}/updateSituation', 'ProductController@updateSituation')->name('products.updateSituation');
    Route::resource('products', 'ProductController');

    // Order
    Route::delete('orders/destroy', 'OrderController@massDestroy')->name('orders.massDestroy');
    Route::get('products/search', 'ProductController@search')->name('products.search');
    Route::resource('orders', 'OrderController');

    // Item Order
    Route::delete('item-orders/destroy', 'ItemOrderController@massDestroy')->name('item-orders.massDestroy');
    Route::resource('item-orders', 'ItemOrderController');

    // Order Payment
    Route::delete('order-payments/destroy', 'OrderPaymentController@massDestroy')->name('order-payments.massDestroy');
    Route::resource('order-payments', 'OrderPaymentController');

    // Discount Order
    Route::delete('discount-orders/destroy', 'DiscountOrderController@massDestroy')->name('discount-orders.massDestroy');
    Route::resource('discount-orders', 'DiscountOrderController');

    // Discount Coupon
    Route::delete('discount-coupons/destroy', 'DiscountCouponController@massDestroy')->name('discount-coupons.massDestroy');
    Route::resource('discount-coupons', 'DiscountCouponController');

    // Affiliates
    Route::delete('affiliates/destroy', 'AffiliatesController@massDestroy')->name('affiliates.massDestroy');
    Route::resource('affiliates', 'AffiliatesController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    Route::controller(\App\Http\Controllers\Admin\DashboardController::class)->group(function () {
        Route::get('dashboard/products', 'products')->name('dashboard.products');
    });

});
