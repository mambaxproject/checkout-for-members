<?php

use App\Models\AbandonedCart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abandoned_carts_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AbandonedCart::class, 'abandoned_cart_id')
                ->constrained();
            $table->string('utm_source');
            $table->string('utm_campaign');
            $table->string('utm_medium')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts_tracking');
    }
};
