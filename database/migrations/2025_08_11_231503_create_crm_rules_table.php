<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop::class)->index()->constrained();
            $table->text('origin');
            $table->text('event_trigger');
            $table->unsignedBigInteger('funnel_id');
            $table->unsignedBigInteger('step_id');
            $table->text('funnel_name');
            $table->text('step_name');
            $table->string('status')->default(\App\Enums\StatusEnum::ACTIVE->name);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_rules');
    }
};
