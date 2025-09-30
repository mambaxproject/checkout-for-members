<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coproducers', function (Blueprint $table) {
            $table->renameColumn('percentage_commission_producer_sales', 'percentage_commission');
            $table->renameColumn('valid_until_at_producer_sales', 'valid_until_at');
            $table->dropColumn(['percentage_commission_affiliates_sales', 'valid_until_at_affiliates_sales']);
        });

        DB::table('coproducers')->update([
            'valid_until_at' => DB::raw('DATE_ADD(created_at, INTERVAL 30 DAY)'),
        ]);
    }

};
