<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Product::class, 'parent_id')->nullable()->index()->constrained('products');
            $table->foreignIdFor(\App\Models\Shop::class)->index()->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropConstrainedForeignId('shop_id');
        });
    }
};
