<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_orders', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });
    }
};
