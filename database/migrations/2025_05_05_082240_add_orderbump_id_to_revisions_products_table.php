<?php

use App\Models\OrderBump;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('revisions_products', function (Blueprint $table) {
            $table->foreignIdFor(OrderBump::class, 'orderBump_id')
                ->nullable()
                ->index()
                ->after('offer_id')
                ->constrained('order_bumps')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('revisions_products', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor('orderBump_id');
        });
    }
};
