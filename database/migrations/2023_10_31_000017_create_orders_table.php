<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop::class, 'shop_id')->index()->constrained();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->index()->constrained();
            $table->decimal('amount', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('net_amount', 15, 2)->comment("Valor líquido da cobrança após desconto das taxas do gateway");
            $table->timestamps();
            $table->softDeletes();
        });
    }

}
