<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(\App\Models\Shop::class)->index()->constrained();
            $table->foreignIdFor(\App\Models\Product::class)->index()->constrained();
            $table->boolean('default')->default(0);
            $table->json('settings')->nullable();
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
