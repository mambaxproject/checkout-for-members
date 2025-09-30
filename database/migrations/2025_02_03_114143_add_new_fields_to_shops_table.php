<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('client_id_banking')->nullable();
            $table->string('client_secret_banking')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['client_id_banking', 'client_secret_banking']);
        });
    }
};
