<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->integer('code')->nullable();
            $table->string('uf');
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
