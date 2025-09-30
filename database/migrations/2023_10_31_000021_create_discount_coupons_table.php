<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCouponsTable extends Migration
{
    public function up(): void
    {
        Schema::create('coupons_discount', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('type', array_values(\App\Enums\TypeDiscountEnum::getDescriptions()));
            $table->integer('quantity')->nullable();
            $table->decimal('minimum_price_order')->default(0);
            $table->boolean('automatic_application')->default(0);
            $table->boolean('once_per_customer')->default(false);
            $table->boolean('newsletter_abandoned_carts')->default(false);
            $table->boolean('only_first_order')->default(false);
            $table->json('payment_methods')->nullable();
            $table->datetime('start_at');
            $table->datetime('end_at');
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
