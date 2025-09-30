<?php

namespace App\Services;

use App\Models\{CouponDiscount, Customer};
use App\Repositories\DiscountCouponRepository;
use Symfony\Component\HttpFoundation\Response;

class DiscountService
{
    private DiscountCouponRepository $discountCouponRepository;

    public function __construct()
    {
        $this->discountCouponRepository = new DiscountCouponRepository(new CouponDiscount);
    }

    public function validateCoupon(
        CouponDiscount $coupon,
        float $amount,
        ?Customer $customer,
        bool $isAffiliateLink,
        int $offer_id
    ): array {

        if ($coupon->offers()->exists() and ! $coupon->offers()->where('products.id', $offer_id)->exists()) {
            return [
                'success'   => false,
                'http_code' => Response::HTTP_FORBIDDEN,
                'message'   => 'Validation errors',
                'data'      => [
                    'offer' => 'Este cupom não é válido para este produto.',
                ],
            ];
        }

        if ($coupon->quantity && $coupon->usage->count() >= $coupon->quantity) {
            return [
                'success'   => false,
                'http_code' => Response::HTTP_FORBIDDEN,
                'message'   => 'Validation errors',
                'data'      => [
                    'amount' => 'Cupom excedido.',
                ],
            ];
        }

        if ($amount < $coupon->minimum_price_order) {
            return [
                'success'   => false,
                'http_code' => Response::HTTP_FORBIDDEN,
                'message'   => 'Validation errors',
                'data'      => [
                    'amount' => 'O valor mínimo para aplicação do cupom é ' . $coupon->minimum_price_order . ' reais',
                ],
            ];
        }

        if (! $coupon->allow_affiliate_links and $isAffiliateLink) {
            return [
                'success'   => false,
                'http_code' => Response::HTTP_FORBIDDEN,
                'message'   => 'Validation errors',
                'data'      => [
                    'allow_affiliate_links' => 'Este cupom não é válido para este link',
                ],
            ];
        }

        if ($coupon->only_first_order and $customer) {
            if ($customer->orders()->count()) {
                return [
                    'success'   => false,
                    'http_code' => Response::HTTP_FORBIDDEN,
                    'message'   => 'Validation errors',
                    'data'      => [
                        'coupon' => 'Cupom válido apenas para a primeira compra.',
                    ],
                ];
            }
        }

        if ($coupon->once_per_customer and $customer) {

            $order = $customer->orders()->whereHas('discounts', function ($query) use ($coupon) {
                $query->where('coupon_discount_id', $coupon->id);
            })->first('id');

            if ($order) {
                return [
                    'success'   => false,
                    'http_code' => Response::HTTP_FORBIDDEN,
                    'message'   => 'Validation errors',
                    'data'      => [
                        'coupon' => 'Cupom restrito a uma única utilização.',
                    ],
                ];
            }
        }

        $valueDiscount = $this->discountCouponRepository->getValueDiscount($coupon, $amount);

        if (($valueDiscount >= $amount) || $valueDiscount < 0) {
            return [
                'success'   => false,
                'http_code' => Response::HTTP_FORBIDDEN,
                'message'   => 'Validation errors',
                'data'      => [
                    'amount' => 'Este cupom não pode ser usado nesta compra.',
                ],
            ];
        }

        return [
            'success' => true,
            'data'    => [
                'message' => 'Cupom aplicado.',
                'coupon'  => $coupon,
            ],
            'http_code' => Response::HTTP_OK,
        ];
    }
}
