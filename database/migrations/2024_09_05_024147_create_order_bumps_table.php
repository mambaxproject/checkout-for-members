<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('order_bumps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Product::class, 'product_id')->index()->constrained();
            $table->foreignIdFor(\App\Models\Product::class, 'product_offer_id')->index()->nullable()->constrained('products');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('title_cta')->nullable();
            $table->decimal('promotional_price', 10, 2)->nullable();
            $table->json('payment_methods')->nullable();
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_bumps');
    }
};
