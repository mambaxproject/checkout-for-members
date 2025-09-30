<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pixels', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class, 'user_id')
                ->nullable()
                ->index()
                ->after('pixel_service_id')
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('pixels', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor('user_id');
        });
    }
};
