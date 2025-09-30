<?php

use App\Enums\TypeItemOrderEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('item_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amount');
            $table->integer('quantity');
            $table->enum('type', array_column(TypeItemOrderEnum::cases(), 'name'))->default(TypeItemOrderEnum::CART->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
