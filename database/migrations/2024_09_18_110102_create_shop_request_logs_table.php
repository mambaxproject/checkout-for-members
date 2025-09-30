<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('shop_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop::class)->index()->constrained();
            $table->string('url');
            $table->integer('status_code');
            $table->json('content');
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_log_requests');
    }

};
