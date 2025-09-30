<?php

use App\Models\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignIdFor(ProductType::class, 'type_id')
                ->index()
                ->nullable()
                ->after('code')
                ->default(1);
        });

        DB::statement("UPDATE checkout.products SET type_id = 1 WHERE type_id IS NULL");
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });
    }
};
