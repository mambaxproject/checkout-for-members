<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('app_shop', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop::class)->index()->constrained();
            $table->foreignIdFor(\App\Models\App::class)->index()->constrained();
            $table->json('data')->nullable();
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_app');
    }

};
