<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{AutomaticCouponDiscountRequest, ValidateCouponDiscountRequest};
use App\Http\Resources\Api\CouponDiscountResource;
use App\Models\{Customer, Product};
use App\Repositories\DiscountCouponRepository;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};
use Symfony\Component\HttpFoundation\Response;

class CouponDiscountApiController extends Controller
{
    public function __construct(
        public DiscountCouponRepository $discountCouponRepository
    ) {}

    public function automaticCoupon(AutomaticCouponDiscountRequest $request): JsonResponse
    {
        $user    = Customer::where('email', $request->customer_email)->first();
        $coupons = Product::findOrFail($request->product_id)
            ->couponsDiscount()
            ->with(['offers', 'usage'])
            ->where('automatic_application', true)
            ->whereRaw('NOW() BETWEEN start_at AND end_at')
            ->get();

        foreach ($coupons as $coupon) {
            $response = (new DiscountService)->validateCoupon(
                $coupon,
                $request->amount,
                $user,
                $request->is_affiliate_link,
                $request->offer_id
            );

            if ($response['success']) {
                return response()->json($response, $response['http_code']);
            }
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'],
            'data'    => [],
        ], Response::HTTP_NOT_FOUND);
    }

    public function index(Product $product): JsonResponse
    {
        $couponsDiscount = QueryBuilder::for($product->couponsDiscount())
            ->allowedFilters([
                'payment_methods',
                AllowedFilter::exact('type'),
                AllowedFilter::exact('auto_application'),
                AllowedFilter::exact('type'),
            ])
            ->active()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => CouponDiscountResource::collection($couponsDiscount),
        ], Response::HTTP_OK);
    }

    public function validateCoupon(ValidateCouponDiscountRequest $request): JsonResponse
    {
        try {
            $coupon = Product::findOrFail($request->product_id)
                ->couponsDiscount()
                ->with(['offers', 'usage'])
                ->whereCode($request->code)
                ->whereRaw('NOW() BETWEEN start_at AND end_at')
                ->firstOrFail();

            $user = Customer::where('email', $request->customer_email)
                ->first();

            $response = (new DiscountService)->validateCoupon(
                $coupon,
                $request->amount,
                $user,
                $request->is_affiliate_link,
                $request->offer_id
            );

            return response()->json($response, $response['http_code']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

}
