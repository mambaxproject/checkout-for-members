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
        Schema::create('telegram_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Shop::class, 'shop_id')
                ->index()
                ->constrained('shops')
                ->nullOnDelete()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('chat_id')->nullable();
            $table->string('status')->default(\App\Enums\SituationTelegramGroupEnum::PENDING->name);

            $table->foreignIdFor(\App\Models\Product::class, 'product_id')
                ->index()
                ->constrained('products')
                ->nullOnDelete()
                ->cascadeOnDelete();

            $table->string('code')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_groups');
    }
};
