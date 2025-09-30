<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coproducers', function (Blueprint $table) {
            $table->integer('duration')
                ->nullable()
                ->comment('duration in days')
                ->after('valid_until_at');
        });
    }

    public function down(): void
    {
        Schema::table('coproducers', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
