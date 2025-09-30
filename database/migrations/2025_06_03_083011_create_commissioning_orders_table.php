<?php

use App\Models\{Order, User};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissioning_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'commissioned_id')->index()->constrained()->cascadeOnDelete();
            $table->enum('type', ['AFFILIATE', 'CO_PRODUCER']);
            $table->foreignIdFor(Order::class, 'order_id')->index()->constrained()->cascadeOnDelete();
            $table->decimal('value', 10, 4);
            $table->string('type_commission')->nullable()->comment('percentage | fixed value');
            $table->decimal('value_commission')->nullable();
            $table->schemalessAttributes('attributes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissioning_orders');
    }
};
