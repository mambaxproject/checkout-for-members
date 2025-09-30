<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utm_links', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Product::class, 'product_id')
                ->index()
                ->constrained('products')
                ->nullOnDelete()
                ->cascadeOnDelete();

            $table->string('utm_source');
            $table->string('utm_medium');
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utm_links');
    }
};
