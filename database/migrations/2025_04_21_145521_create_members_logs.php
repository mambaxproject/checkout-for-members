<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members_logs', function (Blueprint $table) {
            $table->id(); 
            $table->string('level', 50);
            $table->text('message'); 
            $table->json('context')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members_logs');
    }
};
