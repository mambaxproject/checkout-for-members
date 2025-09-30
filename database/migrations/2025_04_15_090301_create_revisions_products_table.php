<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisions_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Product::class, 'product_id')->index()->constrained('products');
            $table->foreignIdFor(\App\Models\Product::class, 'offer_id')->nullable()->index()->constrained('products');
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->index()->constrained('users');
            $table->string('key');
            $table->json('old_value');
            $table->json('new_value');
            $table->enum('status', ['pending', 'approved', 'reproved', 'canceled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisions_products');
    }
};
