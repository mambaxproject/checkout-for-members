<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->decimal('amount');
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->foreignIdFor(\App\Models\Order::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Product::class)->nullable()->constrained();
            $table->text('link_checkout');
            $table->json('infosProduct');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abandoned_cards');
    }
};
