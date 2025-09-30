<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('pixels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\PixelService::class)->index()->constrained();
            $table->string('name');
            $table->string('pixel_id');
            $table->boolean('mark_billet')->default(false);
            $table->boolean('mark_pix')->default(false);
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_pixels');
    }

};
