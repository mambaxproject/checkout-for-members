<?php

use App\Models\Coproducer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignIdFor(Coproducer::class, 'coproducer_id')
                ->index()
                ->nullable()
                ->after('affiliate_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Coproducer::class, 'coproducer_id');
        });
    }
};
