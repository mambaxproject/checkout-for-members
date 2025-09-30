<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\ProductTypeSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->timestamps();
        });

        $this->callSeeder();
    }

    public function down(): void
    {
        Schema::dropIfExists('product_types');
    }

    private function callSeeder(): void
    {
        if (app()->runningInConsole()) {
            $seeder = new ProductTypeSeeder();
            $seeder->run();
        }
    }
};
