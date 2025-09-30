<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons_discount', function (Blueprint $table) {
            $table->boolean('allow_affiliate_links')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('coupons_discount', function (Blueprint $table) {
            $table->dropColumn('allow_affiliate_links');
        });
    }
};
