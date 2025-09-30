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
        Schema::table('abandoned_carts', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Affiliate::class, 'affiliate_id')
                ->index()
                ->nullable()
                ->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abandoned_carts', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\Affiliate::class, 'affiliate_id');
        });
    }
};
