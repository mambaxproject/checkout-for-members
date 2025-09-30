<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDiscountOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('discount_orders', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Order::class, 'order_id')->index()->constrained('orders');
            $table->foreignIdFor(\App\Models\CouponDiscount::class, 'coupon_discount_id')->index()->constrained('coupons_discount');
        });
    }
}
