<?php

namespace App\Repositories;

use App\Enums\TypeDiscountEnum;
use App\Models\CouponDiscount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DiscountCouponRepository
{
    public function __construct(
        protected CouponDiscount $model
    ) { }

    private function buildQuery(): Builder
    {
        $query = $this->model::query()->active();;

        $code = request('code') ?? '';
        if (! empty($code)) {
            $query = $query->where('code', 'like', '%'.$code.'%');
        }

        return $query;
    }

    public function all(): Collection
    {
        return $this->buildQuery()
                ->latest()
                ->get();
    }

    public function paginate(int $limit = 15): LengthAwarePaginator
    {
        return $this->buildQuery()
                ->latest()
                ->paginate($limit);
    }

    public function validateCoupon(string $code): ?CouponDiscount
    {
        $query = $this->model::query();

        $query->active()
            ->whereCode($code)
            ->whereRaw('NOW() BETWEEN start_at AND end_at');

        return $query->first();
    }

    public function getValueDiscount(CouponDiscount $coupon, $total): float
    {
        if ($coupon->type == TypeDiscountEnum::VALUE->name) {
            return $coupon->amount;
        } else {
            return ($total * $coupon->amount) / 100;
        }
    }

    public function getById(int $couponId): Collection
    {
        return $this->model::where('id', $couponId)->get();
    }
}
