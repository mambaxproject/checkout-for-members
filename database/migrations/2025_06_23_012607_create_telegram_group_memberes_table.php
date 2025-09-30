<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telegram_group_members', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\TelegramGroup::class, 'telegram_group_id')
                ->index()
                ->constrained('telegram_groups')
                ->nullOnDelete()
                ->cascadeOnDelete();

            $table->foreignIdFor(\App\Models\Order::class, 'order_id')
                ->index()
                ->constrained('orders')
                ->nullOnDelete()
                ->cascadeOnDelete();

            $table->string('telegram_username')->nullable();
            $table->string('telegram_user_id')->nullable();
            $table->string('invite_link')->nullable();

            $table->string('status')->default(\App\Enums\SituationTelegramGroupMemberEnum::PENDING->name);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_group_memberes');
    }
};
