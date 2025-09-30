<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->references('id')
                ->on('notification_types');
            $table->foreignId('action_id')->references('id')
                ->on('notification_actions');
            $table->foreignId('event_id')->references('id')
                ->on('notification_events');
            $table->longText('text_whatsapp')->nullable();
            $table->integer('dispatch_time');
            $table->string('url_embed')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_notifications');
    }
};
