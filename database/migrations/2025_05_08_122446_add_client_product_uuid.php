<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->uuid('client_product_uuid')->after('infos')->nullable()
                ->unique()->index();
        });

        DB::statement(
            "UPDATE products
        SET client_product_uuid = UUID()
        WHERE client_product_uuid IS NULL
        AND parent_id IS NULL"
        );
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('client_product_uuid');
        });
    }
};
