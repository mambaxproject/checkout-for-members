<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->string('recurrency_id')->nullable()->index();
        });

        DB::table('order_payments')
            ->whereNotNull('payment_gateway_response')
            ->whereRaw('JSON_VALID(payment_gateway_response)')
            ->update([
                'recurrency_id' => DB::raw("JSON_UNQUOTE(JSON_EXTRACT(payment_gateway_response, '$.recurrencyId'))"),
            ]);
    }

    public function down(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn('recurrency_id');
        });
    }
};
