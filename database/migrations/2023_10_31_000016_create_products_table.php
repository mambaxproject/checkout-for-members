<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_9165497')->references('id')->on('category_products');
            $table->string('name')->nullable();
            $table->string('code')->unique()->index()->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->longText('infos')->nullable();
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->string('situation')->default(\App\Enums\SituationProductEnum::DRAFT->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
