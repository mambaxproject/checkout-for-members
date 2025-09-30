<?php

namespace App\Http\Controllers\Checkout;

use App\Models\{Affiliate, Order, Product};
use App\Helpers\UTMLinkVIewHelper;
use Illuminate\Http\{RedirectResponse, Response};
use Illuminate\Support\Facades\{Crypt};
use Illuminate\View\View;

class ProductController
{
    public function product(Product $product): View|RedirectResponse
    {
        $product->append('is_recurring', 'has_first_payment');
        $product->load('shop:id,name');

        $shop          = $product->shop;
        $parentProduct = $product->parentProduct;
        $activeOffers  = $parentProduct->activeOffers($parentProduct->paymentType)->get();

        abort_if($activeOffers->doesntContain($product), Response::HTTP_NOT_FOUND, 'Produto não está disponível');

        $checkout                 = $parentProduct->checkout;
        $checkoutSettings         = $checkout->settings;
        $checkoutVerticalBanner   = $checkout->verticalBanner;
        $checkoutHorizontalBanner = $checkout->horizontalBanner;

        $appsShop = $shop->apps()->with('app:id,slug')->active()->get()->mapWithKeys(fn ($app) => [$app->app->slug => $app]);

        $affiliate = null;

        if ($affiliateCode = session()->get('affiliate_id', false)) {
            $affiliate = Affiliate::find($affiliateCode, ['user_id']);
        }

        $pixels = $parentProduct->pixels()
            ->whereNull('user_id')
            ->when($affiliate, fn ($query) => $query->orWhere('user_id', $affiliate->user_id))
            ->with(['pixelService'])
            ->get();

         UTMLinkVIewHelper::countView($product);

        return view('checkout.products.index', compact(
            'product',
            'parentProduct',
            'checkoutSettings',
            'pixels',
            'appsShop',
            'checkoutVerticalBanner',
            'checkoutHorizontalBanner'
        ));
    }

    public function thanks(string $order_hash): View
    {
        $order = Order::with(['items' => function ($q) {
            $q->with('product:id,name');
        }, 'payments', 'affiliate:id,user_id'])
            ->withCount('items')
            ->findOrFail(Crypt::decryptString($order_hash), ['id', 'amount']);

        $payment       = $order->payment;
        $product       = $order->item->product;
        $parentProduct = $product->parentProduct;

        $pixels = $parentProduct
            ->pixels()
            ->with(['pixelService'])
            ->where(function ($query) use ($order) {
                if ($order->affiliate) {
                    $query->where('user_id', $order->affiliate->user_id);
                }

                $query->orWhereNull('user_id');
            })
            ->get();

        $upSells = $parentProduct
            ->upSells()
            ->with(['product_offer.media', 'product_offer:id,name,price,priceFirstPayment'])
            ->get();

        return view('checkout.thanks', compact(
            'order',
            'payment',
            'pixels',
            'product',
            'upSells'
        ));
    }

}
