<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('up_sells', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Product::class, 'product_id')->index()->constrained();
            $table->foreignIdFor(\App\Models\Product::class, 'product_offer_id')->index()->nullable()->constrained('products');
            $table->string('name')->nullable();
            $table->string('when_offer')->default('AFTER_ORDER_WITH_CREDIT_CARD');
            $table->string('when_accept')->default('REDIRECT_TO_THANKS_PAGE');
            $table->string('when_reject')->default('REDIRECT_TO_THANKS_PAGE');
            $table->text('text_accept');
            $table->text('text_reject');
            $table->string('color_button_accept')->default('#00FF00');
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->schemalessAttributes('attributes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_bumps');
    }
};
