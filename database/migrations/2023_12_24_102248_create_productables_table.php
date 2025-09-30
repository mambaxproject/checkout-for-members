<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('productables', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Product::class)->constrained();
            $table->morphs('productable');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productables');
    }
};
