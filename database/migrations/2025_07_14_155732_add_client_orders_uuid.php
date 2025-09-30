<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('client_orders_uuid')->after('user_id')->nullable()
                ->unique()->index();
        });

        DB::statement(
            "UPDATE checkout.orders
                SET client_orders_uuid = UUID()
                WHERE client_orders_uuid IS NULL"
        );
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('client_orders_uuid');
        });
    }
};
